<?php
/**
 * Template Name: Full Width
 *
 * A full-width page template with no sidebar.
 *
 * @package MyWiki
 */

get_header();
?>

<div class="mw-container mw-singular mw-full-width">
	<?php mywiki_breadcrumbs(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'mw-article mw-article--full' ); ?>>
			<header class="mw-article-header">
				<h1 class="mw-article-title"><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="mw-article-figure">
					<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
				</figure>
			<?php endif; ?>

			<div class="mw-article-content">
				<?php
				the_content();
				wp_link_pages( array(
					'before' => '<nav class="mw-page-links">' . esc_html__( 'Pages:', 'mywiki' ),
					'after'  => '</nav>',
				) );
				?>
			</div>
		</article>

		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	<?php endwhile; ?>
</div>

<?php get_footer();
