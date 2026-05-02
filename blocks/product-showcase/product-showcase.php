<?php
/**
 * Product Showcase ACF block
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
$subheading = get_field( 'subheading' ) ?: 'Portfolio Showcase';
$heading    = get_field( 'heading' ) ?: 'What we’ve delivered';
$items      = get_field( 'items' );
$cta        = get_field( 'cta' );

if ( ! is_array( $items ) || empty( $items ) ) {
	$items = array(
		array(
			'image'   => DEFAULT_THUMBNAIL_ID,
			'eyebrow' => 'ONE·ONE',
			'title'   => 'Engagement Ring',
		),
		array(
			'image'   => DEFAULT_THUMBNAIL_ID,
			'eyebrow' => 'ONE·ONE',
			'title'   => 'Pendant',
		),
		array(
			'image'   => DEFAULT_THUMBNAIL_ID,
			'eyebrow' => 'ONE·ONE',
			'title'   => 'Earrings',
		),
		array(
			'image'   => DEFAULT_THUMBNAIL_ID,
			'eyebrow' => 'ONE·ONE',
			'title'   => 'Necklace',
		),
		array(
			'image'   => DEFAULT_THUMBNAIL_ID,
			'eyebrow' => 'ONE·ONE',
			'title'   => 'Custom Piece',
		),
		array(
			'image'   => DEFAULT_THUMBNAIL_ID,
			'eyebrow' => 'ONE·ONE',
			'title'   => 'Eclipsara Ring',
		),
	);
}

if ( ! is_array( $cta ) || empty( $cta['url'] ) ) {
	$cta = array(
		'url'    => '#',
		'title'  => 'Explore More',
		'target' => '',
	);
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="product-showcase-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<header class="header">
			<?php if ( $subheading ) : ?>
				<span class="text-label"><?php echo esc_html( $subheading ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="heading h2"><?php echo wp_kses_post( $heading ); ?></h2>
			<?php endif; ?>
		</header>

		<div class="grid">
			<?php
			foreach ( $items as $item ) :
				$item_image   = $item['image'];
				$item_eyebrow = $item['eyebrow'] ?? '';
				$item_title   = $item['title'] ?? '';
				?>
				<article class="product">
					<div class="image img-cover-block">
						<?php
						echo wp_get_attachment_image(
							$item_image,
							'w800',
							false,
							array(
								'class'   => 'img-cover',
								'loading' => 'lazy',
								'sizes'   => '(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw',
								'alt'     => esc_attr( $item_title ),
							)
						);
						?>
					</div>

					<div class="meta">
						<?php if ( $item_eyebrow ) : ?>
							<p class="eyebrow"><?php echo esc_html( $item_eyebrow ); ?></p>
						<?php endif; ?>

						<?php if ( $item_title ) : ?>
							<p class="title"><?php echo esc_html( $item_title ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $cta['url'] ) ) : ?>
			<div class="cta">
				<a class="btn btn-icon btn-dark btn-md" href="<?php echo esc_url( $cta['url'] ); ?>" target="<?php echo esc_attr( $cta['target'] ); ?>" data-inview data-aos="fade-up">
					<span class="btn-label"><?php echo esc_html( $cta['title'] ); ?></span>
					<span class="svg-block">
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				</a>
			</div>
		<?php endif; ?>

	</div>

</section>
