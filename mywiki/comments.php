<?php
/**
 * Comments template.
 *
 * @package MyWiki
 */

if ( post_password_required() ) {
	return;
}
?>

<?php if ( comments_open() || get_comments_number() ) : ?>
<div id="comments" class="mw-comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$count = get_comments_number();
			if ( '1' === (string) $count ) {
				/* translators: %s: post title */
				printf( esc_html__( 'One thought on “%s”', 'mywiki' ), esc_html( get_the_title() ) );
			} else {
				printf(
					/* translators: 1: comment count, 2: post title */
					esc_html( _nx( '%1$s thought on “%2$s”', '%1$s thoughts on “%2$s”', intval( $count ), 'comments title', 'mywiki' ) ),
					esc_html( number_format_i18n( $count ) ),
					esc_html( get_the_title() )
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 36,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'class'     => 'mw-pagination',
			)
		);
		?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments" style="color:var(--mw-text-soft);font-size:14px;text-align:center;margin-top:20px;"><?php esc_html_e( 'Comments are closed.', 'mywiki' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_form'         => 'comment-form',
			'title_reply'        => __( 'Leave a comment', 'mywiki' ),
			'title_reply_to'     => __( 'Reply to %s', 'mywiki' ),
			'cancel_reply_link'  => __( 'Cancel reply', 'mywiki' ),
			'label_submit'       => __( 'Post comment', 'mywiki' ),
			'comment_notes_before' => '',
		)
	);
	?>

</div>
<?php endif; ?>
