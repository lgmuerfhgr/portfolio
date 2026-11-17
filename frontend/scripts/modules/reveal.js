/**
 * Elemente mit [data-reveal] werden beim Hineinscrollen sanft eingeblendet.
 * Das Styling liegt in main.css (nur aktiv mit JavaScript und ohne reduzierte Bewegung).
 */
export function initReveal() {
    const elements = document.querySelectorAll('[data-reveal]');
    if (!elements.length) return;

    if (!('IntersectionObserver' in window)) return;

    // -> Erst jetzt ausblenden: Läuft dieses Skript nicht, bleiben die Texte sichtbar
    document.documentElement.classList.add('has-reveal');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });

    elements.forEach((element) => observer.observe(element));
}
