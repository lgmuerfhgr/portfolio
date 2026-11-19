/**
 * Zentrierte Galerie (z.B. Videos auf der Projektseite).
 *
 * Markup:
 *   <div data-carousel>
 *     <ul data-carousel-track> <li>…</li> </ul>
 *     <button data-carousel-prev> <button data-carousel-next>
 *
 * - Das Element in der Mitte erhält .is-active (ganz sichtbar), die Nachbarn sind per CSS verblasst.
 * - Klick auf ein verblasstes Element oder die Pfeile holt es in die Mitte.
 * - Wird ein Video verlassen, wird es angehalten (eigene Videos und Vimeo).
 * - Wischen / Trackpad funktionieren nativ.
 */
export function initCarousels() {
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const track = carousel.querySelector('[data-carousel-track]');
        const prev = carousel.querySelector('[data-carousel-prev]');
        const next = carousel.querySelector('[data-carousel-next]');
        if (!track) return;
        const items = () => [...track.children];
        let active = null;
        let frame = null;

        // -> Element in die Mitte scrollen
        const center = (item, behavior = 'smooth') => {
            if (!item) return;
            track.scrollTo({ left: item.offsetLeft - (track.clientWidth - item.offsetWidth) / 2, behavior });
        };

        // -> Video im Element anhalten (eigene Datei oder Vimeo)
        const pause = (item) => {
            item.querySelectorAll('video').forEach((video) => video.pause());
            item.querySelectorAll('iframe[src*="vimeo.com"]').forEach((iframe) => {
                iframe.contentWindow?.postMessage('{"method":"pause"}', '*');
            });
        };

        // -> Element, das der Mitte am nächsten ist, aktivieren
        const update = () => {
            frame = null;
            const list = items();
            const middle = track.scrollLeft + track.clientWidth / 2;
            let closest = list[0];
            list.forEach((item) => {
                const distance = Math.abs(item.offsetLeft + item.offsetWidth / 2 - middle);
                const best = Math.abs(closest.offsetLeft + closest.offsetWidth / 2 - middle);
                if (distance < best) closest = item;
            });
            if (closest !== active) {
                if (active) pause(active);
                active = closest;
                list.forEach((item) => item.classList.toggle('is-active', item === active));
            }
            const index = list.indexOf(active);
            carousel.classList.toggle('is-scrollable', list.length > 1);
            if (prev) prev.disabled = index <= 0;
            if (next) next.disabled = index >= list.length - 1;
        };
        const requestUpdate = () => {
            if (frame === null) frame = requestAnimationFrame(update);
        };

        prev?.addEventListener('click', () => center(items()[items().indexOf(active) - 1]));
        next?.addEventListener('click', () => center(items()[items().indexOf(active) + 1]));
        track.addEventListener('click', (event) => {
            const item = event.target.closest('li');
            if (item && item !== active) center(item);
        });
        track.addEventListener('scroll', requestUpdate, { passive: true });
        new ResizeObserver(requestUpdate).observe(track);
        // Instagram ersetzt seine Platzhalter nachträglich -> neu ausrichten
        new MutationObserver(requestUpdate).observe(track, { childList: true, subtree: true });

        carousel.classList.add('is-ready');
        center(items()[0], 'instant');
        update();
    });
}
