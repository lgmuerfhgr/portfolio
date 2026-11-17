<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Optionales Anker-Feld, damit Module über die Navigation angesprungen werden können (z.B. /#about)
$anchor_field = fn () => Field::make('text', 'anchor', 'Anker (ID)')
    ->set_help_text('Optional, z.B. «about». Im Menü dann als Link «/#about» verwenden.');

// -> Konfiguration der verschiedenen Module
Container::make('post_meta', 'Module')
    ->where('post_type', '=', 'page')
    ->add_fields([
        Field::make('complex', 'modules', 'Module')
            // -> example
            ->add_fields('example', 'Beispiel', [
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('image', 'image', 'Bild')->set_required(),
            ])
            // -> hero_front
            ->add_fields('hero_front', 'Hero Startseite', [
                Field::make('image', 'image', 'Bild')
                    ->set_required()
                    ->set_help_text('Signatur / Logo. Am besten als SVG oder transparentes PNG im Querformat.'),
                Field::make('checkbox', 'animate', 'Bild beim Laden «schreiben» (Einblend-Animation)')
                    ->set_default_value(true),
                $anchor_field(),
            ])
            // -> gallery_about
            ->add_fields('gallery_about', 'Galerie About', [
                Field::make('media_gallery', 'images', 'Bilder')
                    ->set_type(['image'])
                    ->set_required()
                    ->set_help_text('Farbige Bilder hochladen, Hochformat 3:4. Sie werden automatisch schwarzweiss dargestellt und beim Hover farbig.'),
                Field::make('text', 'title', 'Titel'),
                Field::make('rich_text', 'text', 'Text'),
                $anchor_field(),
            ])
            // -> overview_projects
            ->add_fields('overview_projects', 'Übersicht Projekte', [
                Field::make('text', 'title', 'Titel')
                    ->set_help_text('Wird für Screenreader / SEO verwendet und ist visuell ausgeblendet (gem. Design).'),
                Field::make('rich_text', 'description', 'Beschreibung')
                    ->set_help_text('Optional, ebenfalls visuell ausgeblendet.'),
                Field::make('association', 'selection', 'Auswahl Projekte')
                    ->set_types([['type' => 'post', 'post_type' => 'project']])
                    ->set_help_text('Projekte auswählen und per Drag & Drop sortieren. Leer lassen, um alle Projekte der gewählten Kategorie anzuzeigen.'),
                Field::make('select', 'category', 'Kategorie (falls keine Auswahl)')
                    // -> Carbon Fields braucht hier eine echte Funktion (Closure), keinen Funktionsnamen als Text
                    ->set_options(fn () => function_exists('get_project_category_options')
                        ? get_project_category_options()
                        : ['' => '— Keine —']),
                Field::make('select', 'theme', 'Farbschema')
                    ->set_options([
                        'light' => 'Hell (weiss / schwarz)',
                        'olive' => 'Olive (olive / weiss)',
                        'beige' => 'Beige (hellgrau / schwarz)',
                    ])
                    ->set_default_value('light'),
                $anchor_field(),
            ])
            // -> contact
            ->add_fields('contact', 'Kontakt', [
                Field::make('text', 'title', 'Titel')
                    ->set_default_value('Let’s talk !')
                    ->set_help_text('Die Buttons (LinkedIn, E-Mail, …) werden unter «Globales» gepflegt.'),
                $anchor_field(),
            ])
            // -> information
            ->add_fields('information', 'Informationen', [
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('rich_text', 'text', 'Text'),
                $anchor_field(),
            ])
            // todo: add new modules here
            // -> SETTINGS
            ->set_layout('tabbed-vertical')
    ]);

// -> Funktion, um die Module und die entsprechenden Templates auf der Seite zu laden
function render_modules (): void {
    $modules = carbon_get_the_post_meta('modules');
    if (empty($modules)) {
        return;
    }
    foreach ($modules as $module) {
        // -> Modifiziere die Daten des Moduls, falls nötig, basierend auf dem Modultyp
        switch ($module['_type']) {
            case 'example':
                $module['image'] = get_media_by_id($module['image'], 'full');
                break;
            case 'hero_front':
                $module['image'] = get_media_by_id($module['image'], 'full');
                break;
            case 'gallery_about':
                $module['images'] = array_map(
                    fn ($id) => get_media_by_id($id, 'l'),
                    $module['images'] ?: []
                );
                break;
            case 'overview_projects':
                $ids = array_map(fn ($item) => (int) $item['id'], $module['selection'] ?: []);
                $module['projects'] = $ids
                    ? get_projects_by_ids($ids)
                    : get_projects_by_category((int) ($module['category'] ?? 0));
                break;
            case 'contact':
                $module['contact'] = get_global_contact() ?: [];
                break;
                // todo: add data modifications for modules here as a new case
        }
        // -> Laden des Templates basierend auf dem Modultyp und weitergeben der Daten
        get_template_part(
            'frontend/views/modules/' . $module['_type'],
            null,
            [
                'data' => $module
            ]
        );
    }
}
