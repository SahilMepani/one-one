<?php
/**
 * Helper function to create .php files for ACF blocks.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

// Require the generator files.
require_once get_template_directory() . '/functions/block-generator/generate-php.php';
require_once get_template_directory() . '/functions/block-generator/generate-scss.php';
require_once get_template_directory() . '/functions/block-generator/generate-json.php';

/**
 * This function checks the $block_types values, sanitizes the block names,
 * and creates block files in the blocks/{slug}/ directory structure.
 * Only creates files if they don't exist.
 *
 * @param array $block_types An array of block names.
 * @return void
 */
function skel_create_acf_block_files( array $block_types ): void {
	// Initialize the WordPress filesystem API.
	$wp_filesystem = skel_init_filesystem();

	// Define base directory and template files.
	$blocks_base_dir = get_template_directory() . '/blocks/';
	$php_template    = $blocks_base_dir . 'blank/blank.php';
	$json_template   = $blocks_base_dir . 'blank/blank.json';

	// Bail early if template files are missing.
	if ( ! file_exists( $php_template ) || ! file_exists( $json_template ) ) {
		return;
	}

	// Loop through each block type.
	foreach ( $block_types as $block ) {
		// Sanitize the block name.
		$slug = skel_get_block_slug( $block );

		// Create block directory.
		$block_dir = $blocks_base_dir . $slug . '/';
		if ( ! file_exists( $block_dir ) ) {
			wp_mkdir_p( $block_dir );
		}

		// Generate PHP file.
		skel_create_block_php( $wp_filesystem, $block, $slug, $block_dir, $php_template );

		// Generate SCSS file.
		skel_create_block_scss( $wp_filesystem, $slug, $block_dir );

		// Generate JSON file.
		skel_create_block_json( $wp_filesystem, $block, $slug, $block_dir, $json_template );
	}
}
