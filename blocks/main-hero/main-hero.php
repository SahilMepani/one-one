<?php
/**
 * Main Hero ACF Block
 *
 * @package Skeleton
 * @subpackage ACF
 */

// Set thumbnail preview in backend.
if ( skel_render_block_preview( $block ) ) {
	return;
}

// Return early if display is off.
if ( ! skel_should_display_block() ) {
	return;
}

// Data options.

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="main-hero-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<canvas id="c"></canvas>

	<div class="container">
		<div class="grid">
			<div class="inner-block">
				<div class="text-block">
					<h5 class="sub-heading">User-first, Outcome Driven</h5>
					<h2 class="heading">We help <i>creators, founders</i> and <i>growing teams</i> build digital products for growth</h2>

					<p class="description">Websites, apps, and AI workflows — delivered with clarity, speed, and no unnecessary overhead, by a team trusted by ambitious brands.</p>
				</div> <!-- .text-block -->

				<div class="btns-block">
					<a href="#0" class="btn btn-md btn-dark">
						<span>Book a Discovery Call</span>
					</a>
					<a href="#0" class="btn btn-md btn-light">See Our Impact</a>
				</div> <!-- .btns-block -->
			</div> <!-- .inner-block -->
		</div> <!-- .grid -->

		<div class="swiper hero-gallery-slider js-hero-gallery-slider">
			<!-- Additional required wrapper -->
			<div class="swiper-wrapper">
				<!-- Slides -->
				<div class="swiper-slide">
					<div class="img-cover-block">
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-gallery.jpg" alt="" class="img-cover">
					</div> <!-- .img-cover-block -->
				</div> <!-- .swiper-slide -->

				<div class="swiper-slide">
					<div class="img-cover-block">
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-gallery.jpg" alt="" class="img-cover">
					</div> <!-- .img-cover-block -->
				</div> <!-- .swiper-slide -->

				<div class="swiper-slide">
					<div class="img-cover-block">
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-gallery.jpg" alt="" class="img-cover">
					</div> <!-- .img-cover-block -->
				</div> <!-- .swiper-slide -->
			</div> <!-- .swiper-wrapper -->
		</div> <!-- .swiper -->


	</div> <!-- .container -->
</section>

<section class="main-hero-gallery-slider-section">
	<div class="swiper main-hero-gallery-slider js-main-hero-gallery-slider">
		<!-- Additional required wrapper -->
		<div class="swiper-wrapper">
			<!-- Slides -->
			<div class="swiper-slide">
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-gallery.jpg" alt="" class="img-cover">
			</div> <!-- .swiper-slide -->
		</div> <!-- .swiper-wrapper -->
	</div> <!-- .swiper -->
</section> <!-- .main-hero-gallery-slider-section -->

<div class="brand-marque-section">
	<div class="container" data-inview data-aos="fade">
		<h6 class="section-heading">Trusted by leading brands</h6>

		<div class="marquee">

			<div class="marquee-track">
				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-kettle-fire.svg" loading="lazy" alt="Kettle &amp; Fire">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-feel-goods.svg" loading="lazy" alt="Feel Goods">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-rheal-superfoods.svg" loading="lazy" alt="Rheal SuperFoods">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-lineage-provisions.svg" loading="lazy" alt="Lineage Provisions">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-earths-secret.svg" loading="lazy" alt="Earths Secret">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-senzu.svg" loading="lazy" alt="Senzu">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-adapt-naturals.svg" loading="lazy" alt="Adapt Naturals">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-equip.svg" loading="lazy" alt="Equip">
				</div>


				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-kettle-fire.svg" loading="lazy" alt="Kettle &amp; Fire">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-feel-goods.svg" loading="lazy" alt="Feel Goods">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-rheal-superfoods.svg" loading="lazy" alt="Rheal SuperFoods">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-lineage-provisions.svg" loading="lazy" alt="Lineage Provisions">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-earths-secret.svg" loading="lazy" alt="Earths Secret">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-senzu.svg" loading="lazy" alt="Senzu">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-adapt-naturals.svg" loading="lazy" alt="Adapt Naturals">
				</div>

				<div class="logo-block">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/brand/dark/logo-equip.svg" loading="lazy" alt="Equip">
				</div>
			</div>
		</div>
	</div>
</div>
