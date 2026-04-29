<?php
/**
 * Enqueue scripts
 *
 * It will enqueue script and styles
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

if ( is_admin() ) {
	return;
}

/**
 * Inline critical CSS in the head for faster rendering.
 *
 * @since 1.0.0
 *
 * @return void
 */
function skel_inline_critical_css(): void {
	$critical_css_path = get_template_directory() . '/critical.css';

	if ( file_exists( $critical_css_path ) ) {
		$critical_css = file_get_contents( $critical_css_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( false !== $critical_css ) {
			// Strip tags to remove any HTML, then output inside <style> — CSS context, not HTML.
			$safe_css = wp_strip_all_tags( $critical_css );
			echo '<style id="critical-css">' . $safe_css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted local file, tags already stripped
		}
	}
}
add_action( 'wp_head', 'skel_inline_critical_css', 1 );

/**
 * Conditionally enqueue ACF block assets.
 *
 * Checks which ACF blocks are present on the current page and
 * enqueues only their CSS/JS files for optimal performance.
 *
 * @since 1.0.0
 *
 * @return void
 */
function skel_enqueue_block_assets(): void {
	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return;
	}

	$config_file = get_template_directory() . '/blocks/config.php';
	if ( ! file_exists( $config_file ) ) {
		return;
	}

	include $config_file;

	if ( empty( $block_types ) || ! is_array( $block_types ) ) {
		return;
	}

	$blocks_dir = get_template_directory() . '/blocks';
	$blocks_uri = get_template_directory_uri() . '/blocks';

	foreach ( $block_types as $block_name ) {
		$block_slug     = sanitize_title( $block_name );
		$block_name_acf = 'acf/' . $block_slug;

		if ( ! has_block( $block_name_acf, $post_id ) ) {
			continue;
		}

		$css_path = "{$blocks_dir}/{$block_slug}/{$block_slug}.css";
		$js_path  = "{$blocks_dir}/{$block_slug}/{$block_slug}.js";

		if ( file_exists( $css_path ) ) {
			wp_enqueue_style(
				"block-{$block_slug}",
				"{$blocks_uri}/{$block_slug}/{$block_slug}.css",
				array(),
				filemtime( $css_path )
			);
		}

		if ( file_exists( $js_path ) ) {
			wp_enqueue_script(
				"block-{$block_slug}",
				"{$blocks_uri}/{$block_slug}/{$block_slug}.js",
				array(),
				filemtime( $js_path ),
				true
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'skel_enqueue_block_assets' );

/**
 * Enqueue and register theme scripts and styles.
 *
 * Loads theme stylesheets and JS files, versioned automatically
 * using their file modification times.
 *
 * @since 1.0.0
 *
 * @return void
 */
function skel_enqueue_scripts(): void {
	// wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Montserrat&display=swap', array(), '1.0.0', 'all' );

	// Remove jquery — keep it when Gravity Forms is needed on this page.
	// if ( ! has_block( 'acf/contact-page' ) ) {
		wp_dequeue_script( 'jquery' );
		wp_deregister_script( 'jquery' );
	// }

	$critical_css_path = get_template_directory() . '/critical.css';
	$has_critical_css  = file_exists( $critical_css_path );

	wp_enqueue_style(
		'skel-style',
		get_stylesheet_uri(),
		array(),
		filemtime( get_template_directory() . '/style.css' ),
		$has_critical_css ? 'print' : 'all'
	);

	if ( $has_critical_css ) {
		wp_style_add_data( 'skel-style', 'onload', "this.media='all'" );
	}

	// wp_style_add_data( 'skel-style', 'rtl', 'replace' );

	wp_enqueue_script(
		'skel-swiper',
		get_template_directory_uri() . '/assets/js/swiper-bundle.js',
		array(),
		filemtime( get_template_directory() . '/assets/js/swiper-bundle.js' ),
		true
	);

	wp_enqueue_script(
		'skel-plugins',
		get_template_directory_uri() . '/assets/js/plugins.js',
		array(),
		filemtime( get_template_directory() . '/assets/js/plugins.js' ),
		true
	);

	wp_enqueue_script(
		'skel-custom',
		get_template_directory_uri() . '/assets/js/custom.js',
		array( 'skel-plugins' ),
		filemtime( get_template_directory() . '/assets/js/custom.js' ),
		true
	);

	// wp_localize_script(
	// 'skel-plugins',
	// 'localize_var',
	// array(
	// 'adminUrl' => admin_url( 'admin-ajax.php' ),
	// )
	// );
}
add_action( 'wp_enqueue_scripts', 'skel_enqueue_scripts' );

/**
 * Modify script tags to add defer, async, or type="module" attributes.
 *
 * @param string $tag    The script tag for the enqueued script.
 * @param string $handle The handle of the enqueued script.
 * @return string Modified script tag with the added attributes.
 */
function skel_modify_script_attributes( $tag, $handle ) {
	$defer = array(
		// uncomment for production as it doesn't work with Query monitor
		// 'jquery',
		// 'jquery-core',
		'skel-swiper',
		'skel-plugins',
		'skel-custom',
	);
	$async = array(
		// 'skel-lottie-player',
	);
	$priority_low = array(
		// 'skel-lottie-player',
	);
	$priority_high = array(
		// 'skel-lottie-player',
	);
	$modules = array(
		// 'skel-lottie-player',
	);

	if ( in_array( $handle, $defer, true ) || str_starts_with( $handle, 'block-' ) ) {
		$tag = str_replace( ' src', ' defer="defer" src', $tag );
	}

	if ( in_array( $handle, $async, true ) ) {
		$tag = str_replace( ' src', ' async="async" src', $tag );
	}

	if ( in_array( $handle, $priority_low, true ) ) {
		$tag = str_replace( ' src', ' fetchpriority="low" src', $tag );
	}

	if ( in_array( $handle, $priority_high, true ) ) {
		$tag = str_replace( ' src', ' fetchpriority="high" src', $tag );
	}

	if ( in_array( $handle, $modules, true ) ) {
		$tag = str_replace( ' type="text/javascript"', ' type="module"', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'skel_modify_script_attributes', 10, 2 );
