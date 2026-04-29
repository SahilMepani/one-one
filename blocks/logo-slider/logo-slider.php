<?php
/**
 * Logo Slider ACF block
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
$logos = get_field( 'logos' );

if ( ! is_array( $logos ) || empty( $logos ) ) {
	return;
}

// Developer options.
$dev_options = skel_get_block_developer_options();

?>

<section
	class="logo-slider-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="logo-slider" data-inview data-aos="fade">
		<div class="swiper-wrapper">
			<?php
			for ( $i = 1; $i <= 6; $i++ ) {
				foreach ( $logos as $slide ) :
					$logo = $slide['image'];

					if ( $logo ) :
						?>
						<div class="swiper-slide">
							<div class="logo-block">
								<?php
								echo wp_get_attachment_image(
									$logo,
									'w768',
									false,
									array(
										'class'   => 'img-responsive',
										'sizes'   => '40rem',
										'loading' => 'lazy',
									)
								);
								?>
							</div>
						</div> <!-- .swiper-slide -->
					<?php endif; ?>

				<?php endforeach; ?>
			<?php } ?>

		</div> <!-- .swiper-wrapper -->
	</div> <!-- .swiper -->

</section>
