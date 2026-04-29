<?php
/**
 * Add custom image sizes
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'h200', 9999, 200 );
		add_image_size( 'w200', 200, 9999 );
		add_image_size( 'w768', 768, 9999 );
		add_image_size( 'w1400', 1400, 9999 );
		add_image_size( 'w1920', 1920, 9999 );
	}
);
