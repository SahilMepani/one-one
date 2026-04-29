<?php
/**
 * Generator for Block PHP files.
 *
 * @package Skeleton
 */

/**
 * Create PHP file for a block.
 *
 * @param WP_Filesystem_Base $wp_filesystem The WordPress filesystem instance.
 * @param string            $block_name    The original block name.
 * @param string            $slug          The sanitized block slug.
 * @param string            $block_dir     The block directory path.
 * @param string            $template_file The path to the template file.
 * @return void
 */
function skel_create_block_php( $wp_filesystem, $block_name, $slug, $block_dir, $template_file ) {
	$php_file_path = $block_dir . $slug . '.php';

	if ( file_exists( $php_file_path ) ) {
		return;
	}

	$php_content = $wp_filesystem->get_contents( $template_file );
	if ( false === $php_content ) {
		echo 'Error reading template file!';
		return;
	}

	$php_content = str_replace( 'blank-section', $slug . '-section', $php_content );
	$php_content = str_replace( 'Blank ACF block', $block_name . ' ACF Block', $php_content );

	if ( ! $wp_filesystem->put_contents( $php_file_path, $php_content, FS_CHMOD_FILE ) ) {
		echo 'Error saving PHP file for ' . esc_html( $block_name ) . '!';
	}
}
