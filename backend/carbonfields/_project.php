<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Projekt')
    ->where('post_type', '=', 'project')
    ->add_fields([
        Field::make('image', 'image', 'Bild (schwarzweiss)')
            ->set_required(true)
            ->set_help_text('Wird im Ruhezustand angezeigt.'),

        Field::make('image', 'image_hover', 'Bild (farbig)')
            ->set_required(true)
            ->set_help_text('Wird beim Darüberfahren mit der Maus angezeigt. Gleiches Format wie das erste Bild.'),

        Field::make('rich_text', 'description', 'Beschreibung')
            ->set_required(true)
            ->set_help_text('Hier wird eine kurze Beschreibung des Projekts benötigt.'),

        Field::make('rich_text', 'skills', 'Kompetenzen')
            ->set_help_text('Hier können die angewandten Kompetenzen des Projekts eingefügt werden.'),

        // Felder hier einfügen
    ]);