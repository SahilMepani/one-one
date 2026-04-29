<?php
/**
 * Custom login
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Add logo
 *
 * @return void
 */
function skel_login_logo(): void {
	?>
<style type="text/css">
body.login {
	--page-background: #baa78f;
	--color: #18151d;
	--form-background: #fff;
	--input-background: #f7f7f9;
	--input-color: #333;
	--input-border: #baa78f;
	--input-border-focus: #baa78f;
	--submit-background: #baa78f;
	--submit-color: #fff;
	--link-color: #fff;
	background-color: var(--page-background);
	font-family: Verdana, "-apple-system", BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
	position: relative;
	overflow: hidden;
}

body.login #login_error,
#login .message {
	color: var(--color);
	font-size: 16px;
	border-radius: 5px;
	border: 0;
	padding: 12px 20px;
}

#login form {
	background: var(--form-background);
	border-color: transparent;
	border-radius: 12px;
	padding: 24px 24px 28px;
}

#login label {
	color: var(--color);
}

#login .wp-pwd {
	margin-block-end: 5px;
}

.wp-pwd button {
	background-position: center center !important;
	background-repeat: no-repeat !important;
}

.wp-pwd button:active,
.wp-pwd button:focus-visible {
	border: 0 !important;
	outline: 0 !important;
}

/* .wp-pwd input[type="password"]+button {
	background-image: url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/login-icon-eye.svg') !important;
}

.wp-pwd input[type="text"]+button {
	background-image: url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/login-icon-eye-strike.svg') !important;
} */

#login input[type="text"],
#login input[type="password"] {
	font-size: 20px;
	background: var(--input-background);
	color: var(--input-color);
	padding: 8px 15px;
	border-color: var(--input-border);
	border-radius: 4px;
}

/* .dashicons.dashicons {
	display: none;
} */

#login input[type="text"]:focus-visible,
#login input[type="password"]:focus-visible {
	border-color: var(--input-border-focus);
	box-shadow: none !important;
}

#login input[type="submit"] {
	background: var(--submit-background);
	color: var(--submit-color);
	text-transform: uppercase;
	min-inline-size: 89px;
	block-size: 39px;
	line-height: 39px;
	letter-spacing: 0.84px;
	border-radius: 22px;
	border: 0;
	overflow: hidden;
}

#login input[type="submit"]:focus-visible {
	box-shadow: none;
}

#login h1 a,
.login h1 a {
	background-image: url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/header-logo.png');
	inline-size: auto;
	block-size: 90px;
	background-size: contain;
	background-repeat: no-repeat;
	margin: 0 auto 30px;
}

#nav,
#backtoblog {
	text-align: center;
}

#login #nav {
	margin-block-start: 30px;
}

#login #backtoblog {
	margin-block-start: 18px;
}

#login #nav a,
#login #backtoblog a,
.privacy-policy-page-link a {
	color: var(--link-color);
}

#login #nav a:hover,
#login #backtoblog a:hover,
.privacy-policy-page-link a:hover {
	color: var(--link-color);
	text-decoration: underline;
}

.privacy-policy-link {
	display: none;
}
</style>
<?php } ?>

<?php
// Add action.
add_action( 'login_enqueue_scripts', 'skel_login_logo' );


/**
 * Add URL to logo
 *
 * @return string
 */
function skel_login_logo_url(): string {
	return home_url( '/' );
}
// Add filter.
add_filter( 'login_headerurl', 'skel_login_logo_url' );
