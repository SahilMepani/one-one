<?php //phpcs:ignore file comment
/**
 * Disable WP generated image sizes
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */
function skel_theme_activation_setup() {
	// Update default media sizes.
	update_option( 'thumbnail_size_w', 0 );
	update_option( 'medium_size_w', 0 );
	update_option( 'large_size_w', 0 );
	update_option( 'medium_large_size_w', 0 );
	update_option( 'thumbnail_size_h', 0 );
	update_option( 'medium_size_h', 0 );
	update_option( 'large_size_h', 0 );
	update_option( 'medium_large_size_h', 0 );
}
add_action( 'after_switch_theme', 'skel_theme_activation_setup' );

/**
 * Disable specific image sizes.
 *
 * This function removes certain default image sizes from the list of available sizes.
 *
 * @param array $sizes Associative array of registered image sizes.
 * @return array Filtered associative array of image sizes.
 */
function skel_disable_image_sizes( array $sizes ): array {

	unset( $sizes['thumbnail'] );
	unset( $sizes['medium'] );
	unset( $sizes['large'] );
	unset( $sizes['medium_large'] );
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );

	return $sizes;
}
// Add action.
add_filter( 'intermediate_image_sizes_advanced', 'skel_disable_image_sizes' );

// Disable scaled image size.
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * Disable other image sizes.
 *
 * @return void
 */
function skel_disable_other_image_sizes(): void {

	remove_image_size( 'post-thumbnail' ); // disable images added via set_post_thumbnail_size()
	// remove_image_size('another-size'); // disable any other added image sizes.
}
// Add action.
add_action( 'init', 'skel_disable_other_image_sizes' );
