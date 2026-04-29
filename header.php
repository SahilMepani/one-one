<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

?>

<!doctype html>
<html <?php language_attributes(); ?>  class="<?php echo esc_html( skel_direction_class() ); ?>">

<head>
	<!-- HTML Boilerplte v8.00 -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<?php wp_head(); ?>

	<?php
	if ( wp_get_environment_type() === 'production' ) {
		$head_code = get_field( 'head_code', 'option' );
		if ( $head_code ) {
			echo $head_code;
		}
	}
	?>
</head>

<body <?php body_class( 'site-wrapper' ); ?>>

	<?php wp_body_open(); ?>

	<!-- this has to be the first item focusable -->
	<a class="skip-link screen-reader-text" href="#site-content">
		<?php esc_html_e( 'Skip to content', 'skel' ); ?>
	</a>

	<div class="modal-backdrop" aria-hidden="true"></div>

	<?php
		$header_options = get_field( 'header', 'option' );
		$header_logo    = $header_options['logo'] ?? '';
	?>

	<header class="site-header">
		<div class="container">
			<div class="inner-container">
				<div class="header-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="Go to Home" aria-label="Go to Home">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 104 18"><path fill="currentColor" d="M0 0h3.192v18H0zM11.262 18l-3.116-7.85V18H4.979V0h2.593l3.117 8.693V0h3.166v18zM21.94 18l-3.118-7.85V18h-3.166V0h2.593l3.117 8.693V0h3.166v18zM26.333 0h7.605v3.324h-4.414V7.21h3.666v3.324h-3.666v4.142h4.413V18h-7.604zM42.376 0c.748 0 1.321.205 1.72.588.4.384.599.946.599 1.713V8.72c0 .613-.2 1.074-.574 1.33-.374.255-.823.408-1.371.434L45.218 18h-3.291l-2.27-7.082V7.21h1.846V3.324h-2.568V18h-3.192V0zM56.94 18h-3.39l-1.97-9.895L49.66 18h-3.416l3.715-18h3.291zM61.676 18h-3.191V0h3.191zm.698-5.625V5.446L64.47 0h3.391l-3.64 9 3.64 9h-3.391zM77.633 0v3.324h-2.518V18h-3.192V3.324h-2.518V0zM79.424 0h3.192v18h-3.192zM94.85 0l-3.69 18h-3.316l-3.69-18h3.391l1.945 9.895L91.46 0zM96.395 0H104v3.324h-4.413V7.21h3.665v3.324h-3.665v4.142H104V18h-7.605z"/></svg>
					</a>
				</div>

				<button class="header-nav-toggle btn" aria-label="show primary navigation" aria-haspopup="true" aria-expanded="false"
					aria-controls="siteMenu">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
						<path
							d="M3 13h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1zM3 7h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1zM3 19h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1z"
							fill="currentColor"></path>
					</svg>
				</button>

				<nav class="header-nav" data-esc aria-label="primary navigation">

					<button class="header-nav-close btn" aria-label="close primary navigation" aria-expanded="false"
						aria-controls="siteMenu">
						<svg viewBox="0 0 24 24" fill="none">
							<path
								d="M5.293 6.707l5.293 5.293-5.293 5.293c-0.391 0.391-0.391 1.024 0 1.414s1.024 0.391 1.414 0l5.293-5.293 5.293 5.293c0.391 0.391 1.024 0.391 1.414 0s0.391-1.024 0-1.414l-5.293-5.293 5.293-5.293c0.391-0.391 0.391-1.024 0-1.414s-1.024-0.391-1.414 0l-5.293 5.293-5.293-5.293c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414z"
								fill="currentColor"></path>
						</svg>
					</button>

					<ul class="header-menu">
						<li>
							<a href="<?php echo home_url( '/projects/' ); ?>">Projects</a>
							<div class="header-dropdown-block">
								<ul class="header-sub-menu two-col">
									<li>
										<a href="#">
											<div class="inner-block">
												<div class="icon-block">
													<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/boost.svg' ); ?>">
												</div>
												<span>Boost</span>
											</div>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="<?php echo home_url( '/services/' ); ?>">Services</a>
							<div class="header-dropdown-block">
								<ul class="header-sub-menu one-col">
									<li>
										<a href="#">
											<div class="inner-block">
												<div class="icon-block">
													<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/boost.svg' ); ?>">
												</div>
												<span>Boost</span>
											</div>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="<?php echo home_url( '/how-we-work/' ); ?>">How we Work</a>
						</li>
						<li><a href="<?php echo home_url( '/about-us/' ); ?>">About Us</a></li>
						<li><a href="<?php echo home_url( '/insights/' ); ?>">Insights</a></li>
					</ul>
				</nav>
			</div> <!-- .inner-container -->
		</div>
	</header> <!-- .site-header -->

	<main id="site-content" class="site-content">
