<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Inhaltsfeld-Konfiguration der globalen Einstellungen des Themes
Container::make('theme_options', 'Globales')
    ->add_fields([
        Field::make('complex', 'contact', 'Kontakt')
            ->add_fields('link', 'Socials', array(
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('text', 'link', 'Link')->set_required(),
            ))
            ->add_fields('email', 'E-Mail', array(
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('text', 'email', 'E-Mail')->set_required(),
            ))
            ->set_header_template('<%- title %>')
            ->set_layout('tabbed-vertical'),
    ]);
