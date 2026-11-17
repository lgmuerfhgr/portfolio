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

        Field::make('text', 'link', 'Link')
            ->set_attribute('type', 'url')
            ->set_attribute('placeholder', 'https://')
            ->set_help_text('Optional: Externer Link zum Projekt (z.B. Website, Video, Case Study).'),

        Field::make('text', 'link_label', 'Link-Beschriftung')
            ->set_attribute('placeholder', 'Zum Projekt')
            ->set_help_text('Optional: Text des Link-Buttons. Standard: «Zum Projekt».')
            ->set_conditional_logic([
                ['field' => 'link', 'value' => '', 'compare' => '!='],
            ]),

        Field::make('select', 'image_position', 'Bildausschnitt')
            ->set_options([
                'center' => 'Mitte',
                'top' => 'Oben',
                'bottom' => 'Unten',
                'left' => 'Links',
                'right' => 'Rechts',
            ])
            ->set_default_value('center')
            ->set_help_text('Welcher Bereich der Bilder in der Projektübersicht sichtbar bleibt (Hochformat-Karten).'),

        // Felder hier einfügen
    ]);