<?php
/**
 * Main template — listing fallback.
 *
 * @package MyWiki
 */

get_header();
?>

<div class="mw-container">

	<?php if ( ! is_front_page() ) : ?>
		<header class="mw-archive-header">
			<?php mywiki_breadcrumbs(); ?>
			<h1><?php is_home() ? single_post_title() : esc_html_e( 'Latest articles', 'mywiki' ); ?></h1>
		</header>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="mw-archive-list">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'mw-archive-item' ); ?>>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="mw-archive-meta">
						<span><?php echo mywiki_icon( 'calendar', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( get_the_date() ); ?></span>
						<?php
						$cats = get_the_category();
						if ( $cats ) :
							?>
							<span>
								<?php echo mywiki_icon( 'folder', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php
								$cat_links = array();
								foreach ( $cats as $cat ) {
									$cat_links[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
								}
								echo wp_kses( implode( ', ', $cat_links ), array( 'a' => array( 'href' => array() ) ) );
								?>
							</span>
						<?php endif; ?>
					</div>
					<p class="mw-archive-excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 28, '…' ) ); ?></p>
				</article>
			<?php endwhile; ?>
		</div>

		<?php mywiki_pagination(); ?>

	<?php else : ?>
		<div class="mw-narrow" style="text-align:center;padding:64px 0;">
			<h2><?php esc_html_e( 'Nothing here yet', 'mywiki' ); ?></h2>
			<p style="color:var(--mw-text-muted);"><?php esc_html_e( 'It looks like there are no articles to show. Try the search above, or come back soon.', 'mywiki' ); ?></p>
		</div>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
