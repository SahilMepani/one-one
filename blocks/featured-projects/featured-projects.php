<?php
/**
 * Featured Projects ACF block
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
$subheading = get_field( 'subheading' ) ?: 'Featured Project';
$heading    = get_field( 'heading' ) ?: 'The Story Behind a Piece';
$items      = get_field( 'items' );

if ( ! is_array( $items ) || empty( $items ) ) {
	$items = array(
		array(
			'image'       => DEFAULT_THUMBNAIL_ID,
			'description' => 'This custom pendant was created to celebrate a meaningful milestone. The client wanted a piece that felt timeless yet personal.',
		),
		array(
			'image'       => DEFAULT_THUMBNAIL_ID,
			'description' => 'After selecting the diamond together, we designed a pendant that could be worn daily while remaining elegant and refined.',
		),
		array(
			'image'       => DEFAULT_THUMBNAIL_ID,
			'description' => 'The final piece became a symbol of a special moment that will be remembered for years to come.',
		),
	);
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="featured-projects-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<header class="header">
			<?php if ( $subheading ) : ?>
				<span class="label"><?php echo esc_html( $subheading ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="heading"><?php echo wp_kses_post( $heading ); ?></h2>
			<?php endif; ?>
		</header>

		<div class="grid">
			<?php
			foreach ( $items as $item ) :
				$item_image       = $item['image'] ?? DEFAULT_THUMBNAIL_ID;
				$item_description = $item['description'] ?? '';
				?>
				<article class="project">
					<div class="image">
						<?php
						echo wp_get_attachment_image(
							$item_image,
							'w800',
							false,
							array(
								'class'   => 'img-cover',
								'loading' => 'lazy',
								'sizes'   => '(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw',
								'alt'     => '',
							)
						);
						?>
					</div>

					<?php if ( $item_description ) : ?>
						<p class="description"><?php echo wp_kses_post( $item_description ); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

	</div>

</section>
