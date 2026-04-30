<?php
/**
 * Image Grid ACF block
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
$label       = get_field( 'label' ) ?: 'Custom Possibilities';
$heading     = get_field( 'heading' ) ?: 'What We Can Create Together';
$description = get_field( 'description' ) ?: 'Each project begins with your vision. Clients often come to ONE·ONE to create:';
$note        = get_field( 'note' ) ?: 'Because many clients prefer discretion, not all projects are publicly displayed. Additional examples can be shared privately during consultation.';
$items       = wp_list_pluck( get_field( 'items' ), 'image' );

if ( ! is_array( $items ) || empty( $items ) ) {
	$items = array(
		DEFAULT_THUMBNAIL_ID,
		DEFAULT_THUMBNAIL_ID,
		DEFAULT_THUMBNAIL_ID,
		DEFAULT_THUMBNAIL_ID,
		DEFAULT_THUMBNAIL_ID,
		DEFAULT_THUMBNAIL_ID,
	);
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="image-grid-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="content">
			<header class="header">
				<?php if ( $label ) : ?>
					<span class="label"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
			</header>

			<?php if ( $description ) : ?>
				<p class="description"><?php echo wp_kses_post( nl2br( $description ) ); ?></p>
			<?php endif; ?>

			<div class="grid">
				<?php
				foreach ( $items as $item ) :
					?>
					<div class="item">
						<?php
						echo wp_get_attachment_image(
							$item,
							'w800',
							false,
							array(
								'class'   => 'img-responsive',
								'loading' => 'lazy',
								'sizes'   => '(min-width: 1024px) 16vw, (min-width: 768px) 33vw, 50vw',
								'alt'     => '',
							)
						);
						?>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $note ) : ?>
				<p class="note"><?php echo wp_kses_post( nl2br( $note ) ); ?></p>
			<?php endif; ?>
		</div>

	</div>

</section>
