<?php
/**
 * Generator for Block SCSS files.
 *
 * @package Skeleton
 */

/**
 * Create SCSS file for a block.
 *
 * @param WP_Filesystem_Base $wp_filesystem The WordPress filesystem instance.
 * @param string            $slug          The sanitized block slug.
 * @param string            $block_dir     The block directory path.
 * @return void
 */
function skel_create_block_scss( $wp_filesystem, $slug, $block_dir ) {
	$sass_file_path = $block_dir . $slug . '.scss';

	if ( file_exists( $sass_file_path ) ) {
		return;
	}

	$sass_content = "@use '../../src/sass/partials/abstracts-blocks' as *;\n\n" . '.' . $slug . '-section {' . "\n\n" . '}';
	if ( ! $wp_filesystem->put_contents( $sass_file_path, $sass_content, FS_CHMOD_FILE ) ) {
		echo 'Error saving SCSS file for ' . esc_html( $slug ) . '!';
	}
}
