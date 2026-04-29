<?php //phpcs:ignore file comment
/**
 * Disable the Customizer page and Theme Editor in the WordPress admin.
 *
 * This function removes the Customizer and Theme Editor submenu pages from the admin menu.
 *
 * @return void
 */

add_action(
	'admin_menu',
	function () {
		// Check if REQUEST_URI is set before using it.
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			// Build the customizer URL to remove.
			$customizer_url = add_query_arg(
				'return',
				rawurlencode( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ),
				admin_url( 'customize.php' )
			);

			// Remove the Customizer and Theme Editor submenu pages.
			remove_submenu_page( 'themes.php', 'themes.php' );
			remove_submenu_page( 'themes.php', $customizer_url );
			remove_submenu_page( 'themes.php', 'theme-editor.php' );
		}
	},
	999
);

/**
 * Remove menu item for non-admin users.
 *
 * This function removes a specific menu item from the admin menu for non-admin users.
 *
 * @return void
 */
function skel_remove_menu_item_non_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		remove_menu_page( 'edit.php?post_type=simple-pay' );
	}
}
add_action( 'admin_menu', 'skel_remove_menu_item_non_admin', 999 );
