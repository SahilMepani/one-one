<?php
/**
 * Template Name: Multiple Slider
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

get_header();
?>

<div class="container spacing-top-large">
<div class="swiper creative-slider">
	<!-- Additional required wrapper -->
	<div class="swiper-wrapper">
		<!-- Slides -->
		<div class="swiper-slide">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/1.jpg" alt="">
		</div> <!-- .swiper-slide -->

		<div class="swiper-slide">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/2.jpg" alt="">
		</div> <!-- .swiper-slide -->

		<div class="swiper-slide">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/3.jpg" alt="">
		</div> <!-- .swiper-slide -->

		<div class="swiper-slide">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/4.jpg" alt="">
		</div> <!-- .swiper-slide -->

		<div class="swiper-slide">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/1.jpg" alt="">
		</div> <!-- .swiper-slide -->

		<div class="swiper-slide">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/2.jpg" alt="">
		</div> <!-- .swiper-slide -->

		<div class="swiper-slide">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/3.jpg" alt="">
		</div> <!-- .swiper-slide -->

		<div class="swiper-slide">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/4.jpg" alt="">
		</div> <!-- .swiper-slide -->
	</div> <!-- .swiper-wrapper -->
	<!-- If we need pagination -->
	<div class="swiper-pagination swiper-pagination-dot"></div>
	<!-- If we need navigation buttons -->
	<div class="swiper-navigation">
		<div class="swiper-button-prev">
			<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
				<path fill="#000" d="m10.308 17.308.707-.72L6.927 12.5H19v-1H6.927l4.088-4.088-.707-.72L5 12l5.308 5.308Z" />
			</svg>
		</div>
		<div class="swiper-button-next">
			<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
				<path fill="#000" d="m13.692 17.308-.707-.72 4.088-4.088H5v-1h12.073l-4.088-4.088.707-.72L19 12l-5.308 5.308Z" />
			</svg>
		</div>
	</div>
</div> <!-- .swiper -->
</div> <!-- .container -->


<section class="text-image-slider-section spacing-top-large">

	<div class="container">
		<div class="image-slider-col">
			<div class="image-slider swiper">
				<!-- Additional required wrapper -->
				<div class="swiper-wrapper">

					<!-- Slides -->
					<div class="swiper-slide">
						<div class="img-cover-block">
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/1.jpg" class="img-cover" alt="">
						</div> <!-- .img-cover-block -->
					</div> <!-- .swiper-slide -->

					<div class="swiper-slide">
						<div class="img-cover-block">
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/placeholder/2.jpg" class="img-cover" alt="">
						</div> <!-- .img-cover-block -->
					</div> <!-- .swiper-slide -->
				</div> <!-- .swiper-wrapper -->
			</div> <!-- .swiper -->
		</div> <!-- .image-slider-col -->

		<div class="text-slider-col">
			<div class="text-slider swiper">
				<!-- Additional required wrapper -->
				<div class="swiper-wrapper">

					<!-- Slides -->
					<div class="swiper-slide">
						<h3 class="heading">tradition meets modernity</h3>

						<p class="description">Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien.</p>

						<a href="#0" class="btn btn-md btn-primary">Shop Now</a>
					</div>

					<div class="swiper-slide">
						<h3 class="heading">modernity meets tradition </h3>

						<p class="description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum similique praesentium corrupti accusantium rem officiis.</p>

						<a href="#0" class="btn btn-md btn-primary">Shop Now</a>
					</div>

				</div> <!-- .swiper-wrapper -->

				<div class="swiper-navigation">
					<div class="swiper-button-prev">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path fill="#000" d="m10.308 17.308.707-.72L6.927 12.5H19v-1H6.927l4.088-4.088-.707-.72L5 12l5.308 5.308Z"/></svg>
					</div>
					<div class="swiper-button-next">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
							<path fill="#000" d="m13.692 17.308-.707-.72 4.088-4.088H5v-1h12.073l-4.088-4.088.707-.72L19 12l-5.308 5.308Z" />
						</svg>
					</div>
				</div>
			</div> <!-- .swiper -->
		</div> <!-- .text-slider-col -->
	</div> <!-- .container -->
</section> <!-- .text-image-slider-section -->

<?php get_footer(); ?>
