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

$step        = get_field( 'step' ) ?: 'STEP 01';
$heading     = get_field( 'heading' ) ?: 'Consultation';
$subheading  = get_field( 'subheading' ) ?: "Every project begins with\na conversation.";
$description = get_field( 'description' ) ?: 'We discuss your inspiration, preferences, and budget to understand the vision behind the piece. Consultations can take place in person or remotely, allowing clients worldwide to work together easily.';
$image_id    = get_field( 'image' ) ?: DEFAULT_THUMBNAIL_ID;
$image_first = get_field( 'image_first' ) ? true : false;

$layout_class = $image_first ? 'image-text-column--image-first' : '';
?>

<section
	class="image-text-column-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="image-text-column <?php echo esc_attr( $layout_class ); ?>">

			<div class="text-column">
				<?php if ( $step ) : ?>
					<p class="step"><?php echo esc_html( $step ); ?></p>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $subheading ) : ?>
					<p class="subheading"><?php echo wp_kses_post( $subheading ); ?></p>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<div class="description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
				<?php endif; ?>
			</div>

			<div class="image-column">
				<?php
				echo wp_get_attachment_image(
					$image_id,
					'w1920',
					false,
					array(
						'class'   => 'img-responsive',
						'loading' => 'lazy',
						'sizes'   => 'auto',
					)
				);
				?>
			</div>

		</div>

	</div>

</section>
