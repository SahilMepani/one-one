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

// Field data.
$logo_id = get_field( 'logo' ) ?: DEFAULT_THUMBNAIL_ID;
$text    = get_field( 'text' ) ?: "One client. One story.\nOne piece created just for you.";
?>

<section
	class="brand-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="logo">
			<?php echo wp_get_attachment_image( $logo_id, 'w1920', false, array( 'class' => 'img-responsive', 'loading' => 'lazy', 'sizes' => 'auto' ) ); ?>
		</div>

		<div class="text">
			<?php echo wp_kses_post( wpautop( $text ) ); ?>
		</div>

	</div>

</section>
