<?php
/**
 * This file contains filters and actions for various purpose
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

// Hide admin toolbar.
// add_filter( 'show_admin_bar', '__return_false' );.

// Disable the sitemaps feature - /wp-sitemap.xml.
add_filter( 'wp_sitemaps_enabled', '__return_false' );

/**
 * Adds support for excerpts to the 'page' post type
 */
add_post_type_support( 'page', 'excerpt' );

/**
 * Adds support for post thumbnails (featured images)
 */
add_theme_support( 'post-thumbnails' );

/**
 * Adds support for title
 */
add_theme_support( 'title-tag' );


/**
 * Filter callback to set JPEG image quality to 100.
 *
 * This function is used as a callback for the 'jpeg_quality' filter hook.
 * It sets the quality of JPEG images to 100.
 *
 * @param int $arg The current JPEG quality level.
 * @return int The updated JPEG quality level (100).
 */
/**
 * JPEG quality for image processing.
 */
if ( ! defined( 'SKEL_JPEG_QUALITY' ) ) {
	define( 'SKEL_JPEG_QUALITY', 100 );
}

add_filter(
	'jpeg_quality',
	function () {
		return SKEL_JPEG_QUALITY;
	}
);


/**
 * Add attributes to enqueued styles.
 *
 * This function modifies the attributes of enqueued stylesheets. Note: Do not use with Autoptimizer
 * or any contact plugin.
 *
 * DO NOT USE with Autoptimizer or any contact plugin
 *
 * @param string $tag    The HTML link tag for the enqueued style.
 * @param string $handle The handle of the enqueued style.
 * @return string Modified HTML link tag with additional attributes.
 */
function skel_add_style_attribute( string $tag, string $handle ): string {
	if ( 'google-fonts' !== $handle ) {
		return $tag;
	}
	// phpcs:ignore -- Disable enqueue script warning
	return str_replace( " rel='stylesheet'", " rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $tag );
}
add_filter( 'style_loader_tag', 'skel_add_style_attribute', 10, 2 );


/**
 * Remove jQuery Migrate script.
 *
 * The jQuery Migrate is not useful if autoptimize for JS with concatenation is enabled.
 * If any jQuery-dependent script is loaded at the top, then jQuery is forced by WordPress
 * to load at the top, e.g., Gravity Forms.
 *
 * @param WP_Scripts $scripts The WP_Scripts object.
 */
function remove_jquery_migrate(): void {
	if ( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', false, array( 'jquery-core' ), null, false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
}
add_action( 'wp_enqueue_scripts', 'remove_jquery_migrate', 1 );


/**
 * Remove version query string from styles and scripts.
 *
 * This function removes the version query string (?ver=) from enqueued
 * style and script URLs to improve caching.
 *
 * @param string $src The source URL of the enqueued style or script.
 * @return string The modified source URL without the version query string.
 */
function skel_remove_cssjs_ver( string $src ): string {
	// Keep ?ver= for theme files so filemtime() cache-busting works.
	if ( false !== strpos( $src, get_template_directory_uri() ) ) {
		return $src;
	}
	if ( false !== strpos( $src, '?ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}

add_filter( 'style_loader_src', 'skel_remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'skel_remove_cssjs_ver', 10, 2 );


/**
 * Limit search results to posts only.
 *
 * This function modifies the main query to limit search results to the 'post' post type.
 *
 * @param WP_Query $query The main query.
 * @return WP_Query Modified query.
 */
function searchfilter( WP_Query $query ): void {
	if ( $query->is_search() && ! is_admin() ) {
		$query->set( 'post_type', array( 'post' ) );
	}
}

add_action( 'pre_get_posts', 'searchfilter' );


/**
 * Theme setup function for localization.
 *
 * Registers the theme's text domain 'skel' for translation and
 * loads the translation files from the '/lang' directory within the theme.
 */
function skel_load_theme_textdomain() {
	load_theme_textdomain( 'skel', get_template_directory() . '/lang' );
}
add_action( 'init', 'skel_load_theme_textdomain' );


/**
 * Adds preload hints to the response headers for specified CSS files.
 *
 * This function adds HTTP Link headers to preload specified CSS files.
 * Preloading helps the browser prioritize fetching these resources, improving page load performance.
 *
 * The 'send_headers' action hook ensures these headers are included in the HTTP response.
 */
// function hints() {
// header( 'link:' . get_template_directory() . '/style.css; rel=preload' );
// }
// add_action( 'send_headers', 'hints' );
// .

// ! REQUIRED - Do not edit below.
/**
 * Wrap embedded oEmbed HTML in a responsive container.
 *
 * This function adds a responsive wrapper around the oEmbed HTML to ensure
 * that embedded content is displayed responsively.
 *
 * @param string $html    The oEmbed HTML.
 * @return string Modified oEmbed HTML with responsive wrapper.
 */
function skel_embed_oembed_html( string $html ): string {
	return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
}

add_filter( 'embed_oembed_html', 'skel_embed_oembed_html', 99 );


/**
 * Add post type and slug to the body class.
 *
 * This function adds a class to the body element that includes the post type and post slug
 * of the current post.
 *
 * @param array $classes An array of body classes.
 * @return array Modified array of body classes.
 */
function skel_add_slug_body_class( array $classes ): array {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}

	return $classes;
}

add_filter( 'body_class', 'skel_add_slug_body_class' );



/**
 * Custom login error message.
 *
 * This function modifies the default WordPress login error message to prevent
 * revealing details about login failures.
 *
 * @return string Custom login error message.
 */
function skel_custom_wordpress_errors(): string {
	return 'Something is wrong!';
}

add_filter( 'login_errors', 'skel_custom_wordpress_errors' );


/**
 * Change the default excerpt length.
 *
 * This function modifies the default excerpt length to 150 words.
 *
 * @return int Custom excerpt length.
 */
/**
 * Default excerpt word count.
 */
if ( ! defined( 'SKEL_EXCERPT_LENGTH' ) ) {
	define( 'SKEL_EXCERPT_LENGTH', 150 );
}

function skel_get_the_excerpt_length(): int {
	return SKEL_EXCERPT_LENGTH;
}

add_filter( 'excerpt_length', 'skel_get_the_excerpt_length' );


/**
 * Add ellipsis at the end of the excerpt.
 *
 * This function modifies the excerpt "more" string to display an ellipsis.
 *
 * @return string Modified "more" string.
 */
function skel_get_the_excerpt_more(): string {
	return '... ';
}

add_filter( 'excerpt_more', 'skel_get_the_excerpt_more' );


/**
 * Automatically insert preselected blocks into new posts of the 'custom_type' custom post type.
 *
 * This function hooks into the 'wp_insert_post' action to add a predefined set of blocks
 * to the post content when a new post of type 'accommodation' is created.
 *
 * @param int     $post_id The ID of the post being created or updated.
 * @param WP_Post $post    The post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function skel_create_template_with_preselected_blocks( $post_id, $post, $update ) {
	if ( 'custom_type' !== get_post_type( $post_id ) || $update ) {
		return;
	}

	$default_blocks = array(
		array(
			'blockName' => 'acf/text-image-video',
			'attrs'     => array( 'content' => 'This is a preselected paragraph block.' ),
		),
		array(
			'blockName' => 'acf/scroll-navigation',
			'attrs'     => array( 'content' => 'This is a preselected heading block.' ),
		),
	);

	$default_content = '';
	foreach ( $default_blocks as $block ) {
		$default_content .= serialize_block( $block );
	}

	// Temporarily unhook to prevent infinite recursion during wp_update_post.
	remove_action( 'wp_insert_post', 'skel_create_template_with_preselected_blocks', 10 );

	wp_update_post(
		array(
			'ID'           => $post_id,
			'post_content' => $default_content,
		)
	);

	add_action( 'wp_insert_post', 'skel_create_template_with_preselected_blocks', 10, 3 );
}
add_action( 'wp_insert_post', 'skel_create_template_with_preselected_blocks', 10, 3 );

/**
 * Update default image link type option on theme activation.
 */
add_action(
	'after_switch_theme',
	function () {
		update_option( 'image_default_link_type', 'none' );
	}
);


/**
 * Process gradient section markers in page content.
 *
 * Finds gradient-marker-start / gradient-marker-end block pairs and wraps
 * all content between them in a div with the configured gradient background.
 *
 * @param string $content The post content.
 * @return string Modified content with gradient section wrappers.
 */
function skel_process_gradient_sections( string $content ): string {
	if ( false === strpos( $content, 'gradient-marker-start' ) ) {
		return $content;
	}

	return preg_replace_callback(
		'#(<div[^>]*class="gradient-marker-start"[^>]*>[\s\S]*?</div>)([\s\S]*?)(<div[^>]*class="gradient-marker-end"[^>]*>[\s\S]*?</div>)#',
		function ( $matches ) {
			$start_marker  = $matches[1];
			$inner_content = trim( $matches[2] );

			preg_match( '/data-gradient-type="([^"]*)"/', $start_marker, $type_m );
			preg_match( '/data-gradient-angle="([^"]*)"/', $start_marker, $angle_m );
			preg_match( '/data-gradient-stops="([^"]*)"/', $start_marker, $stops_m );
			preg_match( '/data-gradient-custom="([^"]*)"/', $start_marker, $custom_m );

			$type       = $type_m[1] ?? 'linear';
			$angle      = $angle_m[1] ?? '135';
			$stops_json = html_entity_decode( $stops_m[1] ?? '[]' );
			$custom     = html_entity_decode( $custom_m[1] ?? '' );

			if ( 'custom' === $type ) {
				if ( empty( $custom ) ) {
					return $inner_content;
				}
				$gradient = $custom;
			} else {
				$stops = json_decode( $stops_json, true ) ?: array();
				if ( count( $stops ) < 2 ) {
					return $inner_content;
				}

				$stop_strings = array_map(
					function ( $stop ) {
						$color    = $stop['color'] ?? '';
						$position = $stop['position'] ?? '';
						return '' !== (string) $position ? "{$color} {$position}%" : $color;
					},
					$stops
				);

				$stop_list = implode( ', ', $stop_strings );
				$gradient  = 'radial' === $type
					? "radial-gradient(circle, {$stop_list})"
					: "linear-gradient({$angle}deg, {$stop_list})";
			}

			return '<div class="gradient-section" style="background: ' . esc_attr( $gradient ) . '">' . $inner_content . '</div>';
		},
		$content
	);
}

add_filter( 'the_content', 'skel_process_gradient_sections', 20 );
