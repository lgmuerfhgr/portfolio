/**
 * Scroll-Galerien
 *
 * Markup:
 *   <div data-scroll-gallery="y|x">      -> sichtbarer Bereich
 *     <ul data-scroll-track>             -> wird verschoben
 *       <li data-scroll-item>…</li>      -> einzelne Bilder / Karten
 *
 * - Scroll-Modus (Desktop): Während die Sektion durch den Viewport scrollt, wird die
 *   Spur vertikal (About) bzw. horizontal (Projekte) verschoben – sanft gedämpft.
 * - Fixieren (optional, About): Liegt die Galerie in einem [data-scroll-pin]-Element,
 *   bleibt dieses stehen (sticky), bis alle Bilder durchgelaufen sind. Titel und Text
 *   bewegen sich dabei nicht. Ist der Inhalt höher als der Bildschirm, wird erst fixiert,
 *   wenn sein unteres Ende erreicht ist (so bleibt kein Text unerreichbar).
 * - Nativ-Modus (Mobile, reduzierte Bewegung): Die Galerie ist ein normaler,
 *   wischbarer Scroll-Container (Klasse .is-native).
 * - Geräte ohne Hover (Touch): Das Element in der Bildschirmmitte erhält .is-active
 *   und wird dadurch farbig dargestellt bzw. zeigt seine Infos.
 */

const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
const desktop = matchMedia('(min-width: 769px)');
const canHover = matchMedia('(hover: hover) and (pointer: fine)');

const EASING = 0.12; // Dämpfung der Bewegung (0–1, kleiner = weicher)


const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

const getHeaderHeight = () =>
    parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--header-h')) || 0;

export function initScrollGalleries() {
    const galleries = [...document.querySelectorAll('[data-scroll-gallery]')]
        .map((element) => ({
            element,
            axis: element.dataset.scrollGallery === 'x' ? 'x' : 'y',
            track: element.querySelector('[data-scroll-track]'),
            items: [...element.querySelectorAll('[data-scroll-item]')],
            section: element.closest('[data-module]') || element,
            pin: element.closest('[data-scroll-pin]'),
            pinned: false,
            pinTop: 0,
            current: 0,
            target: 0,
            linked: false,
        }))
        .filter((gallery) => gallery.track && gallery.items.length);

    if (!galleries.length) return;

    let frame = null;

    const isGridOnly = (gallery) => gallery.section.classList.contains('is-gallery-only');

    const setMode = () => {
        galleries.forEach((gallery) => {
            gallery.linked = !reducedMotion.matches && desktop.matches && !isGridOnly(gallery);
            gallery.element.classList.toggle('is-native', !gallery.linked && !isGridOnly(gallery));
            if (!gallery.linked) {
                gallery.current = gallery.target = 0;
                gallery.track.style.transform = '';
            }
            updatePin(gallery);
        });
    };

    // -> Sektion fixieren und ihre Höhe so setzen, dass alle Bilder einmal durchlaufen
    const updatePin = (gallery) => {
        if (!gallery.pin) return;
        gallery.pinned = gallery.linked;
        gallery.section.classList.toggle('is-pinned', gallery.pinned);
        if (!gallery.pinned) {
            gallery.section.style.height = '';
            gallery.section.style.removeProperty('--pin-top');
            return;
        }
        // Normalfall: direkt unter dem Header fixieren. Ist der Inhalt höher als der
        // verfügbare Platz, so fixieren, dass sein unteres Ende am Bildschirmrand liegt.
        const pinHeight = gallery.pin.offsetHeight;
        gallery.pinTop = Math.min(getHeaderHeight(), window.innerHeight - pinHeight);
        gallery.section.style.setProperty('--pin-top', `${gallery.pinTop}px`);
        const overflow = Math.max(0, gallery.track.offsetHeight - gallery.element.clientHeight);
        gallery.section.style.height = `${pinHeight + overflow}px`;
    };

    // -> Horizontale Galerien: Die Bewegung läuft, solange die Kartenreihe vollständig sichtbar ist
    //    (vom unteren Bildschirmrand bis direkt unter den Header). Rückgabe: Mittelpunkt Start / Ende.
    const getRowRange = (gallery) => {
        const viewport = window.innerHeight;
        const header = getHeaderHeight();
        const half = gallery.track.offsetHeight / 2;
        const start = viewport - half;
        const end = header + half;
        // Sehr niedrige Viewports: Mindestbereich, damit die Bewegung nicht springt
        return start - end > viewport * 0.3 ? [start, end] : [viewport * 0.75, viewport * 0.35];
    };

    // -> Scroll-Fortschritt (0–1): vertikal über die ganze Sektion, horizontal über die Kartenreihe
    const getProgress = (gallery) => {
        const viewport = window.innerHeight;
        if (gallery.axis === 'x') {
            const rect = gallery.track.getBoundingClientRect();
            const center = rect.top + rect.height / 2;
            const [start, end] = getRowRange(gallery);
            return clamp((start - center) / (start - end), 0, 1);
        }
        const rect = gallery.section.getBoundingClientRect();
        if (gallery.pinned) {
            // Fortschritt, solange die Sektion fixiert ist
            const distance = gallery.section.offsetHeight - gallery.pin.offsetHeight;
            return distance > 0 ? clamp((gallery.pinTop - rect.top) / distance, 0, 1) : 0;
        }
        return clamp((viewport - rect.top) / (viewport + rect.height), 0, 1);
    };

    // -> Zielposition anhand des Scroll-Fortschritts berechnen
    const measure = (gallery) => {
        const progress = getProgress(gallery);
        const overflow = gallery.axis === 'x'
            ? gallery.track.scrollWidth - gallery.element.clientWidth
            : gallery.track.offsetHeight - gallery.element.clientHeight;
        gallery.target = -Math.max(0, overflow) * progress;
    };

    // -> Auf Touch-Geräten: Element, das der Bildschirmmitte am nächsten ist, aktivieren
    const updateActive = (gallery) => {
        if (canHover.matches) {
            gallery.items.forEach((item) => item.classList.remove('is-active'));
            return;
        }
        const rowLayout = getComputedStyle(gallery.track).flexDirection === 'row';
        const centerX = window.innerWidth / 2;
        const centerY = window.innerHeight / 2;
        let closest = null;
        let closestDistance = Infinity;
        gallery.items.forEach((item) => {
            const rect = item.getBoundingClientRect();
            const visible = rect.bottom > 0 && rect.top < window.innerHeight
                && rect.right > 0 && rect.left < window.innerWidth;
            if (!visible) return;
            const distance = rowLayout
                ? Math.abs(rect.left + rect.width / 2 - centerX)
                : Math.abs(rect.top + rect.height / 2 - centerY);
            if (distance < closestDistance) {
                closest = item;
                closestDistance = distance;
            }
        });
        // -> Bei horizontalen Galerien nur aktivieren, wenn die Sektion gut sichtbar ist
        const sectionRect = gallery.section.getBoundingClientRect();
        const sectionVisible = sectionRect.top < window.innerHeight * 0.6 && sectionRect.bottom > window.innerHeight * 0.4;
        gallery.items.forEach((item) => item.classList.toggle('is-active', item === closest && sectionVisible));
    };

    const render = () => {
        frame = null;
        let moving = false;
        galleries.forEach((gallery) => {
            if (gallery.linked) {
                measure(gallery);
                const delta = gallery.target - gallery.current;
                gallery.current = Math.abs(delta) < 0.1 ? gallery.target : gallery.current + delta * EASING;
                if (gallery.current !== gallery.target) moving = true;
                const value = gallery.current.toFixed(2);
                gallery.track.style.transform = gallery.axis === 'x'
                    ? `translate3d(${value}px, 0, 0)`
                    : `translate3d(0, ${value}px, 0)`;
            }
            updateActive(gallery);
        });
        if (moving) requestUpdate();
    };

    const requestUpdate = () => {
        if (frame === null) frame = requestAnimationFrame(render);
    };

    const reset = () => {
        setMode();
        galleries.forEach((gallery) => {
            if (!gallery.linked) return;
            measure(gallery);
            gallery.current = gallery.target; // ohne Nachlauf an die richtige Position springen
        });
        requestUpdate();
    };

    // -> Tastatur: fokussierte Karte in den sichtbaren Bereich holen
    galleries.forEach((gallery) => {
        gallery.element.addEventListener('focusin', (event) => {
            if (!gallery.linked || gallery.axis !== 'x') return;
            const item = event.target.closest('[data-scroll-item]');
            if (!item) return;
            const overflow = gallery.track.scrollWidth - gallery.element.clientWidth;
            if (overflow <= 0) return;
            const itemCenter = item.offsetLeft + item.offsetWidth / 2 - gallery.element.clientWidth / 2;
            const progress = clamp(itemCenter / overflow, 0, 1);
            // -> Benötigte Position der Kartenreihe für diesen Fortschritt (Umkehrung von getProgress)
            const trackRect = gallery.track.getBoundingClientRect();
            const currentCenter = trackRect.top + trackRect.height / 2;
            const [start, end] = getRowRange(gallery);
            const targetCenter = start - progress * (start - end);
            window.scrollTo({ top: window.scrollY + currentCenter - targetCenter, behavior: 'instant' });
        });
        gallery.element.addEventListener('scroll', requestUpdate, { passive: true });
        gallery.items.forEach((item) => item.querySelectorAll('img').forEach((image) => {
            if (!image.complete) image.addEventListener('load', reset, { once: true });
        }));
    });

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', reset);
    [reducedMotion, desktop, canHover].forEach((query) => query.addEventListener('change', reset));
    const resizeObserver = new ResizeObserver(reset);
    galleries.forEach((gallery) => resizeObserver.observe(gallery.track));

    reset();
}
