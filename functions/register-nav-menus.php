<?php
/**
 * Description: Registers navigation menus for the theme.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Register navigation menus.
 *
 * @return void
 */
function skel_register_nav_menus(): void {
	register_nav_menus(
		array(
			'header-menu' => esc_html__( 'Header Menu', 'skel' ),
			'footer-menu' => esc_html__( 'Footer Menu', 'skel' ),
		)
	);
}
add_action( 'after_setup_theme', 'skel_register_nav_menus' );
