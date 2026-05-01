<?php
/**
 * Cards ACF block
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
$description = get_field( 'description' ) ?: 'Below you will find the essential guides used by professionals in the jewelry industry.';
$items       = get_field( 'items' ) ?: array(
	array(
		'image'    => DEFAULT_THUMBNAIL_ID,
		'title'    => "The 4Cs of\nDiamonds",
		'date'     => 'April 5, 2035',
		'category' => 'Common Mistakes',
		'link'     => array(
			'url'    => '#',
			'title'  => 'Read Article',
			'target' => '',
		),
	),
	array(
		'image'    => DEFAULT_THUMBNAIL_ID,
		'title'    => "Diamond\nShapes Guide",
		'date'     => 'April 5, 2035',
		'category' => 'Common Mistakes',
		'link'     => array(
			'url'    => '#',
			'title'  => 'Read Article',
			'target' => '',
		),
	),
	array(
		'image'    => DEFAULT_THUMBNAIL_ID,
		'title'    => "Diamond Certification\n(GIA vs IGI)",
		'date'     => 'April 5, 2035',
		'category' => 'Common Mistakes',
		'link'     => array(
			'url'    => '#',
			'title'  => 'Read Article',
			'target' => '',
		),
	),
	array(
		'image'    => DEFAULT_THUMBNAIL_ID,
		'title'    => 'Natural vs Lab-Grown Diamonds',
		'date'     => 'April 5, 2035',
		'category' => 'Common Mistakes',
		'link'     => array(
			'url'    => '#',
			'title'  => 'Read Article',
			'target' => '',
		),
	),
);

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="cards-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<?php if ( $description ) : ?>
			<p class="description"><?php echo wp_kses_post( $description ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="cards">
				<?php foreach ( $items as $item ) : ?>
					<article class="card">
						<div class="image">
							<?php
							echo wp_get_attachment_image(
								$item['image'] ?: DEFAULT_THUMBNAIL_ID,
								'w800',
								false,
								array(
									'class'   => 'img-responsive',
									'loading' => 'lazy',
									'sizes'   => '(min-width: 1024px) 25vw, 100vw',
									'alt'     => '',
								)
							);
							?>
						</div>

						<div class="body">
							<div class="header">
								<?php if ( ! empty( $item['title'] ) ) : ?>
									<h3 class="title"><?php echo wp_kses_post( $item['title'] ); ?></h3>
								<?php endif; ?>

								<?php if ( ! empty( $item['date'] ) || ! empty( $item['category'] ) ) : ?>
									<div class="meta">
										<?php if ( ! empty( $item['date'] ) ) : ?>
											<span class="date"><?php echo esc_html( $item['date'] ); ?></span>
										<?php endif; ?>

										<?php if ( ! empty( $item['date'] ) && ! empty( $item['category'] ) ) : ?>
											<span class="meta-divider" aria-hidden="true"></span>
										<?php endif; ?>

										<?php if ( ! empty( $item['category'] ) ) : ?>
											<span class="category"><?php echo esc_html( $item['category'] ); ?></span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( ! empty( $item['link']['url'] ) ) : ?>
								<a
									href="<?php echo esc_url( $item['link']['url'] ); ?>"
									target="<?php echo esc_attr( $item['link']['target'] ); ?>"
									<?php echo ( '_blank' === $item['link']['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>
									class="card-link">
									<span><?php echo esc_html( $item['link']['title'] ?: __( 'Read Article', 'skel' ) ); ?></span>
									<?php echo skel_get_svg( 'arrow-right', array( 'aria-hidden' => 'true' ) ); ?>
									<?php if ( '_blank' === $item['link']['target'] ) : ?>
										<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'skel' ); ?></span>
									<?php endif; ?>
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>

</section>
