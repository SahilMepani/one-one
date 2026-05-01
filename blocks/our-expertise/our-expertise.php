<?php
/**
 * Our Expertise ACF block
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
$subheading = get_field( 'subheading' ) ?: 'Our Expertise';
$heading    = get_field( 'heading' ) ?: 'How I help you';
$items      = get_field( 'items' );

if ( ! is_array( $items ) || empty( $items ) ) {
	$items = array(
		array(
			'title'       => "Diamond\nSourcing",
			'description' => 'Finding natural or lab-grown diamonds that match your vision, priorities, and budget.',
			'image'       => DEFAULT_THUMBNAIL_ID,
		),
		array(
			'title'       => "Custom\nDesign",
			'description' => 'Crafting one-of-a-kind pieces tailored to your style, story, and the moments you want to celebrate.',
			'image'       => DEFAULT_THUMBNAIL_ID,
		),
		array(
			'title'       => "Trusted\nGuidance",
			'description' => 'Honest expertise from start to finish, so you feel informed and confident at every step.',
			'image'       => DEFAULT_THUMBNAIL_ID,
		),
	);
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="our-expertise-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<header class="header">
			<?php if ( $subheading ) : ?>
				<span class="label"><?php echo esc_html( $subheading ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="heading"><?php echo wp_kses_post( nl2br( $heading ) ); ?></h2>
			<?php endif; ?>
		</header>

		<div class="our-expertise-slider swiper">
			<div class="swiper-wrapper">
				<?php
				foreach ( $items as $item ) :
					$item_title       = $item['title'] ?? '';
					$item_description = $item['description'] ?? '';
					$item_image       = $item['image'];
					?>
					<div class="slide swiper-slide">
						<div class="text-col">
							<?php if ( $item_title ) : ?>
								<h3 class="item-title"><?php echo wp_kses_post( $item_title ); ?></h3>
							<?php endif; ?>

							<?php if ( $item_description ) : ?>
								<p class="item-description"><?php echo wp_kses_post( $item_description ); ?></p>
							<?php endif; ?>
						</div>

						<div class="image-col">
							<?php
							echo wp_get_attachment_image(
								$item_image,
								'w1280',
								false,
								array(
									'class'   => 'img-responsive',
									'loading' => 'lazy',
									'sizes'   => 'auto',
									'alt'     => '',
								)
							);
							?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( count( $items ) > 1 ) : ?>
			<div class="swiper-controls">
				<?php get_template_part( 'template-parts/swiper-pagination' ); ?>
				<?php get_template_part( 'template-parts/swiper-navigation' ); ?>
			</div>
		<?php endif; ?>

	</div>

</section>
