<?php
/**
 * 404 template.
 *
 * @package MyWiki
 */

get_header();
?>

<div class="mw-container">
	<div class="mw-404">
		<div class="mw-404-num">404</div>
		<h1><?php esc_html_e( 'Article not found', 'mywiki' ); ?></h1>
		<p><?php esc_html_e( 'The page you are looking for might have been moved, renamed, or never existed. Try a search instead.', 'mywiki' ); ?></p>
		<button type="button" class="mw-cta" style="background:var(--mw-text);" data-mw-search-open>
			<?php esc_html_e( 'Open search', 'mywiki' ); ?>
		</button>
		<div style="margin-top:18px;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="font-size:14px;font-weight:500;color:var(--mw-accent);"><?php esc_html_e( '← Back to home', 'mywiki' ); ?></a>
		</div>
	</div>
</div>

<?php get_footer(); ?>
