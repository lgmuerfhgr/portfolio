<?php
$data = $args['data'];
$contact = $data['contact'] ?? [];
?>

<section data-module="contact"<?= get_module_id_attr($data) ?>>
    <?php if (!empty($data['title'])) : ?>
        <h2 class="title" data-reveal><?= esc_html($data['title']) ?></h2>
    <?php endif; ?>
    <?php if ($contact) : ?>
        <div class="buttons" data-reveal>
            <?php foreach ($contact as $item) : ?>
                <?php if ($item['_type'] === 'email') : ?>
                    <a class="button" href="<?= esc_url('mailto:' . $item['email']) ?>"><?= esc_html($item['title']) ?></a>
                <?php else : ?>
                    <a class="button" target="_blank" rel="noopener" href="<?= esc_url($item['link']) ?>"><?= esc_html($item['title']) ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
