<?php //phpcs:ignore file comment
/**
 * Disable block directory
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

add_action(
	'admin_init',
	function () {
		remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
	}
);
