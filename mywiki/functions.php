<?php
/**
 * MyWiki theme functions and definitions
 *
 * @package MyWiki
 * @since 5.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'MYWIKI_VERSION' ) ) {
	define( 'MYWIKI_VERSION', '5.0.0' );
}

/**
 * Theme setup.
 */
function mywiki_setup() {
	// Translation.
	load_theme_textdomain( 'mywiki', get_template_directory() . '/languages' );

	// Title tag and feeds.
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );

	// Featured images.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 675, true );
	add_image_size( 'mywiki-card', 600, 400, true );
	add_image_size( 'mywiki-thumb', 120, 120, true );

	// HTML5.
	add_theme_support(
		'html5',
		array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	// Custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Custom background.
	add_theme_support(
		'custom-background',
		apply_filters(
			'mywiki_custom_background_args',
			array(
				'default-color' => 'fafaf7',
			)
		)
	);

	// Editor.
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'css/editor-style.css' );

	// Selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Menus.
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'mywiki' ),
			'footer'  => __( 'Footer Menu', 'mywiki' ),
		)
	);

	// Content width.
	$GLOBALS['content_width'] = 760;
}
add_action( 'after_setup_theme', 'mywiki_setup' );

/**
 * Enqueue styles and scripts.
 */
function mywiki_enqueue_assets() {
	$ver = MYWIKI_VERSION;

	// Preconnect to Google Fonts (printed via wp_resource_hints).
	$default_fonts_url = 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap';
	$fonts_url         = apply_filters( 'mywiki_fonts_url', $default_fonts_url );
	if ( $fonts_url ) {
		wp_enqueue_style(
			'mywiki-fonts',
			$fonts_url,
			array(),
			null
		);
	}

	// Theme stylesheet.
	wp_enqueue_style(
		'mywiki-style',
		get_stylesheet_uri(),
		array( 'mywiki-fonts' ),
		$ver
	);

	// Theme script.
	wp_enqueue_script(
		'mywiki-script',
		get_template_directory_uri() . '/js/mywiki.js',
		array(),
		$ver,
		true
	);

	wp_localize_script(
		'mywiki-script',
		'mywikiData',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'mywiki_search' ),
			'searchUrl' => home_url( '/?s=' ),
			'minLen'    => 2,
			'strings'   => array(
				'placeholder'  => __( 'Search documentation, articles, FAQs…', 'mywiki' ),
				'noResults'    => __( 'No results for', 'mywiki' ),
				'tryDifferent' => __( 'Try different keywords, or browse by topic.', 'mywiki' ),
				'startTyping'  => __( 'Start typing to search…', 'mywiki' ),
				'tipPrefix'    => __( 'Tip: prefix with # to search by tag.', 'mywiki' ),
				'searching'    => __( 'Searching…', 'mywiki' ),
				'select'       => __( 'open', 'mywiki' ),
				'close'        => __( 'close', 'mywiki' ),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Allow Pro plugin (or any extension) to inject runtime CSS — fonts, colors, etc.
	$dynamic_css = apply_filters( 'mywiki_dynamic_css', '' );
	if ( $dynamic_css ) {
		wp_add_inline_style( 'mywiki-style', $dynamic_css );
	}
}
add_action( 'wp_enqueue_scripts', 'mywiki_enqueue_assets' );

/**
 * Add resource hints for performance.
 */
function mywiki_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'mywiki_resource_hints', 10, 2 );

/**
 * Register sidebars.
 */
function mywiki_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Main Sidebar', 'mywiki' ),
			'id'            => 'sidebar1',
			'description'   => __( 'Appears on posts and archive pages.', 'mywiki' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="mw-widget-title">',
			'after_title'   => '</h3>',
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number */
				'name'          => sprintf( __( 'Footer Column %d', 'mywiki' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => __( 'Appears in the site footer.', 'mywiki' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="mw-widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'mywiki_widgets_init' );

/**
 * On first activation only, populate the footer widget areas with sensible
 * defaults so the footer doesn't ship empty. Uses WP core widgets.
 *
 * Runs once. Skipped if the user has already configured any footer widgets
 * (so reactivating the theme later never overwrites their setup) or if the
 * one-shot option flag is already set.
 *
 * @return void
 */
function mywiki_install_default_widgets() {

	// Only ever run once per site.
	if ( get_option( 'mywiki_default_widgets_installed' ) ) {
		return;
	}

	$sidebars_widgets = get_option( 'sidebars_widgets', array() );
	if ( ! is_array( $sidebars_widgets ) ) {
		$sidebars_widgets = array();
	}

	// Respect any existing user choices — only fill empty areas.
	$user_already_configured = (
		( ! empty( $sidebars_widgets['footer-1'] ) && is_array( $sidebars_widgets['footer-1'] ) ) ||
		( ! empty( $sidebars_widgets['footer-2'] ) && is_array( $sidebars_widgets['footer-2'] ) ) ||
		( ! empty( $sidebars_widgets['footer-3'] ) && is_array( $sidebars_widgets['footer-3'] ) )
	);

	if ( $user_already_configured ) {
		update_option( 'mywiki_default_widgets_installed', 1 );
		return;
	}

	// Footer 1 — Categories widget (top categories).
	$cat_widgets = get_option( 'widget_categories', array() );
	if ( ! is_array( $cat_widgets ) ) {
		$cat_widgets = array();
	}
	$next = mywiki_next_widget_id( $cat_widgets );
	$cat_widgets[ $next ] = array(
		'title'        => __( 'Browse', 'mywiki' ),
		'count'        => 0,
		'hierarchical' => 0,
		'dropdown'     => 0,
	);
	$cat_widgets['_multiwidget'] = 1;
	update_option( 'widget_categories', $cat_widgets );
	$sidebars_widgets['footer-1'] = array( 'categories-' . $next );

	// Footer 2 — Recent Posts widget.
	$recent_widgets = get_option( 'widget_recent-posts', array() );
	if ( ! is_array( $recent_widgets ) ) {
		$recent_widgets = array();
	}
	$next = mywiki_next_widget_id( $recent_widgets );
	$recent_widgets[ $next ] = array(
		'title'     => __( 'Latest articles', 'mywiki' ),
		'number'    => 5,
		'show_date' => 0,
	);
	$recent_widgets['_multiwidget'] = 1;
	update_option( 'widget_recent-posts', $recent_widgets );
	$sidebars_widgets['footer-2'] = array( 'recent-posts-' . $next );

	// Footer 3 — Pages widget (resources).
	$page_widgets = get_option( 'widget_pages', array() );
	if ( ! is_array( $page_widgets ) ) {
		$page_widgets = array();
	}
	$next = mywiki_next_widget_id( $page_widgets );
	$page_widgets[ $next ] = array(
		'title'   => __( 'Resources', 'mywiki' ),
		'sortby'  => 'menu_order',
		'exclude' => '',
	);
	$page_widgets['_multiwidget'] = 1;
	update_option( 'widget_pages', $page_widgets );
	$sidebars_widgets['footer-3'] = array( 'pages-' . $next );

	update_option( 'sidebars_widgets', $sidebars_widgets );
	update_option( 'mywiki_default_widgets_installed', 1 );
}
add_action( 'after_switch_theme', 'mywiki_install_default_widgets' );

/**
 * On first activation only, create a "Home" page using the Wiki template
 * and set it as the static front page if the user has not already chosen one.
 *
 * Runs once. Skipped if:
 *   - The one-shot flag is already set, or
 *   - The site already has a static front page configured (we never override
 *     the user's existing choice).
 *
 * @return void
 */
function mywiki_install_default_home_page() {

	// Only ever run once per site.
	if ( get_option( 'mywiki_default_home_installed' ) ) {
		return;
	}

	// Respect any existing user choice — don't override an already-set front page.
	if ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) > 0 ) {
		update_option( 'mywiki_default_home_installed', 1 );
		return;
	}

	// If a page with the same slug already exists, reuse it.
	$existing = get_page_by_path( 'home' );

	if ( $existing ) {
		$home_id = (int) $existing->ID;
		// Make sure it has the wiki template applied.
		update_post_meta( $home_id, '_wp_page_template', 'template-wiki.php' );
	} else {
		$home_id = wp_insert_post(
			array(
				'post_title'   => __( 'Home', 'mywiki' ),
				'post_name'    => 'home',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			),
			true
		);
		if ( is_wp_error( $home_id ) || ! $home_id ) {
			// Don't set the flag — let the next activation try again.
			return;
		}
		update_post_meta( $home_id, '_wp_page_template', 'template-wiki.php' );
	}

	// Set as front page.
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );

	update_option( 'mywiki_default_home_installed', 1 );
}
add_action( 'after_switch_theme', 'mywiki_install_default_home_page' );

/**
 * Helper: find the next available numeric ID for a multi-widget option array.
 *
 * @param array $widgets Existing widget settings keyed by integer ID.
 * @return int
 */
function mywiki_next_widget_id( $widgets ) {
	$ids = array();
	foreach ( (array) $widgets as $key => $value ) {
		if ( is_int( $key ) ) {
			$ids[] = $key;
		}
	}
	return $ids ? max( $ids ) + 1 : 2;
}

/**
 * AJAX search handler.
 *
 * Returns grouped results (matched articles) as JSON for the modern search panel.
 */
function mywiki_ajax_search() {
	check_ajax_referer( 'mywiki_search', 'nonce' );

	$q = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';
	$q = trim( $q );

	if ( strlen( $q ) < 2 ) {
		wp_send_json_success( array( 'groups' => array(), 'total' => 0, 'query' => $q ) );
	}

	$is_tag = ( 0 === strpos( $q, '#' ) );

	$args = array(
		'posts_per_page' => 12,
		'post_status'    => 'publish',
		'post_type'      => array( 'post', 'page' ),
		'orderby'        => $is_tag ? 'date' : 'relevance',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	);

	if ( $is_tag ) {
		$tag = sanitize_title( substr( $q, 1 ) );
		if ( ! $tag ) {
			wp_send_json_success( array( 'groups' => array(), 'total' => 0, 'query' => $q ) );
		}
		$args['tag'] = $tag;
	} else {
		$args['s'] = $q;
	}

	$query = new WP_Query( $args );

	$groups = array(
		'post' => array(
			'label' => __( 'Articles', 'mywiki' ),
			'icon'  => 'doc',
			'items' => array(),
		),
		'page' => array(
			'label' => __( 'Pages', 'mywiki' ),
			'icon'  => 'book',
			'items' => array(),
		),
	);

	$total = 0;

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();
			$type    = get_post_type();
			if ( ! isset( $groups[ $type ] ) ) {
				continue;
			}

			$crumbs = '';
			if ( 'post' === $type ) {
				$cats = get_the_category( $post_id );
				if ( $cats ) {
					$crumbs = $cats[0]->name;
				}
			} elseif ( 'page' === $type ) {
				$ancestors = get_post_ancestors( $post_id );
				if ( $ancestors ) {
					$ancestors = array_reverse( $ancestors );
					$titles    = array();
					foreach ( $ancestors as $aid ) {
						$titles[] = get_the_title( $aid );
					}
					$crumbs = implode( ' / ', $titles );
				}
			}

			$groups[ $type ]['items'][] = array(
				'title'   => wp_strip_all_tags( get_the_title() ),
				'url'     => get_permalink(),
				'excerpt' => wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 18, '…' ),
				'crumbs'  => $crumbs,
				'icon'    => mywiki_icon( $groups[ $type ]['icon'], 16 ),
			);
			$total++;
		}
	}
	wp_reset_postdata();

	// Drop empty groups & resequence with localized labels.
	$out = array();
	foreach ( $groups as $g ) {
		if ( ! empty( $g['items'] ) ) {
			$out[] = array(
				'label' => $g['label'],
				'items' => $g['items'],
			);
		}
	}

	wp_send_json_success(
		array(
			'groups' => $out,
			'total'  => $total,
			'query'  => $q,
		)
	);
}
add_action( 'wp_ajax_mywiki_search', 'mywiki_ajax_search' );
add_action( 'wp_ajax_nopriv_mywiki_search', 'mywiki_ajax_search' );

/**
 * Improve excerpt: replace [...] with a clean ellipsis.
 */
function mywiki_excerpt_more( $more ) {
	return '…';
}
add_filter( 'excerpt_more', 'mywiki_excerpt_more' );

/**
 * Excerpt length.
 */
function mywiki_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'mywiki_excerpt_length', 999 );

/**
 * Custom logo class for our markup.
 */
function mywiki_custom_logo_class( $html ) {
	return str_replace( 'class="custom-logo-link"', 'class="custom-logo-link mw-brand"', $html );
}
add_filter( 'get_custom_logo', 'mywiki_custom_logo_class' );

/**
 * Add a body class indicating the active layout type.
 */
function mywiki_body_classes( $classes ) {
	if ( ! is_active_sidebar( 'sidebar1' ) || is_page_template( array( 'template-wiki.php', 'full-width-template.php' ) ) || is_front_page() ) {
		$classes[] = 'mw-no-sidebar';
	}
	if ( is_singular( array( 'post', 'page' ) ) && ! is_front_page() ) {
		$classes[] = 'mw-singular';
	}
	return $classes;
}
add_filter( 'body_class', 'mywiki_body_classes' );

/**
 * Wrap previous/next post links to fit our pagination style.
 */
function mywiki_pagination() {
	$pagination = paginate_links(
		array(
			'mid_size'  => 1,
			'end_size'  => 2,
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'type'      => 'array',
		)
	);

	if ( ! $pagination ) {
		return;
	}

	echo '<nav class="mw-pagination" role="navigation" aria-label="' . esc_attr__( 'Posts navigation', 'mywiki' ) . '">';
	foreach ( $pagination as $link ) {
		echo wp_kses_post( $link );
	}
	echo '</nav>';
}

/**
 * Render breadcrumbs (schema-ready BreadcrumbList JSON-LD also output).
 */
function mywiki_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$home = home_url( '/' );
	$crumbs = array();
	$crumbs[] = array(
		'name' => __( 'Home', 'mywiki' ),
		'url'  => $home,
	);

	if ( is_category() ) {
		$cat = get_queried_object();
		if ( $cat && $cat->parent ) {
			$ancestors = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
			foreach ( $ancestors as $aid ) {
				$ac = get_term( $aid, 'category' );
				if ( $ac && ! is_wp_error( $ac ) ) {
					$crumbs[] = array(
						'name' => $ac->name,
						'url'  => get_category_link( $ac->term_id ),
					);
				}
			}
		}
		$crumbs[] = array(
			'name' => single_cat_title( '', false ),
			'url'  => '',
		);
	} elseif ( is_tag() ) {
		$crumbs[] = array(
			/* translators: %s: tag name */
			'name' => sprintf( __( 'Tag: %s', 'mywiki' ), single_tag_title( '', false ) ),
			'url'  => '',
		);
	} elseif ( is_search() ) {
		$crumbs[] = array(
			/* translators: %s: search query */
			'name' => sprintf( __( 'Search: %s', 'mywiki' ), get_search_query() ),
			'url'  => '',
		);
	} elseif ( is_author() ) {
		$crumbs[] = array(
			/* translators: %s: author name */
			'name' => sprintf( __( 'Author: %s', 'mywiki' ), get_the_author() ),
			'url'  => '',
		);
	} elseif ( is_archive() ) {
		$crumbs[] = array(
			'name' => wp_strip_all_tags( get_the_archive_title() ),
			'url'  => '',
		);
	} elseif ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( ! empty( $cats ) ) {
			$primary = $cats[0];
			$crumbs[] = array(
				'name' => $primary->name,
				'url'  => get_category_link( $primary->term_id ),
			);
		}
		$crumbs[] = array(
			'name' => get_the_title(),
			'url'  => '',
		);
	} elseif ( is_page() ) {
		$post_id = get_the_ID();
		$ancestors = array_reverse( get_post_ancestors( $post_id ) );
		foreach ( $ancestors as $aid ) {
			$crumbs[] = array(
				'name' => get_the_title( $aid ),
				'url'  => get_permalink( $aid ),
			);
		}
		$crumbs[] = array(
			'name' => get_the_title(),
			'url'  => '',
		);
	} elseif ( is_404() ) {
		$crumbs[] = array(
			'name' => __( 'Not Found', 'mywiki' ),
			'url'  => '',
		);
	}

	if ( count( $crumbs ) < 2 ) {
		return;
	}

	echo '<nav class="mw-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'mywiki' ) . '">';
	$total = count( $crumbs );
	foreach ( $crumbs as $i => $crumb ) {
		$is_last = ( $i === $total - 1 );
		if ( $is_last ) {
			echo '<span class="current">' . esc_html( $crumb['name'] ) . '</span>';
		} else {
			echo '<a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['name'] ) . '</a>';
			echo '<span class="sep" aria-hidden="true">/</span>';
		}
	}
	echo '</nav>';

	// JSON-LD breadcrumbs.
	$jsonld = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => array(),
	);
	foreach ( $crumbs as $i => $crumb ) {
		$item = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $crumb['name'],
		);
		if ( ! empty( $crumb['url'] ) ) {
			$item['item'] = $crumb['url'];
		}
		$jsonld['itemListElement'][] = $item;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $jsonld, JSON_UNESCAPED_SLASHES ) . '</script>';
}

/**
 * Build a simple TOC from H2/H3 inside post content.
 */
function mywiki_build_toc( $content ) {
	if ( ! $content ) {
		return array( 'content' => $content, 'toc' => '' );
	}

	$toc_items = array();
	$used_ids  = array();

	$content = preg_replace_callback(
		'/<h([23])([^>]*)>(.*?)<\/h\1>/i',
		function ( $m ) use ( &$toc_items, &$used_ids ) {
			$level = intval( $m[1] );
			$attrs = $m[2];
			$text  = trim( wp_strip_all_tags( $m[3] ) );
			if ( '' === $text ) {
				return $m[0];
			}

			// Reuse existing id="..." if present.
			$id = '';
			if ( preg_match( '/\sid=["\']([^"\']+)["\']/i', $attrs, $idm ) ) {
				$id = $idm[1];
			}
			if ( '' === $id ) {
				$base = sanitize_title( $text );
				if ( '' === $base ) {
					$base = 'section';
				}
				$id = $base;
				$n  = 2;
				while ( in_array( $id, $used_ids, true ) ) {
					$id = $base . '-' . $n;
					$n++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used_ids[]  = $id;
			$toc_items[] = array(
				'id'    => $id,
				'text'  => $text,
				'level' => $level,
			);
			return '<h' . $level . $attrs . '>' . $m[3] . '</h' . $level . '>';
		},
		$content
	);

	$toc = '';
	if ( count( $toc_items ) >= 2 ) {
		$toc  = '<aside class="mw-toc-sidebar" aria-label="' . esc_attr__( 'On this page', 'mywiki' ) . '">';
		$toc .= '<div class="mw-toc">';
		$toc .= '<div class="mw-toc-title">' . esc_html__( 'On this page', 'mywiki' ) . '</div>';
		$toc .= '<ul class="mw-toc-list">';
		foreach ( $toc_items as $item ) {
			$cls  = 'toc-h' . $item['level'];
			$toc .= '<li class="' . esc_attr( $cls ) . '"><a href="#' . esc_attr( $item['id'] ) . '">' . esc_html( $item['text'] ) . '</a></li>';
		}
		$toc .= '</ul></div></aside>';
	}

	return array(
		'content' => $content,
		'toc'     => $toc,
	);
}

/**
 * Render an inline SVG icon by name.
 */
function mywiki_icon( $name, $size = 16 ) {
	$icons = array(
		'search'   => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>',
		'menu'     => '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>',
		'close'    => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
		'arrow-r'  => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
		'arrow-l'  => '<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 19"/>',
		'doc'      => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
		'folder'   => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>',
		'book'     => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
		'tag'      => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
		'clock'    => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
		'user'     => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
		'thumbs-u' => '<path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>',
		'thumbs-d' => '<path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>',
		'chevron-r'=> '<polyline points="9 18 15 12 9 6"/>',
		'chevron-d'=> '<polyline points="6 9 12 15 18 9"/>',
		'home'     => '<path d="M3 9.5L12 3l9 6.5V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9.5z"/><polyline points="9 22 9 12 15 12 15 22"/>',
		'rss'      => '<path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/>',
		'twitter'  => '<path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>',
		'facebook' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'github'   => '<path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>',
		'linkedin' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
		'youtube'  => '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/>',
		'instagram'=> '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"/>',
		'help'     => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
		'sparkle'  => '<path d="M12 3l1.9 5.6 5.6 1.9-5.6 1.9-1.9 5.6-1.9-5.6L4.5 10.5l5.6-1.9z"/>',
		'lightning'=> '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
		'check'    => '<polyline points="20 6 9 17 4 12"/>',
		'list'     => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%2$s</svg>',
		intval( $size ),
		$icons[ $name ]
	);
}

/**
 * Customizer.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Pro upsell admin page.
 */
require get_template_directory() . '/inc/pro-page.php';

/**
 * Custom widgets.
 */
require get_template_directory() . '/inc/widgets.php';
