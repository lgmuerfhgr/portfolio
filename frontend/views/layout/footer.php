<?php
$menu = get_menu_by_slug('legals'); 
js_log($menu);
$contact = get_global_contact();
?>

<footer data-layout="footer">
    <div class="contact">
        <?php foreach ($contact as $item) : ?>
            <?php if ($item['_type'] === 'email') : ?>
                <a href="mailto:<?= $item['email'] ?>"><?= $item['title'] ?></a>
            <?php else : ?>
                <a target="_blank" rel="noopener" href="<?= esc_url($item['link']) ?>"><?= esc_html($item['title']) ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <nav class="nav-container">
        <ul class="nav">
            <?php foreach ($menu as $item) : ?>
                <li class="nav-item">
                    <a target="<?= $item['target'] ?>" href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul> 
    </nav>
</footer>
