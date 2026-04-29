<?php //phpcs:ignore file comment
/**
 * Description: Defines custom shortcodes for the theme.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/*----------  Required  ----------*/

/**
 * Home URL Shortcode.
 *
 * Usage: [home_url]
 *
 * @return string The home URL.
 */
function skel_home_url(): string {
	return esc_url( home_url( '/' ) );
}
add_shortcode( 'home_url', 'skel_home_url' );

/**
 * Template Directory URL Shortcode.
 *
 * Usage: [skel_template_dir]
 *
 * @return string The template directory URL.
 */
function skel_template_directory(): string {
	return esc_url( get_template_directory_uri() );
}
add_shortcode( 'skel_template_dir', 'skel_template_directory' );

/**
 * Images Directory URL Shortcode.
 *
 * Usage: [skel_image_dir]
 *
 * @return string The images directory URL.
 */
function skel_images_directory(): string {
	return esc_url( get_template_directory_uri() . '/images' );
}
add_shortcode( 'skel_image_dir', 'skel_images_directory' );
