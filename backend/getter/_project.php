<?php

// -> Ein Projekt anhand der ID laden und für das Frontend aufbereiten
function get_project_by_id(int $id, string $image_size = 'm'): array
{
    $image_id = carbon_get_post_meta($id, 'image');
    $image_hover_id = carbon_get_post_meta($id, 'image_hover');
    $terms = get_the_terms($id, 'project_categories');

    $image = $image_id ? get_media_by_id($image_id, $image_size) : false;
    $image_hover = $image_hover_id ? get_media_by_id($image_hover_id, $image_size) : false;

    return [
        'id' => $id,
        'title' => get_the_title($id),
        'url' => get_permalink($id),
        'image' => $image,
        'image_hover' => $image_hover,
        // -> Ohne farbiges Bild wird das schwarzweisse Bild per CSS-Filter umgefärbt
        'needs_filter' => $image && !$image_hover,
        'image_position' => carbon_get_post_meta($id, 'image_position') ?: 'center',
        'description' => carbon_get_post_meta($id, 'description'),
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
