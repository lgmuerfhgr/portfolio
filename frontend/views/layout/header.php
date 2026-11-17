<?php 
$menu = get_menu_by_slug('main'); 
?>

<header data-layout="header">
    <a class="skip-link" href="#main">Zum Inhalt springen</a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav">
        <span class="nav-toggle-line" aria-hidden="true"></span>
        <span class="nav-toggle-line" aria-hidden="true"></span>
        <span class="visually-hidden">Menü öffnen</span>
    </button>
    <nav class="nav-container" id="main-nav" aria-label="Hauptnavigation">
        <ul class="nav">
            <?php foreach ($menu as $item) : ?>
                <li class="nav-item">
                    <a target="<?= esc_attr($item['target']) ?>" href="<?= esc_url($item['url']) ?>"><?= esc_html($item['title']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul> 
    </nav>
</header>
