<?php
/**
 * Funktionen in dieser Datei können überall im Projekt verwendet werden.
 * Wenn eine neue globale Funktion benötigt wird, bitte hier ergänzen.
 *
 * Diese globalen Funktionen sind nicht Wordpress-spezifisch.
 */

// -> PHP-Daten in der Browser-Konsole anzeigen
function js_log(mixed ...$content): void
{
    foreach ($content as $c) {
        echo '<script>console.log(' . json_encode($c) . ')</script>';
    }
}

// -> Details einer Mediendatei anhand der ID laden
function get_media_by_id(int|string $id, string $size = 'm'): false|array
{
    $id = (int) $id;
    $data = [
        'title' => get_the_title($id),
        'alt' => get_post_meta($id, '_wp_attachment_image_alt', true) ?: get_the_title($id),
    ];
    $image = wp_get_attachment_image_src($id, $size);
    if ($image) {
        $data['src'] = $image[0];
        $data['type'] = 'image';
        $data['width'] = (int) $image[1];
        $data['height'] = (int) $image[2];
        $data['srcset'] = wp_get_attachment_image_srcset($id, $size) ?: '';
    } else {
        $data['src'] = wp_get_attachment_url($id);
        $data['type'] = get_post_mime_type($id);
    }
    return $data;
}

// -> Ein <img>-Tag aus den Daten von get_media_by_id() erstellen
function get_media_image_tag(false|array $media, string $class = '', string $sizes = '100vw', bool $lazy = true): string
{
    if (empty($media['src'])) {
        return '';
    }
    $attributes = [
        'class' => $class,
        'src' => $media['src'],
        'alt' => $media['alt'] ?? '',
        'decoding' => 'async',
        'loading' => $lazy ? 'lazy' : 'eager',
    ];
    if (!empty($media['srcset'])) {
        $attributes['srcset'] = $media['srcset'];
        $attributes['sizes'] = $sizes;
    }
    if (!empty($media['width']) && !empty($media['height'])) {
        $attributes['width'] = $media['width'];
        $attributes['height'] = $media['height'];
    }
    $html = '<img';
    foreach ($attributes as $name => $value) {
        if ($value === '' && $name !== 'alt') {
            continue;
        }
        $html .= ' ' . $name . '="' . esc_attr($value) . '"';
    }
    return $html . '>';
}

// -> Optionale Anker-ID eines Moduls als HTML-Attribut ausgeben (für die Navigation, z.B. /#about)
function get_module_id_attr(array $data): string
{
    if (empty($data['anchor'])) {
        return '';
    }
    return ' id="' . esc_attr(sanitize_title($data['anchor'])) . '"';
}

// -> Rich-Text-Inhalte sicher und mit Absätzen ausgeben
function format_rich_text(?string $text): string
{
    if (!$text) {
        return '';
    }
    return wp_kses_post(wpautop($text));
}

// -> Ein Menu anhand des Keys laden
function get_menu_by_slug(string $slug): array
{
    $theme_locations = get_nav_menu_locations();
    if (!array_key_exists($slug, $theme_locations)) {
        return [];
    }
    $menu_obj = get_term($theme_locations[$slug], 'nav_menu');
    $menu = wp_get_nav_menu_items($menu_obj->name) ?: [];
    $items = [];
    foreach ($menu as $item) {
        $items[] = [
            'ID' => (int) $item->ID,
            'title' => $item->title,
            'url' => $item->url,
            'target' => $item->target,
        ];
    }
    return $items;
}
