<?php //phpcs:ignore file comment
/**
 * Description: Remove comments functionality from the admin menu, post,
 * and pages, and admin bar.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Remove comments page from the admin menu.
 *
 * @return void
 */
function skel_my_remove_admin_menus(): void {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'skel_my_remove_admin_menus' );

/**
 * Remove comments support from post and pages.
 *
 * @return void
 */
function skel_remove_comment_support(): void {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' );
}
add_action( 'init', 'skel_remove_comment_support', 100 );
