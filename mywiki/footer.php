<?php
/**
 * The footer for the MyWiki theme.
 *
 * @package MyWiki
 */
?>
	</main>

	<footer class="mw-footer" role="contentinfo">
		<div class="mw-container">

			<div class="mw-footer-grid">

				<?php /* Column 1 — Brand block (site identity, not content) */ ?>
				<div class="mw-footer-brand-col">
					<?php
					$name    = get_bloginfo( 'name' );
					$tagline = get_bloginfo( 'description' );
					$initial = mb_substr( $name, 0, 1 );
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mw-footer-brand">
						<?php if ( has_custom_logo() ) : ?>
							<?php the_custom_logo(); ?>
						<?php else : ?>
							<span class="mw-brand-mark" aria-hidden="true"><?php echo esc_html( $initial ); ?></span>
							<span><?php echo esc_html( $name ); ?></span>
						<?php endif; ?>
					</a>
					<?php if ( $tagline ) : ?>
						<p class="mw-footer-desc"><?php echo esc_html( $tagline ); ?></p>
					<?php endif; ?>

					<?php
					$socials = array(
						'twitter'   => get_theme_mod( 'mywiki_social_twitter', '' ),
						'facebook'  => get_theme_mod( 'mywiki_social_facebook', '' ),
						'github'    => get_theme_mod( 'mywiki_social_github', '' ),
						'linkedin'  => get_theme_mod( 'mywiki_social_linkedin', '' ),
						'youtube'   => get_theme_mod( 'mywiki_social_youtube', '' ),
						'instagram' => get_theme_mod( 'mywiki_social_instagram', '' ),
						'rss'       => get_feed_link(),
					);
					$has_any = false;
					foreach ( array( 'twitter', 'facebook', 'github', 'linkedin', 'youtube', 'instagram' ) as $k ) {
						if ( ! empty( $socials[ $k ] ) ) {
							$has_any = true;
							break;
						}
					}
					if ( $has_any || ! empty( $socials['rss'] ) ) :
						?>
					<div class="mw-footer-social">
						<?php foreach ( $socials as $name_k => $url ) : ?>
							<?php if ( ! empty( $url ) ) : ?>
								<a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( ucfirst( $name_k ) ); ?>" rel="noopener" <?php echo 'rss' === $name_k ? '' : 'target="_blank"'; ?>>
									<?php echo mywiki_icon( $name_k, 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>

				<?php /* Columns 2-4 — fully widget-driven, no hardcoded content */ ?>
				<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
					<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
						<div class="mw-footer-col">
							<?php dynamic_sidebar( 'footer-' . $i ); ?>
						</div>
					<?php else : ?>
						<div class="mw-footer-col mw-footer-col-empty" aria-hidden="true"></div>
					<?php endif; ?>
				<?php endfor; ?>

			</div>

			<div class="mw-footer-divider" aria-hidden="true"></div>

			<div class="mw-footer-bottom">
				<div class="mw-footer-bottom-left">
					<?php
					$copyright = get_theme_mod( 'mywiki_footer_copyright', '' );
					if ( $copyright ) {
						echo wp_kses_post( $copyright );
					} else {
						/* translators: 1: current year, 2: site name */
						printf( esc_html__( '© %1$s %2$s. All rights reserved.', 'mywiki' ), esc_html( gmdate( 'Y' ) ), esc_html( $name ) );
					}
					?>
				</div>
				<div class="mw-footer-bottom-right">
					<?php
					if ( has_nav_menu( 'footer' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'menu_class'     => 'mw-footer-nav',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
					} else {
						printf(
							/* translators: %s: theme name link */
							esc_html__( 'Powered by %s', 'mywiki' ),
							'<a href="' . esc_url( __( 'https://fasterthemes.com/wordpress-themes/mywiki', 'mywiki' ) ) . '" rel="nofollow" target="_blank">MyWiki</a>'
						);
					}
					?>
				</div>
			</div>

		</div>
	</footer>

</div><?php // .mw-site ?>

<?php wp_footer(); ?>
</body>
</html>
