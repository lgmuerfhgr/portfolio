<?php
$project = get_project_by_id(get_the_ID(), 'l');
$image = $project['image_hover'] ?: $project['image'];
?>

<main data-page="project" id="main">
    <article class="project">
        <a class="back-link" href="<?= esc_url(home_url('/')) ?>">← Zurück</a>

        <header class="project-header" data-reveal>
            <?php if ($project['categories']) : ?>
                <p class="project-categories"><?= esc_html(implode(' · ', $project['categories'])) ?></p>
            <?php endif; ?>
            <h1 class="project-title"><?= esc_html($project['title']) ?></h1>
        </header>

        <div class="project-body">
            <?php if (!empty($image['src'])) : ?>
                <figure class="project-media" style="--image-position: <?= esc_attr($project['image_position']) ?>">
                    <?= get_media_image_tag($project['image'], 'image image-bw' . ($project['needs_filter'] ? ' needs-filter' : ''), '(max-width: 768px) 100vw, 40vw', false) ?>
                    <?= get_media_image_tag($image, 'image image-color', '(max-width: 768px) 100vw, 40vw', false) ?>
                </figure>
            <?php endif; ?>

            <div class="project-content" data-reveal>
                <?php if ($project['description']) : ?>
                    <div class="text"><?= format_rich_text($project['description']) ?></div>
                <?php endif; ?>
                <?php if ($project['skills']) : ?>
                    <h2 class="project-subtitle">Kompetenzen</h2>
                    <div class="text"><?= format_rich_text($project['skills']) ?></div>
                <?php endif; ?>
                <?php if ($project['link']) : ?>
                    <a class="button" href="<?= esc_url($project['link']) ?>" target="_blank" rel="noopener"><?= esc_html($project['link_label']) ?> ↗</a>
                <?php endif; ?>
            </div>
        </div>
    </article>
</main>
