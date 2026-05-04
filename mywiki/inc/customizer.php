<?php
/**
 * MyWiki Customizer.
 *
 * @package MyWiki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings & controls.
 */
function mywiki_customize_register( $wp_customize ) {

	/* ---------------------------------------------------------------------
	 * Title/Tagline live preview
	 * ------------------------------------------------------------------ */
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	/* ---------------------------------------------------------------------
	 * Panel: MyWiki Theme Options
	 * ------------------------------------------------------------------ */
	$wp_customize->add_panel( 'mywiki_panel', array(
		'title'    => __( 'MyWiki Theme Options', 'mywiki' ),
		'priority' => 30,
	) );

	/* =====================================================================
	 * Section: Search
	 * ================================================================== */
	$wp_customize->add_section( 'mywiki_search', array(
		'title'    => __( 'Search', 'mywiki' ),
		'panel'    => 'mywiki_panel',
		'priority' => 5,
	) );

	$pro_active = apply_filters( 'mywiki_pro_active', false );

	$search_choices = array(
		'suggestions' => __( 'Inline suggestions (free)', 'mywiki' ),
	);
	if ( $pro_active ) {
		$search_choices['modal'] = __( 'Command-K modal — Pro', 'mywiki' );
	} else {
		$search_choices['modal'] = __( 'Command-K modal — Pro (locked)', 'mywiki' );
	}

	$wp_customize->add_setting( 'mywiki_search_type', array(
		'default'           => 'suggestions',
		'sanitize_callback' => 'mywiki_sanitize_search_type',
	) );
	$wp_customize->add_control( 'mywiki_search_type', array(
		'label'       => __( 'Search Style', 'mywiki' ),
		'description' => $pro_active
			? __( 'Choose how visitors search your knowledge base.', 'mywiki' )
			: __( 'The Command-K modal is available with MyWiki Pro. Free users get the elegant inline suggestions dropdown.', 'mywiki' ),
		'section'     => 'mywiki_search',
		'type'        => 'select',
		'choices'     => $search_choices,
	) );

	$wp_customize->add_setting( 'mywiki_search_placeholder', array(
		'default'           => __( 'Search the docs…', 'mywiki' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mywiki_search_placeholder', array(
		'label'   => __( 'Search Placeholder Text', 'mywiki' ),
		'section' => 'mywiki_search',
		'type'    => 'text',
	) );

	/* =====================================================================
	 * Section: Hero
	 * ================================================================== */
	$wp_customize->add_section( 'mywiki_hero', array(
		'title' => __( 'Hero Section', 'mywiki' ),
		'panel' => 'mywiki_panel',
	) );

	$wp_customize->add_setting( 'mywiki_hero_eyebrow', array(
		'default'           => __( 'Knowledge base', 'mywiki' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mywiki_hero_eyebrow', array(
		'label'   => __( 'Eyebrow Label', 'mywiki' ),
		'section' => 'mywiki_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mywiki_hero_heading', array(
		'default'           => __( 'Everything you need,', 'mywiki' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mywiki_hero_heading', array(
		'label'       => __( 'Hero Heading', 'mywiki' ),
		'description' => __( 'The main headline. Keep it short.', 'mywiki' ),
		'section'     => 'mywiki_hero',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'mywiki_hero_italic', array(
		'default'           => __( 'beautifully indexed', 'mywiki' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mywiki_hero_italic', array(
		'label'       => __( 'Hero Italic Accent', 'mywiki' ),
		'description' => __( 'Displayed in italic blue after the main heading.', 'mywiki' ),
		'section'     => 'mywiki_hero',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'mywiki_hero_subtitle', array(
		'default'           => __( 'Browse curated guides, troubleshoot in seconds, and find the exact answer you need with intelligent search.', 'mywiki' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mywiki_hero_subtitle', array(
		'label'   => __( 'Hero Subtitle', 'mywiki' ),
		'section' => 'mywiki_hero',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'mywiki_hero_stats', array(
		'default'           => 1,
		'sanitize_callback' => 'mywiki_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'mywiki_hero_stats', array(
		'label'       => __( 'Show Article & Category Counts', 'mywiki' ),
		'description' => __( 'Display statistics chips beneath the hero search.', 'mywiki' ),
		'section'     => 'mywiki_hero',
		'type'        => 'checkbox',
	) );

	/* =====================================================================
	 * Section: Categories
	 * ================================================================== */
	$wp_customize->add_section( 'mywiki_categories', array(
		'title' => __( 'Category Grid', 'mywiki' ),
		'panel' => 'mywiki_panel',
	) );

	$wp_customize->add_setting( 'mywiki_category_title', array(
		'default'           => __( 'Browse by topic', 'mywiki' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mywiki_category_title', array(
		'label'   => __( 'Section Title', 'mywiki' ),
		'section' => 'mywiki_categories',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mywiki_category_list', array(
		'default'           => '',
		'sanitize_callback' => 'mywiki_sanitize_id_list',
	) );
	$wp_customize->add_control( 'mywiki_category_list', array(
		'label'       => __( 'Featured Category IDs', 'mywiki' ),
		'description' => __( 'Comma-separated list of category IDs to feature. Leave empty to show all top-level categories.', 'mywiki' ),
		'section'     => 'mywiki_categories',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'mywiki_category_count', array(
		'default'           => 1,
		'sanitize_callback' => 'mywiki_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'mywiki_category_count', array(
		'label'   => __( 'Show Article Counts on Category Cards', 'mywiki' ),
		'section' => 'mywiki_categories',
		'type'    => 'checkbox',
	) );

	/* =====================================================================
	 * Section: Header
	 * ================================================================== */
	$wp_customize->add_section( 'mywiki_header', array(
		'title' => __( 'Header', 'mywiki' ),
		'panel' => 'mywiki_panel',
	) );

	$wp_customize->add_setting( 'mywiki_header_cta_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'mywiki_header_cta_text', array(
		'label'       => __( 'CTA Button Text', 'mywiki' ),
		'description' => __( 'Optional call-to-action shown in the header. Leave blank to hide.', 'mywiki' ),
		'section'     => 'mywiki_header',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'mywiki_header_cta_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'mywiki_header_cta_url', array(
		'label'   => __( 'CTA Button URL', 'mywiki' ),
		'section' => 'mywiki_header',
		'type'    => 'url',
	) );

	/* =====================================================================
	 * Section: Footer
	 * ================================================================== */
	$wp_customize->add_section( 'mywiki_footer', array(
		'title' => __( 'Footer', 'mywiki' ),
		'panel' => 'mywiki_panel',
	) );

	$wp_customize->add_setting( 'mywiki_footer_copyright', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'mywiki_footer_copyright', array(
		'label'       => __( 'Footer Copyright', 'mywiki' ),
		'description' => __( 'Use {year} for current year and {site} for site name.', 'mywiki' ),
		'section'     => 'mywiki_footer',
		'type'        => 'textarea',
	) );

	/* =====================================================================
	 * Section: Social
	 * ================================================================== */
	$wp_customize->add_section( 'mywiki_social', array(
		'title' => __( 'Social Links', 'mywiki' ),
		'panel' => 'mywiki_panel',
	) );

	$social_networks = array(
		'twitter'   => __( 'X / Twitter URL', 'mywiki' ),
		'facebook'  => __( 'Facebook URL', 'mywiki' ),
		'github'    => __( 'GitHub URL', 'mywiki' ),
		'linkedin'  => __( 'LinkedIn URL', 'mywiki' ),
		'youtube'   => __( 'YouTube URL', 'mywiki' ),
		'instagram' => __( 'Instagram URL', 'mywiki' ),
	);

	foreach ( $social_networks as $key => $label ) {
		$wp_customize->add_setting( 'mywiki_social_' . $key, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'mywiki_social_' . $key, array(
			'label'   => $label,
			'section' => 'mywiki_social',
			'type'    => 'url',
		) );
	}
}
add_action( 'customize_register', 'mywiki_customize_register' );

/**
 * Sanitize checkbox.
 */
function mywiki_sanitize_checkbox( $value ) {
	return ( isset( $value ) && true == $value ) ? 1 : 0;
}

/**
 * Sanitize search type — clamp to 'suggestions' unless Pro is active.
 */
function mywiki_sanitize_search_type( $value ) {
	$pro = apply_filters( 'mywiki_pro_active', false );
	$allowed = $pro ? array( 'suggestions', 'modal' ) : array( 'suggestions' );
	return in_array( $value, $allowed, true ) ? $value : 'suggestions';
}

/**
 * Sanitize a comma-separated list of integer IDs.
 */
function mywiki_sanitize_id_list( $value ) {
	if ( empty( $value ) ) {
		return '';
	}
	$ids = array_filter( array_map( 'absint', explode( ',', $value ) ) );
	return implode( ',', $ids );
}

/**
 * Bind live preview JS for blogname/description.
 */
function mywiki_customize_preview_js() {
	wp_enqueue_script(
		'mywiki-customize-preview',
		get_template_directory_uri() . '/js/customize-preview.js',
		array( 'customize-preview' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'customize_preview_init', 'mywiki_customize_preview_js' );
