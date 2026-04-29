<?php
/**
 * This file contains functions useful for debugging
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Configure error logging for local WordPress development environment.
 *
 * This conditional statement sets up custom error logging when:
 * 1. WP_DEBUG_LOG is defined and set to true
 * 2. The current environment is identified as 'local'
 *
 * When these conditions are met, it redirects error logs to a custom file
 * within the current theme's directory.
 *
 * @uses wp_get_environment_type() WordPress function to determine the current environment
 * @uses WP_CONTENT_DIR WordPress constant for the absolute path to the wp-content directory
 */
if ( 'local' === wp_get_environment_type() ) {
	/**
	 * Note: WP_DEBUG and WP_DEBUG_LOG should be defined in wp-config.php
	 * before themes load. These fallbacks only work if nothing else has
	 * defined them yet, but WordPress error handling is already initialized
	 * by this point. Prefer setting these in wp-config.php directly.
	 */
	if ( ! defined( 'WP_DEBUG_LOG' ) ) {
		define( 'WP_DEBUG_LOG', WP_CONTENT_DIR . '/themes/skeleton/debug.log' );
	}

	if ( ! defined( 'WP_DEBUG' ) ) {
		define( 'WP_DEBUG', true );
	}
}


/**
 * Get list of all registered blocks and modify allowed block types.
 *
 * This code retrieves all registered block types and outputs the
 * list of block slugs for debugging purposes.
 * It also filters the allowed block types for all contexts by calling
 * the 'skel_allowed_block_types' function.
 *
 * @return array
 */
function skel_list_block_types(): array {
	// Retrieve all registered block types.
	$block_types = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );

	// Output the list of registered block slugs for debugging purposes.
	return $block_types;
}


/**
 * Hooks into the 'wp_print_styles' action to list all enqueued styles,
 * but only when a specific query parameter (e.g., 'debug_styles') is present.
 *
 * @return void
 */
function skel_list_enqueued_styles() {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && isset( $_GET['debug_styles'] ) && 'true' === sanitize_text_field( wp_unslash( $_GET['debug_styles'] ) ) ) {
		global $wp_styles;

		// Loop through the enqueued styles and output their handles.
		foreach ( $wp_styles->queue as $handle ) {
			echo esc_html( $handle ) . '<br>';
		}
	}
}
add_action( 'wp_print_styles', 'skel_list_enqueued_styles' );


/**
 * Hooks into the 'wp_print_scripts' action to list all enqueued scripts,
 * but only when a specific query parameter (e.g., 'debug_scripts') is present.
 *
 * @return void
 */
function skel_list_enqueued_scripts() {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && isset( $_GET['debug_scripts'] ) && 'true' === sanitize_text_field( wp_unslash( $_GET['debug_scripts'] ) ) ) {
		global $wp_scripts;

		// Loop through the enqueued scripts and output their handles.
		foreach ( $wp_scripts->queue as $handle ) {
			echo esc_html( $handle ) . '<br>';
		}
	}
}
add_action( 'wp_print_scripts', 'skel_list_enqueued_scripts' );




/**
 * Displays all custom meta keys for the current singular post.
 *
 * This function checks if the current page is a singular post or page.
 * If it is, it retrieves all metadata associated with that post ID
 * and then outputs each meta key in a <pre> tag for debugging purposes.
 * It's useful for developers to inspect the custom fields attached to a post.
 *
 * @return void
 */
function skel_show_meta_keys() {
	if ( is_singular() ) {
		$post_id   = get_the_ID();
		$meta_data = get_post_meta( $post_id );

		if ( ! empty( $meta_data ) ) {
			echo '<pre>';
			echo esc_html( 'Meta keys for post ID ' . $post_id . ':' ) . '<br>';

			foreach ( $meta_data as $meta_key => $meta_value ) {
				echo esc_html( $meta_key ) . '<br>';
			}
			echo '</pre>';
		} else {
			echo '<pre>' . esc_html( 'No meta keys found for post ID ' . $post_id . ':' ) . '.</pre>';
		}
	}
}

/**
 * Debug function to display all submenu slugs under the 'Appearance' menu in the WordPress admin.
 *
 * This function is for temporary debugging purposes only. It hooks into the 'admin_menu' action
 * with a very high priority to ensure it runs after most menu items are registered.
 * It outputs the structure of the `$submenu['themes.php']` global variable,
 * which contains all submenu items of the 'Appearance' menu.
 * This is crucial for identifying the exact slug needed to remove a submenu page
 * using `remove_submenu_page()`.
 *
 * It will display a red box with the submenu data at the top of any admin page
 * and then stop further page execution using `die()`.
 *
 * @internal This function is for debugging and should be removed after use.
 *
 * @return void
 */
function debug_wp_admin_menus_slugs() {
	global $submenu; // This global variable holds all submenu items.

	echo '<div style="background: #FFF; border: 2px solid red; padding: 10px; margin: 20px; overflow: auto; max-block-size: 400px; font-family: monospace;">';
	echo '<h2>Appearance Submenus Slugs:</h2>';
	echo '<pre>';
	// The parent slug for the Appearance menu is 'themes.php'.
	if ( isset( $submenu['themes.php'] ) ) {
		echo esc_html( print_r( $submenu['themes.php'], true ) ); //phpcs:ignore
	} else {
		echo 'No submenus found for themes.php';
	}
	echo '</pre>';
	echo '</div>';

	die(); // Stops page execution to clearly show the debug output.
}
// phpcs:ignore
// add_action( 'admin_menu', 'debug_wp_admin_menus_slugs', 9999 );




/**
 * Register a custom admin menu for the Gutenberg block usage report.
 */
add_action(
	'admin_menu',
	function () {
		add_menu_page(
			'Block Usage Report',        // Page title.
			'Block Usage',               // Menu title in sidebar.
			'manage_options',            // Capability (admin only).
			'block-usage-report',        // Menu slug (used in URL).
			'print_block_usage_report',  // Callback function to render content.
			'dashicons-screenoptions',   // Icon.
			99                           // Position.
		);
	}
);

/**
 * Display a formatted report of Gutenberg block usage across posts/pages.
 *
 * Outputs an HTML structure showing which blocks are used in each post,
 * including the post title and ID.
 */
function print_block_usage_report() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'skel' ) );
	}

	$usage = get_blocks_by_page();

	echo '<div class="wrap"><h1>Gutenberg Block Usage</h1>';

	if ( empty( $usage ) ) {
		echo '<p>No blocks found in any page or post.</p></div>';
		return;
	}

	foreach ( $usage as $post_id => $blocks ) {
		$post_title = get_the_title( $post_id );
		$post_url   = get_edit_post_link( $post_id );

		echo '<div style="margin-bottom: 20px;">';
		echo '<strong><a href="' . esc_url( $post_url ) . '" target="_blank">' . esc_html( $post_title ) . '</a></strong>';
		echo ' (ID: ' . intval( $post_id ) . ')';
		echo '<ul>';

		foreach ( array_unique( $blocks ) as $block_name ) {
			echo '<li>' . esc_html( $block_name ) . '</li>';
		}

		echo '</ul></div>';
	}

	echo '</div>';
}

/**
 * Get a list of Gutenberg blocks used on each page or post.
 *
 * This function loops through all posts of given post types,
 * parses the block content using `parse_blocks()`,
 * and collects block names used in each post.
 *
 * @return array Associative array of post ID => array of block names.
 */
function get_blocks_by_page() {
	$cache_key = 'skel_block_usage_report';
	$usage     = get_transient( $cache_key );

	if ( false !== $usage ) {
		return $usage;
	}

	$block_usage = array();

	$args = array(
		'post_type'              => array( 'page', 'post' ), // Add custom post types here if needed.
		'post_status'            => 'publish',
		'posts_per_page'         => -1,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			$blocks = parse_blocks( $post->post_content );

			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) ) {
					$block_usage[ $post->ID ][] = $block['blockName'];
				}
			}
		}
	}

	set_transient( $cache_key, $block_usage, HOUR_IN_SECONDS );

	return $block_usage;
}
