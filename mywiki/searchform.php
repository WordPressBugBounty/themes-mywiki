<?php
/**
 * The template for displaying search forms.
 *
 * @package MyWiki
 */
?>
<form role="search" method="get" class="mw-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="mw-s-<?php echo esc_attr( wp_unique_id() ); ?>"><?php esc_html_e( 'Search for:', 'mywiki' ); ?></label>
	<div class="mw-searchform-inner">
		<span class="mw-searchform-icon" aria-hidden="true"><?php echo mywiki_icon( 'search', 18 ); ?></span>
		<input type="search"
			id="mw-s-<?php echo esc_attr( wp_unique_id() ); ?>"
			class="mw-searchform-input"
			placeholder="<?php esc_attr_e( 'Search the docs…', 'mywiki' ); ?>"
			value="<?php echo get_search_query(); ?>"
			name="s" />
		<button type="submit" class="mw-searchform-submit"><?php esc_html_e( 'Search', 'mywiki' ); ?></button>
	</div>
</form>
