# Portfolio `Inhalte pflegen`
> In dieser Anleitung ist beschrieben, wie die Inhalte des Portfolios (gem. CMS-Konzept und Figma-Vorlage) im Wordpress-Backend erfasst werden.

## ⚙️ Einmalige Einrichtung
1. **Permalinks speichern**: `Einstellungen > Permalinks` öffnen und einmal speichern (nötig für die Projekt-Detailseiten und die Taxonomie).
2. **Startseite festlegen**: Seite «Startseite» erstellen und unter `Einstellungen > Lesen` als statische Startseite wählen.
3. **Globales**: Unter `Globales > Kontakt` die Buttons erfassen (z.B. «Linkedin» als Social, «E-Mail» als E-Mail). Sie erscheinen im Modul «Kontakt».
4. **Menus** (`Design > Menus`):
   - **Hauptnavigation** (`main`): Individuelle Links auf die Anker der Startseite, z.B. `/#about`, `/#brand-experience`, `/#multimedia`, `/#contact`.
   - **Rechtliches** (`legals`): Seiten «Datenschutz» und «Impressum».

## 🗂️ Projekte
Unter `Projekte` wird pro Projekt ein Eintrag erstellt.

| Feld | Hinweis |
| --- | --- |
| Titel | Erscheint zentriert auf der Karte und im Hover über der Karte |
| Bild (schwarzweiss) | Ruhezustand, Hochformat ca. **3:4** (z.B. 1200 × 1620px) |
| Bild (farbig) | Erscheint beim Hover, gleiches Format. Darf auch eine alternative Bildversion sein. Fehlt es, wird das erste Bild per Filter eingefärbt dargestellt. |
| Beschreibung | Kurzer Text, erscheint beim Hover über der Karte und auf der Detailseite |
| Kompetenzen | Nur auf der Detailseite |
| Link / Link-Beschriftung | Optional, externer Link (Button «Zum Projekt ↗») |
| Bildausschnitt | Welcher Teil des Bildes in der Hochformat-Karte sichtbar bleibt |
| Kategorien | z.B. «Brand Experience», «Multimedia Projects» |

## 🧱 Module der Startseite (Reihenfolge gem. Figma)
| Modul | Inhalt | Anker (Vorschlag) |
| --- | --- | --- |
| Hero Startseite | Signatur als SVG/PNG | – |
| Galerie About | Bilder (farbig hochladen, Hochformat 3:4), Titel «About me !», Text | `about` |
| Übersicht Projekte | Titel «Brand Experience», Projekte auswählen **oder** Kategorie wählen, Farbschema «Hell» | `brand-experience` |
| Übersicht Projekte | Titel «Multimedia Projects», Farbschema «Olive» | `multimedia` |
| Kontakt | Titel «Let’s talk !» | `contact` |

Für Datenschutz und Impressum wird je eine Seite mit dem Modul **Informationen** (Titel + Text) angelegt.

## ✨ Verhalten im Frontend
- **Galerie About**: Bilder bewegen sich beim Scrollen vertikal, schwarzweiss → farbig beim Hover.
- **Übersicht Projekte**: Die Kartenreihe bewegt sich beim vertikalen Scrollen horizontal. Beim Hover wird die Karte grösser, das farbige Bild erscheint und Titel, Beschreibung und Links werden darüber eingeblendet. Empfehlung: ca. 5–10 Projekte pro Galerie.
- **Touch-Geräte / Mobile**: Galerien sind wischbar, das Element in der Bildschirmmitte wird automatisch farbig bzw. zeigt seine Infos.
- **Reduzierte Bewegung** (Systemeinstellung): keine Scroll-Animationen, Galerien sind normal scrollbar.

## 🛠️ Technik (für Erweiterungen)
- Module: [backend/carbonfields/_modules.php](/backend/carbonfields/_modules.php) + [frontend/views/modules](/frontend/views/modules)
- Projekt-Karte: [frontend/views/teaser/project.php](/frontend/views/teaser/project.php)
- Projekt-Detailseite: [single-project.php](/single-project.php) + [frontend/views/pages/project.php](/frontend/views/pages/project.php)
- Getter: [backend/getter/_project.php](/backend/getter/_project.php)
- Scroll-Galerien: [frontend/scripts/modules/scroll-gallery.js](/frontend/scripts/modules/scroll-gallery.js) (Markup: `data-scroll-gallery="x|y"`, `data-scroll-track`, `data-scroll-item`)
