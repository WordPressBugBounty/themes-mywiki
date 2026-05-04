== MyWiki ==
Contributors: Fasterthemes
Tags: blog, custom-background, custom-colors, custom-header, custom-logo, custom-menu, featured-images, footer-widgets, full-width-template, sticky-post, theme-options, threaded-comments, translation-ready, right-sidebar, two-columns, editor-style
Requires at least: 5.6
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 5.0.1
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

MyWiki is a fast, intelligent WordPress theme purpose-built for documentation, knowledge bases, and wikis. It ships with an AJAX search modal, automatic table of contents, breadcrumbs, reading-time estimation, and a typography system tuned for long-form reading.

== Description ==

MyWiki is the documentation and wiki theme that gets out of the way. The new v5 release is a complete rebuild — no jQuery, no Bootstrap, no icon font — focused on speed, accessibility, and clean reading.

Features:

* Modal AJAX search opened with ⌘K / Ctrl+K, with grouped suggestions and full keyboard navigation.
* "#tag" prefix searches by tag in the search modal.
* Automatic table of contents on every article, with scroll-spy active state.
* Breadcrumbs with JSON-LD structured data for SEO.
* Reading-time estimation in the article header.
* "Was this helpful?" feedback widget on every article.
* Sticky article navigation: previous / next.
* Featured topic grid on the home template, driven by your top-level categories.
* Inline SVG icons. No icon font.
* Vanilla JavaScript. No jQuery.
* Optimized for mobile from the first pixel.
* Block editor styles match the front end exactly.
* Translation ready.
* WCAG-friendly contrast and focus states.

== Installation ==

1. From your WordPress dashboard, go to Appearance → Themes → Add New → Upload Theme.
2. Upload mywiki.zip and activate.
3. Create a new page and assign the "Wiki Home" template.
4. Settings → Reading → A static page → Front page → choose your wiki page.
5. Customize via Appearance → Customize → MyWiki Theme Options.

== Frequently Asked Questions ==

= How do I show a featured topic grid on the homepage? =
Create a Page, assign the "Wiki Home" template, and set it as the front page. The grid is built from your top-level categories. You can pin specific categories under Customizer → MyWiki Theme Options → Category Grid.

= How does the search modal work? =
Press ⌘K (Mac) or Ctrl+K (Windows / Linux), or hit "/" anywhere outside an input to open it. Type at least 2 characters to see suggestions. Use ↑ ↓ to navigate, Enter to open, Esc to close. Prefix your query with # to search by tag.

= How is the table of contents generated? =
Headings ( H2 and H3 ) inside the article body are automatically given anchor IDs and listed in the sticky sidebar. Hover any heading to reveal a copy-link icon.

== Changelog ==

= 5.0.1 - May 04, 2026 =
* Footer credit anchor text updated to "MyWiki WordPress Theme" for SEO consistency.
* Theme URI in style.css and footer link normalized to canonical trailing-slash form.
* No functional or template changes; copy and link polish only. Direct upgrade from 5.0.0 is safe.

= 5.0.0 - May 02, 2026 =
* Complete rebuild from the ground up.
* New design system: Cormorant Garamond serif headlines + Inter body + JetBrains Mono code.
* New AJAX search modal with grouped suggestions, keyboard navigation, ⌘K / Ctrl+K shortcut, and #tag prefix.
* New automatic table of contents with scroll-spy.
* New breadcrumbs with BreadcrumbList JSON-LD.
* New reading-time estimation.
* New "Was this helpful?" widget.
* New "Wiki Home" page template with category grid.
* New article navigation: previous / next.
* New copy-link icon on H2 / H3 hover.
* Removed jQuery dependency. Vanilla JS only.
* Removed Bootstrap. Custom layout system.
* Removed Font Awesome. Inline SVG icons.
* Block editor styles now match the front end.
* New customizer panel: hero, category grid, header CTA, footer, social links.
* Two new widgets: Popular Articles and Recent Articles.
* Resource hints (preconnect) for Google Fonts.
* Lazy-loaded featured images.
* WCAG-friendly focus states and skip link.

= 4.0 - July 11, 2022 =
* Misc changes as per PHP 8.1.

= 3.1.1 - 21 June 2021 =
* static search form replaced with get_search_form.

= 3.1.0 - 19 June 2021 =
* wp_body_open function added.

= 3.0.3 - May 16, 2020 =
* Misc changes.

== Upgrade Notice ==

= 5.0.1 =
Copy and link polish only. No functional changes. Safe to upgrade.

= 5.0.0 =
This is a major release. The theme has been fully rebuilt. Back up your customizer settings before upgrading. The old "fa-" icon classes are no longer supported — they are replaced by inline SVG icons.

== Credits ==

= Cormorant Garamond =
* by Christian Thalmann
* https://fonts.google.com/specimen/Cormorant+Garamond
* License: SIL Open Font License, 1.1 (https://scripts.sil.org/OFL)

= Inter =
* by Rasmus Andersson
* https://fonts.google.com/specimen/Inter
* License: SIL Open Font License, 1.1 (https://scripts.sil.org/OFL)

= JetBrains Mono =
* by JetBrains
* https://fonts.google.com/specimen/JetBrains+Mono
* License: SIL Open Font License, 1.1 (https://scripts.sil.org/OFL)

= Lucide Icons =
* https://lucide.dev/
* License: ISC License (https://github.com/lucide-icons/lucide/blob/main/LICENSE)
* Used as the visual reference for inline SVG icon set.

== Copyright ==

MyWiki WordPress Theme, Copyright 2026 FasterThemes
MyWiki is distributed under the terms of the GNU GPL v3 or later.
