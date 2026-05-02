<?php
/**
 * Home Hero block
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
$background_image    = get_field( 'background_image' ) ?: DEFAULT_THUMBNAIL_ID;
$label               = get_field( 'label' ) ?: 'A private jewelry consulting experience.';
$heading             = get_field( 'heading' ) ?: "Every piece begins\nwith one story.";
$description         = get_field( 'description' ) ?: 'I help clients create meaningful jewelry pieces with thoughtful guidance, carefully sourced stones, and a process that feels personal from beginning to end.';
$cta                 = get_field( 'cta' );
$product_show        = get_field( 'product_show' );
$product_show        = ( null === $product_show ) ? true : (bool) $product_show;
$product_image       = get_field( 'product_image' ) ?: DEFAULT_THUMBNAIL_ID;
$product_eyebrow     = get_field( 'product_eyebrow' ) ?: 'ONE·ONE';
$product_title       = get_field( 'product_title' ) ?: 'Featured Project';
$product_description = get_field( 'product_description' ) ?: 'The Golden Embrace Ring elevates your style with its radiant gold finish, perfect for everyday wear or special occasions.';
$product_link        = get_field( 'product_link' );

if ( ! is_array( $cta ) || empty( $cta['url'] ) ) {
	$cta = array(
		'url'    => '#',
		'title'  => 'Start your Project',
		'target' => '',
	);
}

if ( ! is_array( $product_link ) || empty( $product_link['url'] ) ) {
	$product_link = array(
		'url'    => '#',
		'title'  => 'Learn More',
		'target' => '',
	);
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="home-hero-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="bg" aria-hidden="true">
		<?php
		echo wp_get_attachment_image(
			$background_image,
			'w1920',
			false,
			array(
				'class'         => 'bg-image',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'sizes'         => '100vw',
				'alt'           => '',
			)
		);
		?>
		<div class="bg-overlay"></div>
	</div>

	<div class="container">
		<div class="inner">
			<div class="content">

				<?php if ( $label ) { ?>
					<span class="text-label" data-inview data-aos="fade-up"><?php echo esc_html( $label ); ?></span>
				<?php } ?>

				<?php if ( $heading ) { ?>
					<h1 class="heading" data-inview data-aos="fade-up"><?php echo wp_kses_post( $heading ); ?></h1>
				<?php } ?>

				<?php if ( $description ) { ?>
					<p class="description" data-inview data-aos="fade-up"><?php echo esc_html( $description ); ?></p>
				<?php } ?>

				<?php if ( $cta['url'] ) { ?>
					<a class="btn btn-icon btn-dark btn-md" href="<?php echo esc_url( $cta['url'] ); ?>" target="<?php echo esc_attr( $cta['target'] ); ?>" data-inview data-aos="fade-up">
						<span class="btn-label"><?php echo esc_html( $cta['title'] ); ?></span>
						<span class="svg-block">
							<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
					</a>
				<?php } ?>
			</div>

			<?php if ( $product_show ) { ?>
				<div class="product" data-inview data-aos="fade-up">
					<div class="product-image">
						<?php
						echo wp_get_attachment_image(
							$product_image,
							'w400',
							false,
							array(
								'class'   => 'img-cover',
								'loading' => 'lazy',
								'sizes'   => '(min-width: 768px) 200px, 124px',
								'alt'     => esc_attr( $product_title ),
							)
						);
						?>
					</div>

					<div class="product-body">
						<div class="product-header">
							<?php if ( $product_eyebrow ) { ?>
								<p class="product-eyebrow"><?php echo esc_html( $product_eyebrow ); ?></p>
							<?php } ?>
							<?php if ( $product_title ) { ?>
								<p class="product-title"><?php echo esc_html( $product_title ); ?></p>
							<?php } ?>
							<?php if ( $product_description ) { ?>
								<p class="product-description"><?php echo esc_html( $product_description ); ?></p>
							<?php } ?>
						</div>

						<?php if ( $product_link['url'] ) { ?>
							<a class="btn-link-dark" href="<?php echo esc_url( $product_link['url'] ); ?>" target="<?php echo esc_attr( $product_link['target'] ); ?>">
								<span><?php echo esc_html( $product_link['title'] ); ?></span>
								<svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
									<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
