<?php
/**
 * MyWiki — "About / Go Pro" admin page.
 *
 * A welcome screen for new installs and a tasteful Pro upsell for
 * customers ready to upgrade. Designed to comply with WordPress.org
 * theme review guidelines: dismissible notice, single in-admin page,
 * no aggressive upsells inside Customizer or other admin screens.
 *
 * @package MyWiki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add MyWiki menu under Appearance.
 */
function mywiki_register_admin_page() {
	add_theme_page(
		__( 'MyWiki', 'mywiki' ),
		__( 'MyWiki', 'mywiki' ),
		'edit_theme_options',
		'mywiki-about',
		'mywiki_render_admin_page'
	);
}
add_action( 'admin_menu', 'mywiki_register_admin_page' );

/**
 * Enqueue admin styles only on our page.
 */
function mywiki_admin_enqueue( $hook ) {
	if ( 'appearance_page_mywiki-about' !== $hook ) {
		return;
	}
	wp_register_style( 'mywiki-admin', false, array(), wp_get_theme()->get( 'Version' ) );
	wp_enqueue_style( 'mywiki-admin' );
	wp_add_inline_style( 'mywiki-admin', mywiki_admin_inline_css() );
}
add_action( 'admin_enqueue_scripts', 'mywiki_admin_enqueue' );

/**
 * Inline admin CSS.
 *
 * Modern admin-y look: white surfaces with subtle borders, neutral
 * grey scale, terracotta accents (#c8522a) reserved for primary CTAs
 * and key emphasis. Cormorant Garamond used only for the H1 and the
 * section headings, so the page feels native to WP admin while still
 * carrying enough brand DNA.
 */
function mywiki_admin_inline_css() {
	return '
	.mw-admin{max-width:1140px;margin:24px 20px 60px 0;color:#1a1a1a;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Inter,Roboto,sans-serif;line-height:1.55}
	.mw-admin *{box-sizing:border-box}
	.mw-admin a{color:#c8522a;text-decoration:none}
	.mw-admin a:hover{text-decoration:none}

	/* Hero */
	.mw-hero{background:#fff;border:1px solid #e5e2dc;border-radius:14px;padding:48px 44px;position:relative;overflow:hidden}
	.mw-hero::after{content:"";position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#c8522a 0%,#e8a87c 50%,transparent 100%)}
	.mw-hero-version{display:inline-block;font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:#c8522a;font-weight:600;margin-bottom:14px}
	.mw-hero h1{font-family:"Cormorant Garamond","Iowan Old Style",Georgia,serif;font-size:42px;line-height:1.1;font-weight:500;letter-spacing:-.015em;margin:0 0 14px;color:#1a1a1a}
	.mw-hero h1 em{font-style:italic;font-weight:500;color:#c8522a}
	.mw-hero-sub{font-size:16px;color:#525252;max-width:620px;margin:0 0 28px;line-height:1.6}
	.mw-hero-actions{display:flex;flex-wrap:wrap;gap:10px}

	/* Buttons — chained selectors to beat the generic .mw-admin a rule */
	.mw-admin a.mw-btn{display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:8px;text-decoration:none;font-weight:500;font-size:14px;transition:transform .15s ease,background .15s ease,border-color .15s ease,color .15s ease;border:1px solid transparent;line-height:1}
	.mw-admin a.mw-btn:hover{text-decoration:none}
	.mw-admin a.mw-btn-primary{background:#c8522a;color:#fff}
	.mw-admin a.mw-btn-primary:hover{background:#a4421f;color:#fff;transform:translateY(-1px)}
	.mw-admin a.mw-btn-secondary{background:#fff;color:#1a1a1a;border-color:#d8d3c8}
	.mw-admin a.mw-btn-secondary:hover{border-color:#1a1a1a;color:#1a1a1a;background:#fff}
	.mw-admin a.mw-btn-ghost{background:transparent;color:#525252;padding-left:8px;padding-right:8px}
	.mw-admin a.mw-btn-ghost:hover{color:#1a1a1a}

	/* Section */
	.mw-section{margin-top:32px;background:#fff;border:1px solid #e5e2dc;border-radius:14px;padding:36px 40px}
	.mw-section-eyebrow{font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:8px}
	.mw-section h2{font-family:"Cormorant Garamond","Iowan Old Style",Georgia,serif;font-size:28px;font-weight:500;line-height:1.2;letter-spacing:-.01em;margin:0 0 8px;color:#1a1a1a}
	.mw-section h2 em{font-style:italic;color:#c8522a;font-weight:500}
	.mw-section-lead{font-size:15px;color:#525252;margin:0 0 24px;max-width:680px;line-height:1.6}

	/* Quickstart */
	.mw-quickstart{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
	@media(max-width:780px){.mw-quickstart{grid-template-columns:1fr}}
	.mw-step{display:flex;gap:14px;padding:18px;background:#fafaf7;border:1px solid #ece9e2;border-radius:10px;text-decoration:none;color:#1a1a1a;transition:border-color .15s,background .15s}
	.mw-step:hover{border-color:#c8522a;background:#fff;text-decoration:none}
	.mw-step-num{width:30px;height:30px;border-radius:50%;background:#1a1a1a;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;flex-shrink:0;font-family:"Cormorant Garamond",Georgia,serif;font-style:italic}
	.mw-step-text strong{display:block;font-size:14px;font-weight:600;margin-bottom:3px;color:#1a1a1a}
	.mw-step-text span{font-size:13px;color:#737373;line-height:1.5;display:block}
	.mw-quickstart-tease{margin:18px 0 0;padding:14px 18px;background:#fbe9e1;border-left:3px solid #c8522a;border-radius:6px;font-size:13px;color:#1a1a1a;line-height:1.6}
	.mw-admin .mw-quickstart-tease a{color:#a4421f;font-weight:600}
	.mw-admin .mw-quickstart-tease a:hover{color:#7a3217}

	/* Skins showcase */
	.mw-skins{display:grid;grid-template-columns:repeat(2,1fr);gap:18px;margin-top:8px}
	@media(max-width:780px){.mw-skins{grid-template-columns:1fr}}
	.mw-skin{border:1px solid #e5e2dc;border-radius:12px;overflow:hidden;background:#fff;transition:border-color .15s ease,transform .15s ease}
	.mw-skin:hover{border-color:#c8522a;transform:translateY(-2px)}
	.mw-skin-preview{aspect-ratio:16/10;background:#fafaf7;border-bottom:1px solid #ece9e2;display:flex;align-items:center;justify-content:center;padding:24px;position:relative}
	.mw-skin-preview svg{width:100%;height:auto;max-height:170px}
	.mw-skin-info{padding:18px 20px}
	.mw-skin-name{font-family:"Cormorant Garamond",Georgia,serif;font-size:20px;font-weight:600;margin:0 0 4px;color:#1a1a1a;display:flex;align-items:center;gap:8px}
	.mw-skin-tag{font-size:10px;letter-spacing:.12em;text-transform:uppercase;font-weight:600;padding:3px 8px;border-radius:999px;font-family:-apple-system,sans-serif;font-style:normal}
	.mw-skin-tag-free{background:#e8f4ec;color:#1f6d3e}
	.mw-skin-tag-pro{background:#fbe9e1;color:#a4421f}
	.mw-skin-desc{font-size:13px;color:#737373;margin:0;line-height:1.55}

	/* Free vs Pro */
	.mw-compare{margin-top:8px;border:1px solid #ece9e2;border-radius:10px;overflow:hidden}
	.mw-compare table{width:100%;border-collapse:collapse}
	.mw-compare th,.mw-compare td{padding:14px 18px;text-align:left;border-bottom:1px solid #f0eee7;font-size:14px}
	.mw-compare tr:last-child td{border-bottom:none}
	.mw-compare th{background:#fafaf7;font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#525252}
	.mw-compare td:nth-child(2),.mw-compare td:nth-child(3),.mw-compare th:nth-child(2),.mw-compare th:nth-child(3){text-align:center;width:110px}
	.mw-yes{color:#c8522a;font-weight:600;font-size:16px}
	.mw-no{color:#c4c4b8;font-size:16px}
	.mw-compare-section-row td{background:#fafaf7;font-family:"Cormorant Garamond",Georgia,serif;font-style:italic;font-size:14px;color:#1a1a1a;font-weight:600;padding:10px 18px;border-bottom:1px solid #ece9e2}
	.mw-feat-note{display:block;font-size:12px;color:#888;margin-top:3px;line-height:1.5;font-weight:400}

	/* Resources */
	.mw-resources{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
	@media(max-width:780px){.mw-resources{grid-template-columns:1fr}}
	.mw-resource{display:flex;align-items:flex-start;gap:14px;padding:18px;background:#fafaf7;border:1px solid #ece9e2;border-radius:10px;text-decoration:none;color:#1a1a1a;transition:border-color .15s,background .15s}
	.mw-resource:hover{border-color:#c8522a;background:#fff;text-decoration:none}
	.mw-resource-icon{width:34px;height:34px;border-radius:8px;background:#fbe9e1;color:#c8522a;display:flex;align-items:center;justify-content:center;flex-shrink:0}
	.mw-resource-text strong{display:block;font-size:14px;font-weight:600;margin-bottom:3px}
	.mw-resource-text span{font-size:12px;color:#737373;line-height:1.5;display:block}

	/* Footer */
	.mw-foot{margin-top:32px;padding:20px 0;text-align:center;color:#888;font-size:13px;font-family:"Cormorant Garamond",Georgia,serif;font-style:italic}
	';
}

/**
 * Render the admin page.
 */
function mywiki_render_admin_page() {
	$theme       = wp_get_theme();
	$pro_url     = 'https://fasterthemes.com/wordpress-themes/mywiki/?utm_source=mywiki&utm_medium=admin&utm_campaign=upgrade';
	$docs_url    = 'https://docs.fasterthemes.com/mywiki-wordpress-theme/?utm_source=mywiki&utm_medium=admin';
	$demo_url    = 'https://demo.fasterthemes.com/mywiki-wordpress-theme/';
	$support_url = 'https://wordpress.org/support/theme/mywiki/';
	?>
	<div class="mw-admin">

		<?php /* ---------- HERO ---------- */ ?>
		<div class="mw-hero">
			<span class="mw-hero-version"><?php printf( esc_html__( 'MyWiki v%s', 'mywiki' ), esc_html( $theme->get( 'Version' ) ) ); ?></span>
			<h1><?php esc_html_e( 'A documentation theme', 'mywiki' ); ?> <em><?php esc_html_e( 'people actually love.', 'mywiki' ); ?></em></h1>
			<p class="mw-hero-sub"><?php esc_html_e( 'Built for knowledge bases, wikis, and product docs. Modal AJAX search, automatic table of contents, sticky sidebar navigation, and a typography system tuned for long-form reading.', 'mywiki' ); ?></p>
			<div class="mw-hero-actions">
				<a class="mw-btn mw-btn-primary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Open Customizer', 'mywiki' ); ?> →</a>
				<a class="mw-btn mw-btn-secondary" href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Read the docs', 'mywiki' ); ?></a>
			</div>
		</div>

		<?php /* ---------- QUICKSTART ---------- */ ?>
		<div class="mw-section">
			<div class="mw-section-eyebrow"><?php esc_html_e( 'Get started', 'mywiki' ); ?></div>
			<h2><?php esc_html_e( 'Four steps', 'mywiki' ); ?> <em><?php esc_html_e( 'to your wiki homepage.', 'mywiki' ); ?></em></h2>
			<p class="mw-section-lead"><?php esc_html_e( 'MyWiki ships with a custom Wiki Home page template. Follow these to set it up and unlock the full experience.', 'mywiki' ); ?></p>
			<div class="mw-quickstart">
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=page' ) ); ?>" class="mw-step">
					<span class="mw-step-num">1</span>
					<span class="mw-step-text">
						<strong><?php esc_html_e( 'Create a Page', 'mywiki' ); ?></strong>
						<span><?php esc_html_e( 'Add a new page and assign the “Wiki Home” page template.', 'mywiki' ); ?></span>
					</span>
				</a>
				<a href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>" class="mw-step">
					<span class="mw-step-num">2</span>
					<span class="mw-step-text">
						<strong><?php esc_html_e( 'Set as Front Page', 'mywiki' ); ?></strong>
						<span><?php esc_html_e( 'Settings → Reading → A static page → choose your wiki page.', 'mywiki' ); ?></span>
					</span>
				</a>
				<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=category' ) ); ?>" class="mw-step">
					<span class="mw-step-num">3</span>
					<span class="mw-step-text">
						<strong><?php esc_html_e( 'Build Categories', 'mywiki' ); ?></strong>
						<span><?php esc_html_e( 'Top-level categories become the topic cards on your homepage.', 'mywiki' ); ?></span>
					</span>
				</a>
				<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="mw-step">
					<span class="mw-step-num">4</span>
					<span class="mw-step-text">
						<strong><?php esc_html_e( 'Customize Hero & Header', 'mywiki' ); ?></strong>
						<span><?php esc_html_e( 'Tune the headline, italic accent, CTA, and social links.', 'mywiki' ); ?></span>
					</span>
				</a>
			</div>

			<p class="mw-quickstart-tease">
				<?php
				printf(
					/* translators: %s is a link to the upgrade page */
					esc_html__( 'In a hurry? %s skips all four steps with one click — Wiki Home page, six categories, twenty sample articles, and the right reading-page configuration, all set up for you.', 'mywiki' ),
					'<a href="' . esc_url( $pro_url ) . '" target="_blank" rel="noopener">' . esc_html__( 'The MyWiki Pro demo importer', 'mywiki' ) . '</a>'
				);
				?>
			</p>
		</div>

		<?php /* ---------- PRO INTRODUCTION ---------- */ ?>
		<div class="mw-section">
			<div class="mw-section-eyebrow"><?php esc_html_e( 'MyWiki Pro Plugin', 'mywiki' ); ?></div>
			<h2><?php esc_html_e( 'MyWiki ships your docs.', 'mywiki' ); ?> <em><?php esc_html_e( 'Pro converts them.', 'mywiki' ); ?></em></h2>
			<p class="mw-section-lead"><?php esc_html_e( 'MyWiki Pro is the power plugin that turns your documentation into a sales engine. It impresses visitors with premium visual skins, helps them find answers in milliseconds with a Command-K search modal, and shows you exactly what they search for — so you write content that converts.', 'mywiki' ); ?></p>

			<div class="mw-skins">
				<?php /* Default skin (free) */ ?>
				<div class="mw-skin">
					<div class="mw-skin-preview"><?php echo mywiki_admin_skin_preview( 'default' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div class="mw-skin-info">
						<h3 class="mw-skin-name">
							<?php esc_html_e( 'Default Skin', 'mywiki' ); ?>
							<span class="mw-skin-tag mw-skin-tag-free"><?php esc_html_e( 'Free', 'mywiki' ); ?></span>
						</h3>
						<p class="mw-skin-desc"><?php esc_html_e( 'Clean, modern, neutral. Inter for UI, Cormorant for editorial moments. Ships with the free theme.', 'mywiki' ); ?></p>
					</div>
				</div>

				<?php /* Editorial skin (pro) */ ?>
				<div class="mw-skin">
					<div class="mw-skin-preview"><?php echo mywiki_admin_skin_preview( 'editorial' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div class="mw-skin-info">
						<h3 class="mw-skin-name">
							<?php esc_html_e( 'Editorial Skin', 'mywiki' ); ?>
							<span class="mw-skin-tag mw-skin-tag-pro"><?php esc_html_e( 'Pro', 'mywiki' ); ?></span>
						</h3>
						<p class="mw-skin-desc"><?php esc_html_e( 'Cormorant Garamond italics, Jost UI, cream paper, terracotta accents. Built for brand and product docs that close deals.', 'mywiki' ); ?></p>
					</div>
				</div>
			</div>

			<p style="margin-top:24px;font-size:13px;color:#737373;font-style:italic;font-family:&quot;Cormorant Garamond&quot;,Georgia,serif"><?php esc_html_e( 'New skins ship with each Pro release — your license unlocks every future skin.', 'mywiki' ); ?></p>
		</div>

		<?php /* ---------- FREE VS PRO ---------- */ ?>
		<div class="mw-section">
			<div class="mw-section-eyebrow"><?php esc_html_e( 'What you get', 'mywiki' ); ?></div>
			<h2><?php esc_html_e( 'A solid foundation,', 'mywiki' ); ?> <em><?php esc_html_e( 'with room to grow.', 'mywiki' ); ?></em></h2>
			<p class="mw-section-lead"><?php esc_html_e( 'Free MyWiki is a complete documentation theme — installable, beautiful, ready to ship. MyWiki Pro is the power plugin you add when documentation becomes a serious channel for converting visitors into paying customers.', 'mywiki' ); ?></p>

			<div class="mw-compare">
				<table>
					<thead>
						<tr>
							<th><?php esc_html_e( 'Capability', 'mywiki' ); ?></th>
							<th><?php esc_html_e( 'Free Theme', 'mywiki' ); ?></th>
							<th><?php esc_html_e( 'Pro Plugin', 'mywiki' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr class="mw-compare-section-row"><td colspan="3"><?php esc_html_e( 'Core experience', 'mywiki' ); ?></td></tr>
						<tr><td><?php esc_html_e( 'Wiki Home page template with hero & category grid', 'mywiki' ); ?></td><td><span class="mw-yes">✓</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td><?php esc_html_e( 'Inline AJAX search suggestions', 'mywiki' ); ?></td><td><span class="mw-yes">✓</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td><?php esc_html_e( 'Automatic table of contents with scroll-spy', 'mywiki' ); ?></td><td><span class="mw-yes">✓</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td><?php esc_html_e( 'Reading time, breadcrumbs, related articles', 'mywiki' ); ?></td><td><span class="mw-yes">✓</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td><?php esc_html_e( '“Was this helpful?” feedback widget', 'mywiki' ); ?></td><td><span class="mw-yes">✓</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td><?php esc_html_e( 'Schema.org Article markup for SEO', 'mywiki' ); ?></td><td><span class="mw-yes">✓</span></td><td><span class="mw-yes">✓</span></td></tr>

						<tr class="mw-compare-section-row"><td colspan="3"><?php esc_html_e( 'Conversion power-ups', 'mywiki' ); ?></td></tr>
						<tr><td>
							<strong><?php esc_html_e( 'Premium visual skins', 'mywiki' ); ?></strong>
							<span class="mw-feat-note"><?php esc_html_e( 'Editorial today, more shipping with every release', 'mywiki' ); ?></span>
						</td><td><span class="mw-no">—</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td>
							<strong><?php esc_html_e( 'Command-K search modal', 'mywiki' ); ?></strong>
							<span class="mw-feat-note"><?php esc_html_e( 'Power-user keyboard search that impresses on first try', 'mywiki' ); ?></span>
						</td><td><span class="mw-no">—</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td>
							<strong><?php esc_html_e( '15 curated Google Font pairings', 'mywiki' ); ?></strong>
							<span class="mw-feat-note"><?php esc_html_e( 'Editorial-grade typography in one click', 'mywiki' ); ?></span>
						</td><td><span class="mw-no">—</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td>
							<strong><?php esc_html_e( 'Search analytics dashboard', 'mywiki' ); ?></strong>
							<span class="mw-feat-note"><?php esc_html_e( 'See exactly what visitors search for — and what they cannot find', 'mywiki' ); ?></span>
						</td><td><span class="mw-no">—</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td>
							<strong><?php esc_html_e( 'One-click demo importer', 'mywiki' ); ?></strong>
							<span class="mw-feat-note"><?php esc_html_e( 'Twenty sample articles, six categories, footer blocks — instant launch', 'mywiki' ); ?></span>
						</td><td><span class="mw-no">—</span></td><td><span class="mw-yes">✓</span></td></tr>
						<tr><td>
							<strong><?php esc_html_e( 'Priority email support', 'mywiki' ); ?></strong>
							<span class="mw-feat-note"><?php esc_html_e( 'Direct line to the team behind MyWiki', 'mywiki' ); ?></span>
						</td><td><span class="mw-no">—</span></td><td><span class="mw-yes">✓</span></td></tr>
					</tbody>
				</table>
			</div>

			<p style="margin-top:22px;display:flex;gap:10px;flex-wrap:wrap;align-items:center">
				<a class="mw-btn mw-btn-primary" href="<?php echo esc_url( $pro_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get the Pro Plugin — $39', 'mywiki' ); ?> →</a>
				<a class="mw-btn mw-btn-secondary" href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'See Pro in action', 'mywiki' ); ?></a>
				<span style="font-size:12px;color:#737373;margin-left:6px"><?php esc_html_e( 'One payment · Lifetime updates · Unlimited sites', 'mywiki' ); ?></span>
			</p>
		</div>

		<?php /* ---------- RESOURCES ---------- */ ?>
		<div class="mw-section">
			<div class="mw-section-eyebrow"><?php esc_html_e( 'Resources', 'mywiki' ); ?></div>
			<h2><?php esc_html_e( 'Help is', 'mywiki' ); ?> <em><?php esc_html_e( 'never far away.', 'mywiki' ); ?></em></h2>

			<div class="mw-resources">
				<a href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener" class="mw-resource">
					<span class="mw-resource-icon"><?php echo mywiki_admin_svg( 'book' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="mw-resource-text">
						<strong><?php esc_html_e( 'Documentation', 'mywiki' ); ?></strong>
						<span><?php esc_html_e( 'Setup guides, customization, and the FAQ.', 'mywiki' ); ?></span>
					</span>
				</a>
				<a href="<?php echo esc_url( $support_url ); ?>" target="_blank" rel="noopener" class="mw-resource">
					<span class="mw-resource-icon"><?php echo mywiki_admin_svg( 'message' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="mw-resource-text">
						<strong><?php esc_html_e( 'Free Support', 'mywiki' ); ?></strong>
						<span><?php esc_html_e( 'Ask questions on the WordPress.org forum.', 'mywiki' ); ?></span>
					</span>
				</a>
				<a href="https://wordpress.org/themes/mywiki/#reviews" target="_blank" rel="noopener" class="mw-resource">
					<span class="mw-resource-icon"><?php echo mywiki_admin_svg( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="mw-resource-text">
						<strong><?php esc_html_e( 'Leave a Review', 'mywiki' ); ?></strong>
						<span><?php esc_html_e( 'Enjoying MyWiki? A 5-star review helps us keep building.', 'mywiki' ); ?></span>
					</span>
				</a>
			</div>
		</div>

		<p class="mw-foot"><?php esc_html_e( 'MyWiki — quietly making WordPress beautiful since 2014.', 'mywiki' ); ?></p>

	</div>
	<?php
}

/**
 * Tiny SVG helper for admin (avoids needing front-end mywiki_icon).
 */
function mywiki_admin_svg( $name ) {
	$icons = array(
		'book'      => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>',
		'message'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
		'star'      => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
	);
	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Inline SVG mockups for the skin showcase.
 *
 * Hand-crafted preview tiles so the admin page doesn't need to ship
 * raster images. Each preview is a stylized representation of the
 * skin's homepage — enough to give a feel for the aesthetic.
 */
function mywiki_admin_skin_preview( $skin ) {
	if ( 'editorial' === $skin ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 200" preserveAspectRatio="xMidYMid meet">
			<rect width="320" height="200" fill="#f5f3ef"/>
			<rect x="0" y="0" width="320" height="22" fill="#f5f3ef" stroke="#e5e2dc"/>
			<rect x="9" y="6" width="10" height="10" rx="2" fill="#1a1a1a"/>
			<text x="13" y="14" font-family="Cormorant Garamond,Georgia,serif" font-style="italic" font-size="7" fill="#f5f3ef" text-anchor="middle">F</text>
			<text x="24" y="14" font-family="Cormorant Garamond,Georgia,serif" font-weight="600" font-size="8" fill="#1a1a1a">FasterThemes</text>
			<rect x="100" y="6" width="120" height="10" rx="3" fill="#fff" stroke="#e5e2dc"/>
			<rect x="244" y="5" width="36" height="12" rx="6" fill="#1a1a1a"/>
			<text x="262" y="13" font-family="Inter,sans-serif" font-size="6" fill="#fff" text-anchor="middle">Pro</text>
			<text x="160" y="60" font-family="Inter,sans-serif" font-weight="600" font-size="5" fill="#c8522a" text-anchor="middle" letter-spacing="1.4">DOCUMENTATION</text>
			<text x="160" y="86" font-family="Cormorant Garamond,Georgia,serif" font-weight="500" font-size="20" fill="#1a1a1a" text-anchor="middle">Everything you need to</text>
			<text x="160" y="110" font-family="Cormorant Garamond,Georgia,serif" font-style="italic" font-weight="500" font-size="19" fill="#c8522a" text-anchor="middle">cook with confidence.</text>
			<rect x="60" y="125" width="200" height="22" rx="6" fill="#fff" stroke="#e5e2dc"/>
			<text x="74" y="139" font-family="Inter,sans-serif" font-size="7" fill="#888">Search the docs…</text>
			<g transform="translate(40,160)">
				<rect width="80" height="32" rx="6" fill="#fff" stroke="#e5e2dc"/>
				<rect x="10" y="9" width="14" height="14" rx="3" fill="#fbe9e1"/>
				<text x="32" y="19" font-family="Cormorant Garamond,Georgia,serif" font-weight="600" font-size="9" fill="#1a1a1a">Setup</text>
			</g>
			<g transform="translate(125,160)">
				<rect width="80" height="32" rx="6" fill="#fff" stroke="#e5e2dc"/>
				<rect x="10" y="9" width="14" height="14" rx="3" fill="#fbe9e1"/>
				<text x="32" y="19" font-family="Cormorant Garamond,Georgia,serif" font-weight="600" font-size="9" fill="#1a1a1a">Pro</text>
			</g>
			<g transform="translate(210,160)">
				<rect width="80" height="32" rx="6" fill="#fff" stroke="#e5e2dc"/>
				<rect x="10" y="9" width="14" height="14" rx="3" fill="#fbe9e1"/>
				<text x="32" y="19" font-family="Cormorant Garamond,Georgia,serif" font-weight="600" font-size="9" fill="#1a1a1a">FAQ</text>
			</g>
		</svg>';
	}

	// Default skin
	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 200" preserveAspectRatio="xMidYMid meet">
		<rect width="320" height="200" fill="#fafaf7"/>
		<rect x="0" y="0" width="320" height="22" fill="#fafaf7" stroke="#e8e6df"/>
		<circle cx="14" cy="11" r="4.5" fill="#0f3a82"/>
		<text x="24" y="14" font-family="Inter,sans-serif" font-weight="600" font-size="8" fill="#1a1a1a">MyWiki</text>
		<rect x="100" y="6" width="120" height="10" rx="3" fill="#fff" stroke="#e8e6df"/>
		<rect x="244" y="5" width="36" height="12" rx="6" fill="#0f3a82"/>
		<text x="262" y="13" font-family="Inter,sans-serif" font-size="6" fill="#fff" text-anchor="middle">Get Pro</text>
		<text x="160" y="78" font-family="Cormorant Garamond,Georgia,serif" font-weight="500" font-size="20" fill="#1a1a1a" text-anchor="middle">Find any answer</text>
		<text x="160" y="102" font-family="Cormorant Garamond,Georgia,serif" font-style="italic" font-weight="500" font-size="19" fill="#0f3a82" text-anchor="middle">in milliseconds.</text>
		<rect x="60" y="118" width="200" height="22" rx="6" fill="#fff" stroke="#e8e6df"/>
		<text x="74" y="132" font-family="Inter,sans-serif" font-size="7" fill="#888">Search the docs…</text>
		<g transform="translate(40,158)">
			<rect width="80" height="32" rx="6" fill="#fff" stroke="#e8e6df"/>
			<rect x="10" y="9" width="14" height="14" rx="3" fill="#eef3fc"/>
			<text x="32" y="19" font-family="Inter,sans-serif" font-weight="600" font-size="9" fill="#1a1a1a">Start</text>
		</g>
		<g transform="translate(125,158)">
			<rect width="80" height="32" rx="6" fill="#fff" stroke="#e8e6df"/>
			<rect x="10" y="9" width="14" height="14" rx="3" fill="#eef3fc"/>
			<text x="32" y="19" font-family="Inter,sans-serif" font-weight="600" font-size="9" fill="#1a1a1a">Guide</text>
		</g>
		<g transform="translate(210,158)">
			<rect width="80" height="32" rx="6" fill="#fff" stroke="#e8e6df"/>
			<rect x="10" y="9" width="14" height="14" rx="3" fill="#eef3fc"/>
			<text x="32" y="19" font-family="Inter,sans-serif" font-weight="600" font-size="9" fill="#1a1a1a">FAQ</text>
		</g>
	</svg>';
}

/**
 * Add admin notice for new installs.
 *
 * Dismissible, shown only on Dashboard and Themes screens, never on
 * other admin pages. WP.org review compliant.
 */
function mywiki_admin_notice() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	if ( get_option( 'mywiki_notice_dismissed' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}
	$allowed_screens = array( 'dashboard', 'themes' );
	if ( ! in_array( $screen->id, $allowed_screens, true ) ) {
		return;
	}
	?>
	<div class="notice notice-info is-dismissible mywiki-welcome-notice">
		<p style="font-size:14px;margin:8px 0">
			<strong><?php esc_html_e( 'Welcome to MyWiki.', 'mywiki' ); ?></strong>
			<?php esc_html_e( 'Thanks for installing — your quick-start guide is ready.', 'mywiki' ); ?>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=mywiki-about' ) ); ?>" class="button button-primary" style="margin-left:8px"><?php esc_html_e( 'Get started', 'mywiki' ); ?></a>
			<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'mywiki-dismiss', '1' ), 'mywiki_dismiss' ) ); ?>" style="margin-left:6px;color:#737373;text-decoration:none"><?php esc_html_e( 'Dismiss', 'mywiki' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'mywiki_admin_notice' );

/**
 * Handle dismiss.
 */
function mywiki_admin_notice_dismiss() {
	if ( isset( $_GET['mywiki-dismiss'] ) && check_admin_referer( 'mywiki_dismiss' ) ) {
		update_option( 'mywiki_notice_dismissed', 1 );
		wp_safe_redirect( remove_query_arg( array( 'mywiki-dismiss', '_wpnonce' ) ) );
		exit;
	}
}
add_action( 'admin_init', 'mywiki_admin_notice_dismiss' );
