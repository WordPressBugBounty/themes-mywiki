<?php
/**
 * Template Name: Wiki Home
 *
 * The signature MyWiki landing layout: hero search + browseable category grid.
 *
 * @package MyWiki
 */

get_header();

$selected_cats = get_theme_mod( 'mywiki_category_list', array() );
if ( empty( $selected_cats ) || ( isset( $selected_cats[0] ) && 0 == $selected_cats[0] ) ) {
	$selected_cats = array();
}
$per_cat        = absint( get_theme_mod( 'mywiki_category_count', 4 ) );
$cat_title      = get_theme_mod( 'mywiki_category_title', __( 'Browse by category', 'mywiki' ) );
$hero_eyebrow   = get_theme_mod( 'mywiki_hero_eyebrow', __( 'Knowledge, instantly searchable', 'mywiki' ) );
$hero_heading   = get_theme_mod( 'mywiki_hero_heading', __( 'Find any answer', 'mywiki' ) );
$hero_italic    = get_theme_mod( 'mywiki_hero_italic', __( 'in milliseconds.', 'mywiki' ) );
$hero_subtitle  = get_theme_mod( 'mywiki_hero_subtitle', __( 'Search the documentation, browse by topic, or jump straight to what you need.', 'mywiki' ) );
$show_stats     = get_theme_mod( 'mywiki_hero_stats', 1 );

$cat_count = wp_count_terms(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
	)
);
if ( is_wp_error( $cat_count ) ) {
	$cat_count = 0;
}
$post_count = wp_count_posts();
$published  = isset( $post_count->publish ) ? intval( $post_count->publish ) : 0;
?>

<section class="mw-hero">
	<div class="mw-container">
		<?php if ( $hero_eyebrow ) : ?>
			<div class="mw-hero-eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></div>
		<?php endif; ?>
		<h1 class="mw-hero-title">
			<span class="mw-hero-title-main"><?php echo esc_html( $hero_heading ); ?></span>
			<?php if ( $hero_italic ) : ?>
				<span class="mw-italic-accent mw-hero-title-italic"><?php echo esc_html( $hero_italic ); ?></span>
			<?php endif; ?>
		</h1>
		<?php if ( $hero_subtitle ) : ?>
			<p class="mw-hero-sub mw-hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
		<?php endif; ?>

		<?php
		$mw_search_type = get_theme_mod( 'mywiki_search_type', 'suggestions' );
		if ( 'modal' === $mw_search_type ) :
			?>
			<button type="button" class="mw-hero-search" data-mw-search-trigger aria-label="<?php esc_attr_e( 'Open search', 'mywiki' ); ?>">
				<?php echo mywiki_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="mw-hero-search-text">
					<?php
					/* translators: %d: number of articles */
					printf( esc_html( _n( 'Search %d article…', 'Search %d articles…', $published, 'mywiki' ) ), intval( $published ) );
					?>
				</span>
				<span class="mw-kbd">⌘ K</span>
			</button>
		<?php else : ?>
			<div class="mw-hero-search-wrap" data-mw-suggest data-mw-suggest-hero>
				<span class="mw-hero-search-icon" aria-hidden="true"><?php echo mywiki_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<input
					type="search"
					class="mw-hero-search-input"
					data-mw-suggest-input
					placeholder="<?php
					/* translators: %d: number of articles */
					echo esc_attr( sprintf( _n( 'Search %d article…', 'Search %d articles…', $published, 'mywiki' ), intval( $published ) ) );
					?>"
					autocomplete="off"
					autocorrect="off"
					spellcheck="false"
					aria-label="<?php esc_attr_e( 'Search articles', 'mywiki' ); ?>" />
				<div class="mw-suggest-panel" data-mw-suggest-panel hidden></div>
			</div>
		<?php endif; ?>

		<?php if ( $show_stats ) : ?>
			<div class="mw-hero-stats" aria-hidden="true">
				<span><strong><?php echo esc_html( number_format_i18n( $published ) ); ?></strong> <?php esc_html_e( 'articles', 'mywiki' ); ?></span>
				<span><strong><?php echo esc_html( number_format_i18n( $cat_count ) ); ?></strong> <?php esc_html_e( 'categories', 'mywiki' ); ?></span>
				<span><?php esc_html_e( 'Always up to date', 'mywiki' ); ?></span>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="mw-section">
	<div class="mw-container">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php $page_content = get_the_content(); if ( trim( wp_strip_all_tags( $page_content ) ) !== '' ) : ?>
				<div class="mw-narrow mw-article" style="margin-bottom:48px;">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		<?php endwhile; endif; ?>

		<div class="mw-section-head">
			<div class="mw-section-eyebrow"><?php esc_html_e( 'Knowledgebase', 'mywiki' ); ?></div>
			<h2><?php echo esc_html( $cat_title ); ?></h2>
		</div>

		<?php
		$cats = get_categories(
			array(
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		// Color rotation for category icon backgrounds (semantic, not rainbow).
		$palettes = array(
			array( 'bg' => '#eef2fa', 'fg' => '#0f3a82' ),
			array( 'bg' => '#f5edee', 'fg' => '#993556' ),
			array( 'bg' => '#eaf3de', 'fg' => '#3b6d11' ),
			array( 'bg' => '#fcefea', 'fg' => '#993c1d' ),
			array( 'bg' => '#f1efe8', 'fg' => '#444441' ),
			array( 'bg' => '#faeeda', 'fg' => '#854f0b' ),
		);

		if ( $cats ) :
		?>
		<div class="mw-cat-grid">
			<?php
			$idx = 0;
			foreach ( $cats as $cat ) :
				if ( ! empty( $selected_cats ) && ! in_array( $cat->term_id, (array) $selected_cats, true ) ) {
					continue;
				}
				$pal = $palettes[ $idx % count( $palettes ) ];
				$idx++;

				$posts_q = new WP_Query(
					array(
						'posts_per_page' => $per_cat ? $per_cat : 4,
						'cat'            => $cat->term_id,
						'post_status'    => 'publish',
						'orderby'        => 'date',
						'order'          => 'DESC',
						'no_found_rows'  => true,
					)
				);
				?>
				<article class="mw-cat-card">
					<div class="mw-cat-head">
						<div class="mw-cat-icon" style="background:<?php echo esc_attr( $pal['bg'] ); ?>;color:<?php echo esc_attr( $pal['fg'] ); ?>;">
							<?php echo mywiki_icon( 'folder', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<span class="mw-cat-count">
							<?php
							/* translators: %d: post count */
							printf( esc_html( _n( '%d article', '%d articles', intval( $cat->count ), 'mywiki' ) ), intval( $cat->count ) );
							?>
						</span>
					</div>
					<h3 class="mw-cat-title">
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
					</h3>
					<?php if ( $posts_q->have_posts() ) : ?>
						<ul class="mw-cat-list">
							<?php while ( $posts_q->have_posts() ) : $posts_q->the_post(); ?>
								<li>
									<a href="<?php the_permalink(); ?>">
										<?php echo mywiki_icon( 'doc', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<span><?php the_title(); ?></span>
									</a>
								</li>
							<?php endwhile; ?>
						</ul>
					<?php endif; wp_reset_postdata(); ?>

					<?php if ( intval( $cat->count ) > intval( $per_cat ? $per_cat : 4 ) ) : ?>
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="mw-cat-more">
							<?php
							/* translators: %d: total in category */
							printf( esc_html__( 'View all %d', 'mywiki' ), intval( $cat->count ) );
							?>
							<?php echo mywiki_icon( 'arrow-r', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
		<?php else : ?>
			<p style="text-align:center;color:var(--mw-text-soft);"><?php esc_html_e( 'Add categories and posts to start building your knowledgebase.', 'mywiki' ); ?></p>
		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
