<?php
/**
 * Übersicht Projekte: Galerie mit Projekt-Karten, die sich beim vertikalen
 * Scrollen horizontal bewegt (Desktop) bzw. per Wischen gescrollt wird (Mobile).
 */
$data = $args['data'];
$projects = $data['projects'] ?? [];
$theme = $data['theme'] ?? 'light';
if (empty($projects)) {
    return;
}
?>

<section data-module="overview-projects" data-theme="<?= esc_attr($theme) ?>"<?= get_module_id_attr($data) ?>>
    <?php if (!empty($data['title'])) : ?>
        <h2 class="visually-hidden"><?= esc_html($data['title']) ?></h2>
    <?php endif; ?>
    <?php if (!empty($data['description'])) : ?>
        <div class="visually-hidden"><?= format_rich_text($data['description']) ?></div>
    <?php endif; ?>

    <div class="projects" data-scroll-gallery="x">
        <ul class="projects-track" data-scroll-track>
            <?php foreach ($projects as $project) : ?>
                <?php get_template_part('frontend/views/teaser/project', null, ['data' => $project]); ?>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
