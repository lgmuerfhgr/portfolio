/**
 * Lädt die einzelnen Bausteine unabhängig voneinander: Fehlt einer oder hat er
 * einen Fehler, funktionieren die anderen trotzdem. Fehler stehen in der Browser-Konsole.
 * Die Versionsnummer von main.js (?v=…) wird an die Bausteine weitergegeben,
 * damit der Browser nach einem Update keine alten Dateien aus dem Cache verwendet.
 */
const version = new URL(import.meta.url).search;

const modules = [
    ['./modules/header.js', 'initHeader'],
    ['./modules/scroll-gallery.js', 'initScrollGalleries'],
    ['./modules/reveal.js', 'initReveal'],
];

modules.forEach(async ([path, init]) => {
    try {
        const module = await import(path + version);
        module[init]();
    } catch (error) {
        console.error(`[Theme] ${path} konnte nicht geladen werden:`, error);
    }
});
