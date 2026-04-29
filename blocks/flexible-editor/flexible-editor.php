<?php
/**
 * Flexible Editor ACF block
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

// Block options.
$flexible_editor = get_field( 'flexible_editor' );

if ( ! is_array( $flexible_editor ) || empty( $flexible_editor ) ) {
	return;
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="flexible-editor-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">
	<div class="container">

		<?php
			get_template_part(
				'template-parts/flexible-editor',
				null,
				array(
					'flexible_editor' => $flexible_editor,
				)
			);
			?>

	</div> <!-- .container -->
</section>
