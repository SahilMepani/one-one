<?php
/**
 * Visual Editor ACF block
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
$content = get_field( 'content' );

if ( empty( $content ) ) {
	return;
}

$background = get_field( 'background' );
$layout     = get_field( 'layout' );

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="visual-editor-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']} layout-" . esc_attr( $layout ) . ' bg-' . esc_attr( $background ) ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="inner-container">
			<?php echo wp_kses_post( $content ); ?>
		</div> <!-- .inner-container -->

	</div><!-- .container -->
</section>
