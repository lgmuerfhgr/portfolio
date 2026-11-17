/**
 * Header: echte Höhe als CSS-Variable (--header-h) für Sticky-Abstände
 * und Burger-Menü auf kleinen Bildschirmen.
 */
export function initHeader() {
    const header = document.querySelector('[data-layout="header"]');
    if (!header) return;

    const root = document.documentElement;
    const setHeight = () => root.style.setProperty('--header-h', `${header.offsetHeight}px`);
    setHeight();
    new ResizeObserver(setHeight).observe(header);

    const toggle = header.querySelector('.nav-toggle');
    const nav = header.querySelector('.nav-container');
    if (!toggle || !nav) return;

    const label = toggle.querySelector('.visually-hidden');
    const setOpen = (open) => {
        header.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        if (label) label.textContent = open ? 'Menü schliessen' : 'Menü öffnen';
        document.body.style.overflow = open ? 'hidden' : '';
    };

    toggle.addEventListener('click', () => setOpen(!header.classList.contains('is-open')));
    nav.addEventListener('click', (event) => {
        if (event.target.closest('a')) setOpen(false);
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && header.classList.contains('is-open')) {
            setOpen(false);
            toggle.focus();
        }
    });
    matchMedia('(min-width: 769px)').addEventListener('change', (event) => {
        if (event.matches) setOpen(false);
    });
}
