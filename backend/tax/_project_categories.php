<?php
register_taxonomy(
    'project_categories',
    ['project'],
    [
        'label' => 'Projekt Kategorie',
        'labels' => [
            'name' => 'Projekt Kategorien',
            'singular_name' => 'Projekt Kategorie',
            'menu_name' => 'Kategorien',
            'all_items' => 'Alle Kategorien',
            'edit_item' => 'Kategorie bearbeiten',
            'view_item' => 'Kategorie ansehen',
            'update_item' => 'Kategorie updaten',
            'add_new_item' => 'Neue Kategorie hinzufügen',
            'not_found' => 'Keine Kategorie gefunden',
        ],
        'public' => true,
        'show_in_rest' => false,
        'show_in_nav_menus' => false,
        'show_admin_column' => true,
        'query_var' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'projekt-kategorie'],
    ],
);
