<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Projekt')
    ->where('post_type', '=', 'project')
    ->add_fields([
        Field::make('image', 'image_main', 'Titelbild')
            ->set_required(true)
            ->set_help_text('Farbiges Bild, am besten im Hochformat 3:4. Wird automatisch schwarzweiss dargestellt und beim Hover farbig.'),

        Field::make('textarea', 'lead', 'Lead-Text')
            ->set_rows(4)
            ->set_help_text('Kurzer Text (ca. 2–4 Sätze), der auf der Startseite beim Hover über dem Projekt erscheint.'),

        Field::make('rich_text', 'description', 'Projekttext')
            ->set_required(true)
            ->set_help_text('Vollständiger Text, wird auf der Projektseite («Mehr erfahren») angezeigt.'),

        Field::make('media_gallery', 'gallery', 'Weitere Bilder')
            ->set_type(['image'])
            ->set_help_text('Optional: Bilder, die auf der Projektseite unter dem Text als Galerie erscheinen. Per Drag & Drop sortierbar.'),

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

        Field::make('complex', 'videos', 'Videos (Datei / Vimeo / Instagram)')
            ->set_help_text('Optional: Videos, die auf der Projektseite unter dem Link nebeneinander in einer Galerie erscheinen und mit Ton abgespielt werden können. Am saubersten (ohne Rahmen) sind eine Video-Datei oder Vimeo. Instagram zeigt immer seinen eigenen Kopf und Rahmen.')
            ->add_fields([
                Field::make('file', 'file', 'Video-Datei (MP4)')
                    ->set_type(['video'])
                    ->set_help_text('Empfohlen: Video direkt hochladen (MP4, möglichst unter ca. 20 MB). Wird ohne Rahmen angezeigt.'),
                Field::make('text', 'url', 'oder Link (Vimeo / Instagram)')
                    ->set_attribute('placeholder', 'https://vimeo.com/123456789 oder https://www.instagram.com/reel/…')
                    ->set_help_text('Nur nötig, wenn keine Datei hochgeladen ist. Der Instagram-Beitrag muss öffentlich sein.'),
                Field::make('select', 'ratio', 'Format (Datei / Vimeo)')
                    ->set_options([
                        '16 / 9' => 'Querformat 16:9',
                        '9 / 16' => 'Hochformat 9:16 (z.B. Reel)',
                        '4 / 5' => 'Hochformat 4:5',
                        '1 / 1' => 'Quadratisch 1:1',
                    ])
                    ->set_default_value('16 / 9')
                    ->set_help_text('Muss zum Video passen, sonst wird es beschnitten. Instagram passt die Grösse automatisch an.'),
                Field::make('text', 'caption', 'Bildunterschrift (optional)'),
            ])
            ->set_header_template('<%- caption || url || "Video-Datei" %>')
            ->set_layout('tabbed-vertical'),

        Field::make('select', 'image_position', 'Bildausschnitt')
            ->set_options([
                'center' => 'Mitte',
                'top' => 'Oben',
                'bottom' => 'Unten',
                'left' => 'Links',
                'right' => 'Rechts',
            ])
            ->set_default_value('center')
            ->set_help_text('Welcher Bereich des Titelbilds in den Hochformat-Karten sichtbar bleibt.'),

        // Felder hier einfügen
    ]);
