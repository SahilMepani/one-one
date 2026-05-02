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

// Field defaults.
$label      = get_field( 'label' )      ?: 'Our Purpose';
$heading    = get_field( 'heading' )    ?: 'Projects I Help Create';
$subheading = get_field( 'subheading' ) ?: 'Clients come to ONE·ONE for a wide range of meaningful jewelry projects, including:';
$items      = get_field( 'items' )      ?: array(
	array( 'image' => DEFAULT_THUMBNAIL_ID ),
	array( 'image' => DEFAULT_THUMBNAIL_ID ),
	array( 'image' => DEFAULT_THUMBNAIL_ID ),
	array( 'image' => DEFAULT_THUMBNAIL_ID ),
	array( 'image' => DEFAULT_THUMBNAIL_ID ),
	array( 'image' => DEFAULT_THUMBNAIL_ID ),
	array( 'image' => DEFAULT_THUMBNAIL_ID ),
);
?>

<section
	class="project-showcase-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="header">
			<?php if ( $label ) : ?>
				<span class="label"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="heading h2"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
		</div>

		<?php if ( $subheading ) : ?>
			<p class="subheading h4"><?php echo esc_html( $subheading ); ?></p>
		<?php endif; ?>

		<?php if ( is_array( $items ) && ! empty( $items ) ) : ?>
			<div class="banners">
				<?php foreach ( $items as $item ) :
					$image_id = $item['image'] ?: DEFAULT_THUMBNAIL_ID;
					?>
					<div class="card">
						<?php
						echo wp_get_attachment_image(
							$image_id,
							'w1024',
							false,
							array(
								'class'   => 'img-responsive',
								'loading' => 'lazy',
								'sizes'   => 'auto',
							)
						);
						?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>

</section>
