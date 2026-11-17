<?php
/**
 * Projekt-Karte für die Projektübersicht.
 * Ruhezustand: Schwarzweissbild mit zentriertem Titel.
 * Hover / Fokus / aktiv (Touch): farbiges Bild, Karte wird grösser, Infos erscheinen darüber.
 */
$project = $args['data'];
$has_image = !empty($project['image']['src']);
$sizes = '(max-width: 768px) 70vw, 24vw';
?>

<li class="project-card<?= $has_image ? '' : ' is-empty' ?>" data-scroll-item style="--image-position: <?= esc_attr($project['image_position']) ?>">
    <article class="project-card-inner">
        <div class="info">
            <h3 class="info-title"><?= esc_html($project['title']) ?></h3>
            <?php if (!empty($project['categories'])) : ?>
                <p class="info-categories"><?= esc_html(implode(' · ', $project['categories'])) ?></p>
            <?php endif; ?>
            <?php if (!empty($project['description'])) : ?>
                <div class="info-text"><?= format_rich_text($project['description']) ?></div>
            <?php endif; ?>
            <div class="info-links">
                <a class="info-link" href="<?= esc_url($project['url']) ?>">Mehr erfahren</a>
                <?php if (!empty($project['link'])) : ?>
                    <a class="info-link" href="<?= esc_url($project['link']) ?>" target="_blank" rel="noopener"><?= esc_html($project['link_label']) ?> ↗</a>
                <?php endif; ?>
            </div>
        </div>

        <a class="media" href="<?= esc_url($project['url']) ?>" aria-label="<?= esc_attr($project['title']) ?>">
            <?php if ($has_image) : ?>
                <?= get_media_image_tag($project['image'], 'image image-bw' . ($project['needs_filter'] ? ' needs-filter' : ''), $sizes) ?>
                <?= get_media_image_tag($project['image_hover'] ?: $project['image'], 'image image-color', $sizes) ?>
            <?php endif; ?>
            <span class="card-title" aria-hidden="true"><?= esc_html($project['title']) ?></span>
        </a>
    </article>
</li>
