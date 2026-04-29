<?php
/**
 * Description: Removes default Posts type since no blog.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Remove default Posts type from the admin menu.
 *
 * @return void
 */
function skel_remove_default_post_type() {
	remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', 'skel_remove_default_post_type' );

/**
 * Remove +New post in top Admin Menu Bar.
 *
 * @param object $wp_admin_bar The WP_Admin_Bar instance.
 * @return void
 */
function skel_remove_default_post_type_menu_bar( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'new-post' );
}
add_action( 'admin_bar_menu', 'skel_remove_default_post_type_menu_bar', 999 );

/**
 * Remove Quick Draft Dashboard Widget.
 *
 * @return void
 */
function skel_remove_draft_widget() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'skel_remove_draft_widget', 999 );


/**
 * Removes selected meta boxes from Appearance → Menus.
 *
 * Hooks into the `nav_menu_meta_box_object` filter to remove one or more
 * meta boxes (e.g., Posts, Pages, Categories, Tags, etc.) from the Menus screen.
 * Returning `false` for a given menu object hides it from the available menu items list.
 *
 * @since 1.0.0
 *
 * @param object $menu_object The current meta box object being processed in the Menus screen.
 * @return object|false The unmodified menu object, or false to remove the meta box.
 */
function skel_remove_menu_meta_boxes( object $menu_object ): object|false {

	// Define the post types or taxonomies to remove from Menus.
	$remove_items = array(
		'post',      // Default Posts.
		'page',      // Pages.
		'category',  // Categories taxonomy.
		'post_tag',  // Tags taxonomy.
		'product',   // WooCommerce Products (optional).
	);

	// If this object matches one of the items to remove, return false.
	if ( isset( $menu_object->name ) && in_array( $menu_object->name, $remove_items, true ) ) {
		return false;
	}

	return $menu_object;
}
add_filter( 'nav_menu_meta_box_object', 'skel_remove_menu_meta_boxes' );
