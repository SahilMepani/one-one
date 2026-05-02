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

$label    = get_field( 'label' ) ?: 'Our Purpose';
$heading  = get_field( 'heading' ) ?: "Why I Created\nONE\u{00B7}ONE";
$image_id = get_field( 'image' ) ?: DEFAULT_THUMBNAIL_ID;
$columns  = get_field( 'columns' ) ?: array(
	array(
		'lead' => 'Over time I noticed that choosing an important jewelry piece can often feel confusing for many clients.',
		'body' => '<p>People are usually limited to what happens to be available in a store, without truly understanding the quality, value, or possibilities that exist beyond the display.</p><p>At the same time, jewelry craftsmanship continues to evolve — from improved cutting techniques to more refined manufacturing methods and custom design possibilities.</p>',
	),
	array(
		'lead' => "I created ONE\u{00B7}ONE to offer\nsomething different:",
		'body' => '<p>a thoughtful and transparent experience where clients can explore diamonds, understand their choices, and create jewelry that truly reflects their story.</p>',
	),
);
?>

<section
	class="purpose-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
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

		<div class="content">

			<?php if ( $image_id ) : ?>
				<div class="image">
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
			<?php endif; ?>

			<?php if ( is_array( $columns ) && ! empty( $columns ) ) : ?>
				<div class="columns">
					<?php foreach ( $columns as $column ) : ?>
						<div class="column">
							<?php if ( ! empty( $column['lead'] ) ) : ?>
								<p class="lead h4"><?php echo esc_html( $column['lead'] ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $column['body'] ) ) : ?>
								<div class="body"><?php echo wp_kses_post( $column['body'] ); ?></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>

	</div>

</section>
