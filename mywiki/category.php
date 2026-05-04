<?php
/**
 * Category archive.
 *
 * @package MyWiki
 */

get_header();
$cat = get_queried_object();
?>

<div class="mw-container">

	<header class="mw-archive-header">
		<?php mywiki_breadcrumbs(); ?>
		<div class="mw-page-eyebrow"><?php esc_html_e( 'Category', 'mywiki' ); ?></div>
		<h1><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
		<?php $desc = category_description(); if ( $desc ) : ?>
			<div style="max-width:560px;margin:8px auto 0;color:var(--mw-text-muted);font-size:15px;"><?php echo wp_kses_post( $desc ); ?></div>
		<?php endif; ?>
		<?php if ( $cat && isset( $cat->count ) ) : ?>
			<div style="margin-top:14px;font-size:12px;color:var(--mw-text-soft);letter-spacing:0.06em;text-transform:uppercase;">
				<?php
				/* translators: %d: number of articles */
				printf( esc_html( _n( '%d article in this category', '%d articles in this category', intval( $cat->count ), 'mywiki' ) ), intval( $cat->count ) );
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
						<?php
						$reading_time = max( 1, ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 220 ) );
						?>
						<span>
							<?php echo mywiki_icon( 'clock', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php
							/* translators: %d: minutes */
							printf( esc_html( _n( '%d min read', '%d min read', $reading_time, 'mywiki' ) ), intval( $reading_time ) );
							?>
						</span>
					</div>
					<p class="mw-archive-excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 28, '…' ) ); ?></p>
				</article>
			<?php endwhile; ?>
		</div>

		<?php mywiki_pagination(); ?>

	<?php else : ?>
		<div class="mw-narrow" style="text-align:center;padding:64px 0;">
			<p style="color:var(--mw-text-muted);"><?php esc_html_e( 'No articles in this category yet.', 'mywiki' ); ?></p>
		</div>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
