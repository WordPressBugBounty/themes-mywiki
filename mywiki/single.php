<?php
/**
 * Single post template — with optional auto-TOC and helpfulness prompt.
 *
 * @package MyWiki
 */

get_header();
?>

<div class="mw-container">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<header class="mw-page-header">
			<?php mywiki_breadcrumbs(); ?>
			<?php
			$cats = get_the_category();
			if ( $cats ) :
				$primary = $cats[0];
				?>
				<div class="mw-page-eyebrow"><?php echo esc_html( $primary->name ); ?></div>
			<?php endif; ?>
			<h1 itemprop="headline"><?php the_title(); ?></h1>
			<div class="mw-page-meta">
				<span><?php echo mywiki_icon( 'calendar', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( get_the_date() ); ?></span>
				<?php
				$author_id   = get_the_author_meta( 'ID' );
				$author_name = get_the_author_meta( 'display_name' );
				if ( ! $author_name ) {
					$author_name = get_the_author_meta( 'user_login' );
				}
				if ( $author_name ) :
					?>
					<span>
						<?php echo mywiki_icon( 'user', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php echo esc_html( $author_name ); ?></a>
					</span>
				<?php endif; ?>
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
		</header>

		<?php
		$raw_content = apply_filters( 'the_content', get_the_content( null, false ) );
		$raw_content = str_replace( ']]>', ']]&gt;', $raw_content );
		$built       = mywiki_build_toc( $raw_content );
		$has_toc     = ! empty( $built['toc'] );
		?>

		<div class="mw-content-layout <?php echo $has_toc ? '' : 'mw-content-layout-narrow'; ?>">

			<?php if ( $has_toc ) : ?>
				<?php echo $built['toc']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- TOC is built from sanitized content. ?>
			<?php endif; ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'mw-article' ); ?> itemscope itemtype="https://schema.org/Article">
				<?php
				if ( has_post_thumbnail() ) :
					?>
					<figure>
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					</figure>
					<?php
				endif;
				?>
				<div itemprop="articleBody">
					<?php echo $built['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- TOC build returns the same filtered content with id attributes. ?>
					<?php
					wp_link_pages(
						array(
							'before' => '<nav class="mw-pagination" style="margin-top:32px;">',
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<footer class="mw-article-footer">

					<?php if ( has_tag() ) : ?>
						<div class="mw-tags">
							<?php
							foreach ( get_the_tags() as $tag ) {
								echo '<a class="mw-tag" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">#' . esc_html( $tag->name ) . '</a>';
							}
							?>
						</div>
					<?php endif; ?>

					<div class="mw-helpful" data-mw-helpful="<?php echo esc_attr( get_the_ID() ); ?>">
						<div class="mw-helpful-q"><?php esc_html_e( 'Was this article helpful?', 'mywiki' ); ?></div>
						<div class="mw-helpful-btns">
							<button type="button" class="mw-helpful-btn" data-helpful-vote="yes">
								<?php echo mywiki_icon( 'thumbs-u', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php esc_html_e( 'Yes', 'mywiki' ); ?>
							</button>
							<button type="button" class="mw-helpful-btn" data-helpful-vote="no">
								<?php echo mywiki_icon( 'thumbs-d', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php esc_html_e( 'No', 'mywiki' ); ?>
							</button>
						</div>
						<div class="mw-helpful-feedback" data-helpful-feedback hidden>
							<?php esc_html_e( 'Thanks for your feedback.', 'mywiki' ); ?>
						</div>
					</div>

					<?php
					$prev_post = get_previous_post();
					$next_post = get_next_post();
					if ( $prev_post || $next_post ) :
						?>
						<nav class="mw-post-nav" aria-label="<?php esc_attr_e( 'Article navigation', 'mywiki' ); ?>">
							<?php if ( $prev_post ) : ?>
								<a class="mw-post-nav-link prev" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
									<div class="mw-post-nav-label"><?php echo mywiki_icon( 'arrow-l', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Previous', 'mywiki' ); ?></div>
									<div class="mw-post-nav-title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></div>
								</a>
							<?php else : ?>
								<span></span>
							<?php endif; ?>

							<?php if ( $next_post ) : ?>
								<a class="mw-post-nav-link next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
									<div class="mw-post-nav-label"><?php esc_html_e( 'Next', 'mywiki' ); ?> <?php echo mywiki_icon( 'arrow-r', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
									<div class="mw-post-nav-title"><?php echo esc_html( get_the_title( $next_post ) ); ?></div>
								</a>
							<?php endif; ?>
						</nav>
					<?php endif; ?>
				</footer>
			</article>

		</div>

		<div class="mw-narrow">
			<?php comments_template(); ?>
		</div>

	<?php endwhile; endif; ?>

</div>

<?php get_footer(); ?>
