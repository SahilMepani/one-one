<?php
/**
 * Whitelist all acf blocks and some core/image Gutenberg blocks
 *
 * @link https://rudrastyh.com/gutenberg/remove-default-blocks.html#allowed_block_types_all
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Filter the allowed block types for all contexts.
 *
 * This function modifies the list of allowed block types for all contexts,
 * including the block editor and block inserter.
 *
 * @param bool|string[] $allowed_block_types Array of allowed block types or true for all.
 * @param WP_Block_Editor_Context $editor_context The current block editor context.
 * @return array The modified list of allowed block types.
 */
function skel_allowed_block_types( $allowed_block_types, $editor_context ): array {
	$block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

	$allowed_blocks = array_values(
		array_filter(
			array_keys( $block_types ),
			function ( $block ) {
				return str_starts_with( $block, 'acf/' );
			}
		)
	);

	$allowed_blocks[] = 'core/block';

	// Additional block types allowed based on page post type.
	$post_type = $editor_context->post->post_type ?? '';
	if ( 'page' === $post_type ) {
		$allowed_blocks[] = 'core/image';
	}

	return $allowed_blocks;
}

add_filter( 'allowed_block_types_all', 'skel_allowed_block_types', 25, 2 );
