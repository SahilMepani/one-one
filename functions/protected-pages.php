<?php
/**
 * Protected Pages
 *
 * Prevents deletion of specific protected posts required by the theme.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Prevents deletion of specific protected posts.
 *
 * This function hooks into the 'wp_trash_post' and 'before_delete_post' actions
 * to prevent certain posts from being trashed or permanently deleted. If a user
 * attempts to delete a protected post, they are redirected back to the page list
 * with a notice.
 *
 * @param int $postid The ID of the post being trashed or deleted.
 */
function prevent_post_deletion( $postid ) {
	$protected_posts = array();

	if ( defined( 'PAGE_404_ID' ) ) {
		$protected_posts[] = (int) PAGE_404_ID;
	}
	if ( defined( 'PAGE_SEARCH_ID' ) ) {
		$protected_posts[] = (int) PAGE_SEARCH_ID;
	}
	if ( defined( 'PAGE_KB_ID' ) ) {
		$protected_posts[] = (int) PAGE_KB_ID;
	}

	if ( empty( $protected_posts ) || ! in_array( (int) $postid, $protected_posts, true ) ) {
		return;
	}

	$redirect_url = add_query_arg(
		array(
			'post_type'      => 'page',
			'protected_post' => 'true',
			'_wpnonce'       => wp_create_nonce( 'protected_post_notice' ),
		),
		admin_url( 'edit.php' )
	);

	wp_safe_redirect( $redirect_url );
	exit;
}
add_action( 'wp_trash_post', 'prevent_post_deletion' );
add_action( 'before_delete_post', 'prevent_post_deletion' );

/**
 * Displays an admin notice when a user attempts to delete a protected post.
 */
function protected_post_admin_notice() {
	if ( ! isset( $_GET['protected_post'], $_GET['_wpnonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'protected_post_notice' ) ) {
		return;
	}

	echo '<div class="notice notice-error is-dismissible"><p>';
	echo esc_html__( 'This page is protected and cannot be deleted.', 'skel' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'protected_post_admin_notice' );
