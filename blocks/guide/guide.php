<?php
/**
 * Guide ACF block
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
$heading     = get_field( 'heading' ) ?: "Meaningful jewelry,\nguided personally";
$description = get_field( 'description' ) ?: 'I help clients create meaningful jewelry pieces with thoughtful guidance, carefully sourced stones, and a process that feels personal from beginning to end.';
$items       = get_field( 'items' ) ?: array(
	array(
		'image'       => DEFAULT_THUMBNAIL_ID,
		'title'       => 'Clarity',
		'description' => 'Guidance when choosing an important piece feels overwhelming.',
	),
	array(
		'image'       => DEFAULT_THUMBNAIL_ID,
		'title'       => 'Selection',
		'description' => 'Natural and lab-grown diamonds sourced around your priorities.',
	),
	array(
		'image'       => DEFAULT_THUMBNAIL_ID,
		'title'       => 'Creation',
		'description' => 'Custom jewelry designed to feel personal and lasting.',
	),
	array(
		'image'       => DEFAULT_THUMBNAIL_ID,
		'title'       => 'Discretion',
		'description' => 'A private experience where many client projects remain confidential.',
	),
);

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="guide-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<header class="header">
			<?php if ( $heading ) : ?>
				<h2 class="heading"><?php echo wp_kses_post( $heading ); ?></h2>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<p class="description"><?php echo wp_kses_post( $description ); ?></p>
			<?php endif; ?>
		</header>

		<div class="grid">
			<?php foreach ( $items as $item ) : ?>
				<div class="card">
					<?php
					echo wp_get_attachment_image(
						$item['image'] ?: DEFAULT_THUMBNAIL_ID,
						'w800',
						false,
						array(
							'class'   => 'img-responsive',
							'loading' => 'lazy',
							'sizes'   => '(min-width: 1024px) 25vw, (min-width: 768px) 50vw, 100vw',
							'alt'     => '',
						)
					);
					?>

					<div class="overlay">
						<?php if ( ! empty( $item['title'] ) ) : ?>
							<p class="title"><?php echo esc_html( $item['title'] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $item['description'] ) ) : ?>
							<p class="body"><?php echo wp_kses_post( $item['description'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>

</section>
