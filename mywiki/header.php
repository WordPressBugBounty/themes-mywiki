<?php
/**
 * The header for the MyWiki theme.
 *
 * @package MyWiki
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<link rel="profile" href="https://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#mw-main"><?php esc_html_e( 'Skip to content', 'mywiki' ); ?></a>

<div class="mw-site">

	<header class="mw-header" role="banner">
		<div class="mw-container">
			<div class="mw-header-inner">

				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					$tagline = get_bloginfo( 'description' );
					$name    = get_bloginfo( 'name' );
					$initial = mb_substr( $name, 0, 1 );
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mw-brand" rel="home">
						<span class="mw-brand-mark" aria-hidden="true"><?php echo esc_html( $initial ); ?></span>
						<span class="mw-brand-text">
							<?php echo esc_html( $name ); ?>
							<?php if ( $tagline ) : ?>
								<span class="mw-brand-tagline"><?php echo esc_html( $tagline ); ?></span>
							<?php endif; ?>
						</span>
					</a>
					<?php
				}
				?>

				<nav class="mw-nav" id="mw-primary-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'mywiki' ); ?>">
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
								'menu_class'     => 'mw-nav-list',
								'depth'          => 2,
								'fallback_cb'    => false,
							)
						);
					} else {
						?>
						<ul class="mw-nav-list">
							<?php wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) ); ?>
						</ul>
						<?php
					}
					?>
				</nav>

				<div class="mw-header-actions">
					<?php
					$mw_search_type        = get_theme_mod( 'mywiki_search_type', 'suggestions' );
					$mw_search_placeholder = get_theme_mod( 'mywiki_search_placeholder', __( 'Search the docs…', 'mywiki' ) );
					if ( 'modal' === $mw_search_type ) :
						?>
						<button type="button" class="mw-search-trigger" aria-label="<?php esc_attr_e( 'Open search', 'mywiki' ); ?>" data-mw-search-trigger>
							<?php echo mywiki_icon( 'search', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $mw_search_placeholder ); ?></span>
							<span class="mw-kbd">⌘ K</span>
						</button>
						<button type="button" class="mw-mobile-search" aria-label="<?php esc_attr_e( 'Open search', 'mywiki' ); ?>" data-mw-search-trigger>
							<?php echo mywiki_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
					<?php else : ?>
						<div class="mw-header-search" data-mw-suggest>
							<?php echo mywiki_icon( 'search', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<input
								type="search"
								class="mw-header-search-input"
								data-mw-suggest-input
								placeholder="<?php echo esc_attr( $mw_search_placeholder ); ?>"
								autocomplete="off"
								autocorrect="off"
								spellcheck="false"
								aria-label="<?php esc_attr_e( 'Search documentation', 'mywiki' ); ?>" />
							<div class="mw-suggest-panel" data-mw-suggest-panel hidden></div>
						</div>
						<button type="button" class="mw-mobile-search" aria-label="<?php esc_attr_e( 'Open search', 'mywiki' ); ?>" data-mw-suggest-mobile>
							<?php echo mywiki_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
					<?php endif; ?>

					<?php $cta_url = get_theme_mod( 'mywiki_header_cta_url', '' ); ?>
					<?php $cta_text = get_theme_mod( 'mywiki_header_cta_text', '' ); ?>
					<?php if ( $cta_url && $cta_text ) : ?>
						<a href="<?php echo esc_url( $cta_url ); ?>" class="mw-cta"><?php echo esc_html( $cta_text ); ?></a>
					<?php endif; ?>

					<button type="button" class="mw-menu-toggle" data-mw-menu-toggle aria-label="<?php esc_attr_e( 'Toggle menu', 'mywiki' ); ?>" aria-expanded="false" aria-controls="mw-primary-nav">
						<?php echo mywiki_icon( 'menu', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				</div>

			</div>
		</div>
	</header>

	<?php if ( 'modal' === $mw_search_type ) : ?>
	<div class="mw-search-modal" id="mw-search-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="mw-search-label">
		<div class="mw-modal-overlay" data-mw-search-close></div>
		<div class="mw-search-box">
			<div class="mw-search-input-wrap">
				<?php echo mywiki_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<label for="mw-search-input" id="mw-search-label" class="screen-reader-text"><?php esc_html_e( 'Search', 'mywiki' ); ?></label>
				<input type="search" id="mw-search-input" class="mw-search-input" placeholder="<?php echo esc_attr( $mw_search_placeholder ); ?>" autocomplete="off" autocorrect="off" spellcheck="false">
				<button type="button" class="mw-search-close" data-mw-search-close aria-label="<?php esc_attr_e( 'Close search', 'mywiki' ); ?>">esc</button>
			</div>
			<div class="mw-search-results" id="mw-search-results" role="listbox" aria-label="<?php esc_attr_e( 'Search results', 'mywiki' ); ?>"></div>
			<div class="mw-search-footer">
				<span><span class="mw-kbd">↑</span> <span class="mw-kbd">↓</span> <?php esc_html_e( 'navigate', 'mywiki' ); ?></span>
				<span><span class="mw-kbd">↵</span> <?php esc_html_e( 'select', 'mywiki' ); ?></span>
				<span><span class="mw-kbd">esc</span> <?php esc_html_e( 'close', 'mywiki' ); ?></span>
				<span class="mw-search-footer-meta"><?php esc_html_e( 'Tip:', 'mywiki' ); ?> <span class="mw-kbd">#</span> <?php esc_html_e( 'searches by tag', 'mywiki' ); ?></span>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<main class="mw-main" id="mw-main" role="main">
