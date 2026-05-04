<?php
/**
 * Page template — same elegant article layout as posts.
 *
 * @package MyWiki
 */

get_header();
?>

<div class="mw-container">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<header class="mw-page-header">
			<?php mywiki_breadcrumbs(); ?>
			<h1><?php the_title(); ?></h1>
		</header>

		<?php
		$raw_content = apply_filters( 'the_content', get_the_content( null, false ) );
		$raw_content = str_replace( ']]>', ']]&gt;', $raw_content );
		$built       = mywiki_build_toc( $raw_content );
		$has_toc     = ! empty( $built['toc'] );
		?>

		<div class="mw-content-layout <?php echo $has_toc ? '' : 'mw-content-layout-narrow'; ?>">

			<?php if ( $has_toc ) : ?>
				<?php echo $built['toc']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'mw-article' ); ?>>
				<?php
				if ( has_post_thumbnail() ) :
					?>
					<figure>
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					</figure>
					<?php
				endif;
				?>
				<?php echo $built['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
				wp_link_pages(
					array(
						'before' => '<nav class="mw-pagination" style="margin-top:32px;">',
						'after'  => '</nav>',
					)
				);
				?>
			</article>

		</div>

		<div class="mw-narrow">
			<?php comments_template(); ?>
		</div>

	<?php endwhile; endif; ?>

</div>

<?php get_footer(); ?>
