<?php
/**
 * Projektseite: gleiche Galerie wie bei «About» (data-module="gallery-about").
 * Links laufen Titelbild und weitere Bilder beim Scrollen vertikal durch
 * (schwarzweiss, beim Hover farbig), rechts stehen Titel und Text still.
 */
$project = get_project_by_id(get_the_ID(), 'l', true);
$images = array_values(array_filter(
    array_merge([$project['image']], $project['gallery']),
    fn ($image) => !empty($image['src'])
));
?>

<main data-page="project" id="main">
    <section data-module="gallery-about">
        <div class="about-inner" data-scroll-pin>
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

            <div class="content" data-scroll-pin-content>
                <a class="back-link" href="<?= esc_url(home_url('/')) ?>">← Zurück</a>
                <?php if ($project['categories']) : ?>
                    <p class="project-categories"><?= esc_html(implode(' · ', $project['categories'])) ?></p>
                <?php endif; ?>
                <h1 class="title"><?= esc_html($project['title']) ?></h1>
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
                <?php if ($project['videos']) : ?>
                    <div class="project-videos" data-carousel>
                        <ul class="video-track" data-carousel-track>
                            <?php foreach ($project['videos'] as $video) : ?>
                                <li class="video-item video-item--<?= esc_attr($video['type']) ?><?= ($video['ratio'] ?? '') === '16 / 9' ? ' is-landscape' : '' ?>" style="--ratio: <?= esc_attr($video['ratio'] ?? '9 / 16') ?>">
                                    <?php if ($video['type'] === 'file') : ?>
                                        <video class="video-frame" src="<?= esc_url($video['src']) ?>" controls playsinline preload="metadata"></video>
                                    <?php elseif ($video['type'] === 'vimeo') : ?>
                                        <div class="video-frame">
                                            <iframe
                                                src="<?= esc_url($video['src']) ?>"
                                                title="<?= esc_attr($video['caption'] ?: 'Video zum Projekt ' . $project['title']) ?>"
                                                allow="autoplay; fullscreen; picture-in-picture; clipboard-write"
                                                allowfullscreen
                                                loading="lazy"
                                            ></iframe>
                                        </div>
                                    <?php else : ?>
                                        <blockquote class="instagram-media" data-instgrm-permalink="<?= esc_url($video['permalink']) ?>?utm_source=ig_embed" data-instgrm-version="14">
                                            <a href="<?= esc_url($video['permalink']) ?>" target="_blank" rel="noopener">Beitrag auf Instagram ansehen ↗</a>
                                        </blockquote>
                                    <?php endif; ?>
                                    <?php if (!empty($video['caption'])) : ?>
                                        <p class="video-caption"><?= esc_html($video['caption']) ?></p>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="carousel-nav">
                            <button class="carousel-button" type="button" data-carousel-prev aria-label="Vorheriges Video">←</button>
                            <button class="carousel-button" type="button" data-carousel-next aria-label="Nächstes Video">→</button>
                        </div>
                    </div>
                    <?php if (in_array('instagram', array_column($project['videos'], 'type'), true)) : ?>
                        <script async src="https://www.instagram.com/embed.js"></script>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($project['pdfs']) : ?>
                    <div class="project-pdfs">
                        <?php foreach ($project['pdfs'] as $pdf) : ?>
                            <figure class="pdf-embed is-<?= esc_attr($pdf['format']) ?>">
                                <figcaption class="pdf-title">
                                    <?= esc_html($pdf['title']) ?>
                                    <?php if ($pdf['size']) : ?><span class="pdf-size">PDF, <?= esc_html($pdf['size']) ?></span><?php endif; ?>
                                </figcaption>
                                <?php // Desktop: PDF direkt im Browser-Viewer ?>
                                <iframe
                                    class="pdf-frame"
                                    src="<?= esc_url($pdf['src']) ?>#view=FitH&amp;toolbar=1"
                                    title="<?= esc_attr($pdf['title']) ?> (PDF)"
                                    loading="lazy"
                                ></iframe>
                                <?php // Mobile: Karte, die das PDF öffnet ?>
                                <a class="pdf-card" href="<?= esc_url($pdf['src']) ?>" target="_blank" rel="noopener">
                                    <span class="pdf-card-icon" aria-hidden="true">PDF</span>
                                    <span class="pdf-card-text"><?= esc_html($pdf['title']) ?></span>
                                </a>
                                <div class="pdf-links">
                                    <a class="info-link" href="<?= esc_url($pdf['src']) ?>" target="_blank" rel="noopener">PDF öffnen ↗</a>
                                    <a class="info-link" href="<?= esc_url($pdf['src']) ?>" download>Herunterladen</a>
                                </div>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
