<?php

// -> Ein Projekt anhand der ID laden und für das Frontend aufbereiten
//    $with_gallery: weitere Bilder nur laden, wenn sie gebraucht werden (Projektseite)
function get_project_by_id(int $id, string $image_size = 'm', bool $with_gallery = false): array
{
    // Titelbild; Fallback auf die früheren Felder «Bild (farbig)» / «Bild (schwarzweiss)»,
    // damit bestehende Projekte ohne Titelbild weiterhin ein Bild zeigen
    $image_id = carbon_get_post_meta($id, 'image_main')
        ?: get_post_meta($id, '_image_hover', true)
        ?: get_post_meta($id, '_image', true);
    $terms = get_the_terms($id, 'project_categories');
    $description = carbon_get_post_meta($id, 'description');
    $lead = carbon_get_post_meta($id, 'lead');

    $gallery = [];
    $videos = [];
    $pdfs = [];
    if ($with_gallery) {
        foreach (carbon_get_post_meta($id, 'gallery') ?: [] as $gallery_id) {
            $media = get_media_by_id($gallery_id, 'l');
            if (!empty($media['src'])) {
                $gallery[] = $media;
            }
        }
        foreach (carbon_get_post_meta($id, 'videos') ?: [] as $video) {
            $file = !empty($video['file']) ? wp_get_attachment_url((int) $video['file']) : false;
            $embed = $file
                ? ['type' => 'file', 'src' => $file, 'ratio' => get_video_ratio($video['ratio'] ?? '')]
                : get_video_embed($video['url'] ?? '', $video['ratio'] ?? '16 / 9');
            if ($embed) {
                $embed['caption'] = $video['caption'] ?? '';
                $videos[] = $embed;
            }
        }
        foreach (carbon_get_post_meta($id, 'pdfs') ?: [] as $pdf) {
            $file_id = (int) ($pdf['file'] ?? 0);
            $src = $file_id ? wp_get_attachment_url($file_id) : false;
            if (!$src || get_post_mime_type($file_id) !== 'application/pdf') {
                continue;
            }
            $path = get_attached_file($file_id);
            $pdfs[] = [
                'src' => $src,
                'title' => ($pdf['title'] ?? '') ?: get_the_title($file_id),
                'format' => ($pdf['format'] ?? '') === 'landscape' ? 'landscape' : 'portrait',
                'size' => $path && file_exists($path) ? size_format(filesize($path), 1) : '',
            ];
        }
    }

    return [
        'id' => $id,
        'title' => get_the_title($id),
        'url' => get_permalink($id),
        'image' => $image_id ? get_media_by_id($image_id, $image_size) : false,
        'image_position' => carbon_get_post_meta($id, 'image_position') ?: 'center',
        // Lead für die Startseite; leer -> Anfang des Projekttexts
        'lead' => $lead ? esc_html($lead) : $description,
        'description' => $description,
        'gallery' => $gallery,
        'videos' => $videos,
        'pdfs' => $pdfs,
        'skills' => carbon_get_post_meta($id, 'skills'),
        'link' => carbon_get_post_meta($id, 'link'),
        'link_label' => carbon_get_post_meta($id, 'link_label') ?: 'Zum Projekt',
        'categories' => is_array($terms) ? array_map(fn ($term) => $term->name, $terms) : [],
    ];
}

// -> Mehrere Projekte anhand ihrer IDs laden (Reihenfolge bleibt erhalten)
function get_projects_by_ids(array $ids): array
{
    $projects = [];
    foreach ($ids as $id) {
        $id = (int) $id;
        if ($id && get_post_status($id) === 'publish') {
            $projects[] = get_project_by_id($id);
        }
    }
    return $projects;
}

// -> Alle Projekte (optional gefiltert nach Kategorie-ID) laden
function get_projects_by_category(int $term_id = 0): array
{
    $args = [
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC'],
    ];
    if ($term_id) {
        $args['tax_query'] = [[
            'taxonomy' => 'project_categories',
            'field' => 'term_id',
            'terms' => $term_id,
        ]];
    }
    return get_projects_by_ids(get_posts($args));
}

// -> Optionen für die Kategorie-Auswahl im Modul "Übersicht Projekte"
function get_project_category_options(): array
{
    $options = ['' => '— Keine (manuelle Auswahl oder alle Projekte) —'];
    $terms = get_terms(['taxonomy' => 'project_categories', 'hide_empty' => false]);
    if (is_array($terms)) {
        foreach ($terms as $term) {
            $options[(string) $term->term_id] = $term->name;
        }
    }
    return $options;
}

// -> Einen Vimeo- oder Instagram-Link in Einbett-Daten umwandeln (false bei unbekanntem Link)
function get_video_embed(string $url, string $ratio = '16 / 9'): array|false
{
    $url = trim($url);
    if ($url === '') {
        return false;
    }

    // Vimeo: vimeo.com/123, vimeo.com/123/abcdef (nicht gelistet), player.vimeo.com/video/123?h=abcdef
    if (preg_match('~vimeo\.com/(?:.*?/)?(?:video/)?(\d+)(?:/([a-z0-9]+))?~i', $url, $match)) {
        $hash = $match[2] ?? '';
        if (!$hash && preg_match('~[?&]h=([a-z0-9]+)~i', $url, $hash_match)) {
            $hash = $hash_match[1];
        }
        return [
            'type' => 'vimeo',
            // dnt=1: Vimeo setzt keine Tracking-Cookies
            'src' => 'https://player.vimeo.com/video/' . $match[1] . '?' . http_build_query(array_filter([
                'h' => $hash,
                'dnt' => 1,
                'title' => 0,
                'byline' => 0,
                'portrait' => 0,
            ], fn ($value) => $value !== '')),
            'ratio' => get_video_ratio($ratio),
        ];
    }

    // Instagram: instagram.com/p/CODE, /reel/CODE, /reels/CODE, /tv/CODE
    if (preg_match('~instagram\.com/(?:[a-z0-9_.]+/)?(p|reels?|tv)/([a-z0-9_-]+)~i', $url, $match)) {
        $kind = strtolower($match[1]) === 'p' ? 'p' : (strtolower($match[1]) === 'tv' ? 'tv' : 'reel');
        return [
            'type' => 'instagram',
            'permalink' => 'https://www.instagram.com/' . $kind . '/' . $match[2] . '/',
        ];
    }

    return false;
}

// -> Nur bekannte Seitenverhältnisse zulassen
function get_video_ratio(string $ratio): string
{
    return in_array($ratio, ['16 / 9', '9 / 16', '4 / 5', '1 / 1'], true) ? $ratio : '16 / 9';
}
