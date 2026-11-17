<?php
$data = $args['data'];
$animate = !empty($data['animate']);
?>

<section data-module="hero-front"<?= get_module_id_attr($data) ?>>
    <h1 class="visually-hidden"><?= esc_html(get_bloginfo('name')) ?></h1>
    <?php if (!empty($data['image']['src'])) : ?>
        <?= get_media_image_tag($data['image'], 'image' . ($animate ? ' is-animated' : ''), '100vw', false) ?>
    <?php endif; ?>
</section>
