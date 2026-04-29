<?php
/**
 * Template Name: Styleguide
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<!-- HTML Boilerplate v8.00 -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php wp_title( '-', true, 'right' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'site-wrapper' ); ?>>
	<?php wp_body_open(); ?>
	<div class="container">
		<div class=""><?php esc_html_e( 'Base Colors', 'skel' ); ?></div>
		<hr class="">
		<ul class="">
			<?php foreach ( array( 'blue', 'indigo', 'purple', 'pink', 'red' ) as $color ) : ?>
				<li class="">
					<div class="bg-<?php echo esc_attr( $color ); ?> text-white d-flex flex-column align-content-between py-1-5 px-2">
						<div class="fs-4 mb-7"><?php echo esc_html( $color ); ?></div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<!-- More color sections omitted for brevity -->
		<div class="row row-cols-2 gx-5 mb-10">
			<!-- Typography section omitted for brevity -->
		</div>
		<div class="fs-4 mb-1 text-gray-600 fw-bold"><?php esc_html_e( 'Buttons with Hover', 'skel' ); ?></div>
		<hr class="mb-4">
		<!-- Button styles section omitted for brevity -->
		<div class="fs-4 mb-1 text-gray-600 fw-bold"><?php esc_html_e( 'Alerts', 'skel' ); ?></div>
		<hr class="mb-4">
		<div class="mb-6">
			<!-- Alert section omitted for brevity -->
		</div>
		<div class="fs-4 mb-1 text-gray-600 fw-bold"><?php esc_html_e( 'Table', 'skel' ); ?></div>
		<hr class="mb-4">
		<div class="row mb-5">
			<!-- Table section omitted for brevity -->
		</div>
		<h4><?php esc_html_e( 'Form', 'skel' ); ?></h4>
		<?php gravity_form( 1, false, false, false, null, true, 10 ); ?>
	</div>
	<?php get_footer(); ?>
