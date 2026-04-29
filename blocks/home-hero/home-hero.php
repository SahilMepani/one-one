<?php
// Set thumbnail preview in backend.
if ( skel_render_block_preview( $block ) ) {
	return;
}

// Return early if display is off.
if ( ! skel_should_display_block() ) {
	return;
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="heading-text-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="home-hero__bg">
		<img
			class="home-hero__bg-image"
			src="assets/images/home-hero-bg.png"
			alt=""
			loading="eager"
			fetchpriority="high"
		/>
		<div class="home-hero__bg-overlay" aria-hidden="true"></div>
	</div>

	<div class="container home-hero__container">
		<div class="home-hero__inner">
			<div class="home-hero__content">
				<span class="home-hero__label" data-inview data-aos="fade-up">Elegant Jewelry</span>

				<h1 class="home-hero__heading" data-inview data-aos="fade-up">Luminous Treasures: A Journey into Exquisite Jewelry</h1>

				<p class="home-hero__description" data-inview data-aos="fade-up">Thoughtfully crafted pieces that honor your style and simplify your jewelry routine.</p>

				<a class="btn btn-icon btn-dark btn-md" href="/shop" data-inview data-aos="fade-up">
					<span class="label">Shop Now</span>
					<span class="svg-block">
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				</a>
			</div>

			<div class="home-hero__product" data-inview data-aos="fade-up">
				<a class="home-hero__product-image img-link" href="/shop/golden-embrace-ring" aria-label="View Golden Embrace Ring">
					<img
						src="assets/images/home-hero-product.png"
						alt="Golden Embrace Ring"
						loading="lazy"
						class="img-cover"
					/>
				</a>

				<div class="home-hero__product-body">
					<div class="home-hero__product-header">
						<p class="home-hero__product-brand">bySoraya</p>
						<a href="#0" class="home-hero__product-name text-link">Golden Embrace Ring</a>
					</div>

					<p class="home-hero__product-description">The Golden Embrace Ring elevates your style with its radiant gold finish, perfect for everyday wear or special occasions.</p>

					<a class="btn-link-dark" href="/shop/golden-embrace-ring">
						<span>Add to Bag</span>
						<svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</a>
				</div>
			</div>
		</div>
	</div> <!-- .container -->

</section>
