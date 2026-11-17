<?php
$menu = get_menu_by_slug('legals'); 
// -> Die Kontakt-Buttons wurden ins Modul "Kontakt" verschoben (frontend/views/modules/contact.php)
?>

<footer data-layout="footer">
    <nav class="nav-container" aria-label="Rechtliches">
        <ul class="nav">
            <?php foreach ($menu as $item) : ?>
                <li class="nav-item">
                    <a target="<?= esc_attr($item['target']) ?>" href="<?= esc_url($item['url']) ?>"><?= esc_html($item['title']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul> 
    </nav>
</footer>
