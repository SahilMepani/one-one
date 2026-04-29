<?php // phpcs:ignore file comment
/**
 * Helper function to delete unnecessary ACF block folders.
 *
 * This function checks the existing block folders against the $block_types values,
 * and deletes any folders that are no longer needed, except for the `blank` folder.
 *
 * @param array $block_types An array of current block names.
 * @return void
 */
function skel_delete_unwanted_acf_block_files( array $block_types ): void {
	$wp_filesystem = skel_init_filesystem();

	if ( ! $wp_filesystem ) {
		return;
	}

	$blocks_directory = get_template_directory() . '/blocks/';

	if ( ! $wp_filesystem->is_dir( $blocks_directory ) ) {
		return;
	}

	$existing_folders = glob( $blocks_directory . '*', GLOB_ONLYDIR );

	if ( ! $existing_folders ) {
		return;
	}

	$current_blocks = array_map(
		function ( $block ) {
			return skel_get_block_slug( $block );
		},
		$block_types
	);

	foreach ( $existing_folders as $folder ) {
		$folder_name = basename( $folder );

		if ( 'blank' === $folder_name ) {
			continue;
		}

		if ( ! in_array( $folder_name, $current_blocks, true ) ) {
			if ( ! $wp_filesystem->delete( $folder, true ) ) {
				error_log( sprintf( 'Skeleton: Error deleting block folder: %s', $folder ) );
			}
		}
	}
}
