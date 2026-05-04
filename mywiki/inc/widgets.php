<?php
/**
 * MyWiki — Custom widgets.
 *
 * @package MyWiki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Popular Posts (by comment count) widget.
 */
class MyWiki_Popular_Posts_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'mywiki_popular_posts',
			__( 'MyWiki — Popular Articles', 'mywiki' ),
			array(
				'description' => __( 'Most-discussed articles, by comment count.', 'mywiki' ),
				'classname'   => 'widget_mywiki_popular',
			)
		);
	}

	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Popular Articles', 'mywiki' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		$days  = ! empty( $instance['days'] ) ? absint( $instance['days'] ) : 0;

		$query_args = array(
			'posts_per_page'      => $count,
			'orderby'             => 'comment_count',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( $days > 0 ) {
			$query_args['date_query'] = array( array( 'after' => $days . ' days ago' ) );
		}

		$q = new WP_Query( $query_args );

		if ( ! $q->have_posts() ) {
			return;
		}

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		echo '<ul class="mw-widget-list mw-widget-popular">';
		while ( $q->have_posts() ) {
			$q->the_post();
			$count_label = sprintf( _n( '%s comment', '%s comments', get_comments_number(), 'mywiki' ), number_format_i18n( get_comments_number() ) );
			?>
			<li class="mw-widget-item">
				<a href="<?php the_permalink(); ?>" class="mw-widget-link">
					<span class="mw-widget-title"><?php the_title(); ?></span>
					<span class="mw-widget-meta"><?php echo esc_html( $count_label ); ?></span>
				</a>
			</li>
			<?php
		}
		echo '</ul>';
		wp_reset_postdata();

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Popular Articles', 'mywiki' );
		$count = isset( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		$days  = isset( $instance['days'] ) ? absint( $instance['days'] ) : 0;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'mywiki' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of articles:', 'mywiki' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="20" step="1" value="<?php echo esc_attr( $count ); ?>" size="3">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'days' ) ); ?>"><?php esc_html_e( 'From the last X days (0 = all time):', 'mywiki' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'days' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'days' ) ); ?>" type="number" min="0" max="365" step="1" value="<?php echo esc_attr( $days ); ?>" size="3">
		</p>
		<?php
	}

	public function update( $new, $old ) {
		return array(
			'title' => sanitize_text_field( $new['title'] ),
			'count' => absint( $new['count'] ),
			'days'  => absint( $new['days'] ),
		);
	}
}

/**
 * Recent Articles widget.
 */
class MyWiki_Recent_Posts_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'mywiki_recent_posts',
			__( 'MyWiki — Recent Articles', 'mywiki' ),
			array(
				'description' => __( 'Most recent published articles.', 'mywiki' ),
				'classname'   => 'widget_mywiki_recent',
			)
		);
	}

	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Articles', 'mywiki' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;

		$q = new WP_Query( array(
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );

		if ( ! $q->have_posts() ) {
			return;
		}

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		echo '<ul class="mw-widget-list mw-widget-recent">';
		while ( $q->have_posts() ) {
			$q->the_post();
			?>
			<li class="mw-widget-item">
				<a href="<?php the_permalink(); ?>" class="mw-widget-link">
					<span class="mw-widget-title"><?php the_title(); ?></span>
					<span class="mw-widget-meta"><?php echo esc_html( get_the_date() ); ?></span>
				</a>
			</li>
			<?php
		}
		echo '</ul>';
		wp_reset_postdata();

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Recent Articles', 'mywiki' );
		$count = isset( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'mywiki' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of articles:', 'mywiki' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="20" step="1" value="<?php echo esc_attr( $count ); ?>" size="3">
		</p>
		<?php
	}

	public function update( $new, $old ) {
		return array(
			'title' => sanitize_text_field( $new['title'] ),
			'count' => absint( $new['count'] ),
		);
	}
}

/**
 * Register widgets.
 */
function mywiki_register_widgets() {
	register_widget( 'MyWiki_Popular_Posts_Widget' );
	register_widget( 'MyWiki_Recent_Posts_Widget' );
}
add_action( 'widgets_init', 'mywiki_register_widgets' );
