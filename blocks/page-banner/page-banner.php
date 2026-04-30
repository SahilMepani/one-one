<?php
/**
 * Page Banner ACF block
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
$background_image = get_field( 'background_image' ) ?: DEFAULT_THUMBNAIL_ID;
$label            = get_field( 'label' ) ?: 'Services';
$heading          = get_field( 'heading' ) ?: 'At ONE·ONE, every piece begins with you.';
$description      = get_field( 'description' ) ?: 'Custom jewelry, thoughtfully guided from the first idea to the final piece — with clarity, honesty, and attention to every detail.';

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="page-banner-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="background" aria-hidden="true">
		<?php
		echo wp_get_attachment_image(
			$background_image,
			'w1920',
			false,
			array(
				'class'   => 'background-image',
				'loading' => 'eager',
				'sizes'   => '100vw',
				'alt'     => '',
			)
		);
		?>
		<div class="background-overlay"></div>
	</div>

	<div class="container">

		<header class="header">

			<?php if ( $label ) : ?>
				<span class="label"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h1 class="heading"><?php echo wp_kses_post( nl2br( $heading ) ); ?></h1>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<p class="description"><?php echo wp_kses_post( nl2br( $description ) ); ?></p>
			<?php endif; ?>

		</header>

	</div>

</section>
