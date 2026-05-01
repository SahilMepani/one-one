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

$label          = get_field( 'label' ) ?: 'Get In Touch';
$heading        = get_field( 'heading' ) ?: 'Tell Me About Your Project';
$description    = get_field( 'description' ) ?: 'If you already have a project in mind, you can share a few details through the form below. This helps me better understand your vision before we speak.';
$image_id       = get_field( 'image' ) ?: DEFAULT_THUMBNAIL_ID;
$form_shortcode = get_field( 'form_shortcode' ) ?: '';
$image_first    = get_field( 'image_first' ) ?? true;

$layout_class = $image_first ? '' : 'image-form--form-first';
?>

<section
	class="image-form-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="image-form <?php echo esc_attr( $layout_class ); ?>">

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

			<div class="form-column">

				<div class="header">
					<?php if ( $label ) : ?>
						<span class="label"><?php echo esc_html( $label ); ?></span>
					<?php endif; ?>

					<?php if ( $heading ) : ?>
						<h2 class="heading"><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>

					<?php if ( $description ) : ?>
						<div class="description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
					<?php endif; ?>
				</div>

				<div class="form-wrap">
					<?php if ( $form_shortcode ) : ?>
						<?php echo do_shortcode( $form_shortcode ); ?>
					<?php endif; ?>
				</div>

			</div>

		</div>

	</div>

</section>
