<?php //phpcs:ignore file comment
/**
 * Redirect user after login. Skip dashboard
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Redirect user after login.
 *
 * This function redirects users after login, skipping the dashboard and directing them to the
 * 'Pages' screen if they have the capability to edit posts.
 *
 * @param string $url     The redirect URL.
 * @param string $request The requested redirect URL.
 * @param object $user    The user object.
 * @return string Redirect URL.
 */
function skel_skip_dash( $url, $request, $user ) {
	// If there is no user or the user object is an error, do not change the URL.
	if ( ! $user || is_wp_error( $user ) ) {
		return $url;
	}

	// If the user does not have the capability to edit posts, do not change the URL.
	if ( ! user_can( $user, 'edit_posts' ) ) {
		return $url;
	}

	// Redirect to the 'Pages' screen.
	return admin_url( 'edit.php?post_type=page' );
}

add_filter( 'login_redirect', 'skel_skip_dash', 10, 3 );
