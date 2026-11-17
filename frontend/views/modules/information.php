<?php
$data = $args['data'];
// -> Auf Unterseiten (z.B. Datenschutz) ist der Titel die Hauptüberschrift der Seite
$heading = is_front_page() ? 'h2' : 'h1';
?>

<section data-module="information"<?= get_module_id_attr($data) ?>>
    <<?= $heading ?> class="title"><?= esc_html($data['title']) ?></<?= $heading ?>>
    <?php if (!empty($data['text'])) : ?>
        <div class="text"><?= format_rich_text($data['text']) ?></div>
    <?php endif; ?>
</section>
