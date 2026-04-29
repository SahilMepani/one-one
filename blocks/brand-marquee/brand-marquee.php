<?php
// Set thumbnail preview in backend.
if ( skel_render_block_preview( $block ) ) {
	return;
}

// Return early if display is off.
if ( ! skel_should_display_block() ) {
	return;
}

// Data options.
$heading = get_field( 'heading' );
$logos   = get_field( 'logos' );

if ( ! is_array( $logos ) || empty( $logos ) ) {
	return;
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="brand-marquee-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">
		<?php if ( $heading ) : ?>
			<h6 class="section-heading" data-inview data-aos="fade-up">
				<?php echo skel_get_italic_braces( $heading ); ?>
			</h6>
		<?php endif; ?>

		<div class="grid" data-inview data-aos="fade">
			<?php
			foreach ( $logos as $logo ) :
				$image_id = $logo['image'] ?? '';
				if ( ! $image_id ) {
					continue;
				}
				?>
				<div class="logo-block">
					<?php
					echo wp_get_attachment_image(
						$image_id,
						'w400',
						false,
						array(
							'class'   => 'img-responsive',
							'loading' => 'lazy',
						)
					);
					?>
				</div>
			<?php endforeach; ?>
		</div> <!-- .grid -->
	</div> <!-- .container -->

</section>
