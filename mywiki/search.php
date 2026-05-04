<?php
/**
 * Search results template.
 *
 * @package MyWiki
 */

get_header();
?>

<div class="mw-container">

	<header class="mw-archive-header">
		<?php mywiki_breadcrumbs(); ?>
		<div class="mw-page-eyebrow"><?php esc_html_e( 'Search results', 'mywiki' ); ?></div>
		<h1>
			<?php
			/* translators: %s: search query */
			printf( esc_html__( 'Results for %s', 'mywiki' ), '<span class="mw-italic-accent">' . esc_html( get_search_query() ) . '</span>' );
			?>
		</h1>
		<?php
		global $wp_query;
		if ( isset( $wp_query->found_posts ) ) :
			?>
			<div style="margin-top:14px;font-size:12px;color:var(--mw-text-soft);letter-spacing:0.06em;text-transform:uppercase;">
				<?php
				/* translators: %d: number of results */
				printf( esc_html( _n( '%d result found', '%d results found', intval( $wp_query->found_posts ), 'mywiki' ) ), intval( $wp_query->found_posts ) );
				?>
			</div>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="mw-archive-list">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'mw-archive-item' ); ?>>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="mw-archive-meta">
						<span><?php echo mywiki_icon( 'calendar', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( get_the_date() ); ?></span>
						<span><?php echo mywiki_icon( 'doc', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( ucfirst( get_post_type() ) ); ?></span>
					</div>
					<p class="mw-archive-excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 32, '…' ) ); ?></p>
				</article>
			<?php endwhile; ?>
		</div>

		<?php mywiki_pagination(); ?>

	<?php else : ?>
		<div class="mw-narrow" style="text-align:center;padding:48px 0;">
			<h2 style="font-style:italic;color:var(--mw-text-muted);font-weight:400;"><?php esc_html_e( 'No matching articles', 'mywiki' ); ?></h2>
			<p style="color:var(--mw-text-muted);max-width:440px;margin:12px auto 24px;"><?php esc_html_e( 'Try different keywords, check spelling, or browse by category from the home page.', 'mywiki' ); ?></p>
			<?php get_search_form(); ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;margin-top:18px;color:var(--mw-accent);font-size:14px;font-weight:500;"><?php esc_html_e( '← Back to home', 'mywiki' ); ?></a>
		</div>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
