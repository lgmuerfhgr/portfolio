<?php
/**
 * Galerie About: vertikale Scroll-Galerie (links) + Text (rechts).
 * Die Bilder sind schwarzweiss und werden beim Hover (bzw. auf Touch-Geräten
 * beim Erreichen der Bildschirmmitte) farbig. Das Scrollverhalten steuert
 * frontend/scripts/modules/scroll-gallery.js.
 */
$data = $args['data'];
$images = array_filter($data['images'] ?? [], fn ($image) => !empty($image['src']));
$has_content = !empty($data['title']) || !empty($data['text']);
?>

<section data-module="gallery-about" class="<?= $has_content ? '' : 'is-gallery-only' ?>"<?= get_module_id_attr($data) ?>>
    <?php if ($images) : ?>
        <div class="gallery" data-scroll-gallery="y">
            <ul class="gallery-track" data-scroll-track>
                <?php foreach ($images as $image) : ?>
                    <li class="gallery-item" data-scroll-item>
                        <?= get_media_image_tag($image, 'image', '(max-width: 768px) 70vw, 21vw') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($has_content) : ?>
        <div class="content">
            <?php if (!empty($data['title'])) : ?>
                <h2 class="title" data-reveal><?= esc_html($data['title']) ?></h2>
            <?php endif; ?>
            <?php if (!empty($data['text'])) : ?>
                <div class="text" data-reveal><?= format_rich_text($data['text']) ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>
