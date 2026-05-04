<?php
/**
 * The template for displaying attachments.
 *
 * @package MyWiki
 */

get_header(); ?>

<div class="mw-container mw-singular">
	<?php mywiki_breadcrumbs(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'mw-article mw-attachment' ); ?>>
			<header class="mw-article-header">
				<p class="mw-article-eyebrow"><?php esc_html_e( 'Attachment', 'mywiki' ); ?></p>
				<h1 class="mw-article-title"><?php the_title(); ?></h1>
				<div class="mw-article-meta">
					<span class="mw-meta-item">
						<?php echo mywiki_icon( 'calendar', 14 ); ?>
						<?php echo esc_html( get_the_date() ); ?>
					</span>
					<span class="mw-meta-item">
						<?php echo mywiki_icon( 'user', 14 ); ?>
						<?php the_author(); ?>
					</span>
				</div>
			</header>

			<figure class="mw-attachment-figure">
				<?php
				if ( wp_attachment_is_image() ) {
					echo wp_get_attachment_image( get_the_ID(), 'large', false, array( 'class' => 'mw-attachment-image' ) );
				} else {
					echo '<a class="mw-attachment-link" href="' . esc_url( wp_get_attachment_url() ) . '">' . esc_html( basename( get_attached_file( get_the_ID() ) ) ) . '</a>';
				}
				?>
				<?php if ( has_excerpt() ) : ?>
					<figcaption class="mw-attachment-caption"><?php the_excerpt(); ?></figcaption>
				<?php endif; ?>
			</figure>

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
