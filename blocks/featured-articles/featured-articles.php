<?php
/**
 * Featured Articles ACF block
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
$subheading = get_field( 'subheading' ) ?: 'Education Hub';
$heading    = get_field( 'heading' ) ?: 'Jewelry Knowledge, shared';
$cta_link   = get_field( 'cta_link' ) ?: array(
	'url'    => '#',
	'title'  => 'Explore More',
	'target' => '',
);
$items      = get_field( 'items' ) ?: array(
	array(
		'image'       => DEFAULT_THUMBNAIL_ID,
		'title'       => "Diamond Basics (4C's simplified)",
		'description' => 'Before selecting a diamond, it is important to understand the fundamental characteristics that determine its beauty and value.',
		'date'        => 'May 1, 2026',
		'category'    => 'Diamonds',
		'link'        => array(
			'url'    => '#',
			'title'  => 'Explore the Guide',
			'target' => '',
		),
	),
	array(
		'image'       => DEFAULT_THUMBNAIL_ID,
		'title'       => 'Size Guide',
		'description' => '',
		'date'        => 'April 5, 2026',
		'category'    => 'Diamonds',
		'link'        => array(
			'url'    => '#',
			'title'  => 'Read Guide',
			'target' => '',
		),
	),
	array(
		'image'       => DEFAULT_THUMBNAIL_ID,
		'title'       => 'Natural vs Lab-Grown Diamonds',
		'description' => '',
		'date'        => 'March 16, 2026',
		'category'    => 'Diamonds',
		'link'        => array(
			'url'    => '#',
			'title'  => 'Read Guide',
			'target' => '',
		),
	),
);

$featured = array_shift( $items );
$rest     = $items;

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="featured-articles-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<header class="header">
			<?php if ( $subheading ) : ?>
				<span class="subheading"><?php echo esc_html( $subheading ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="heading"><?php echo wp_kses_post( $heading ); ?></h2>
			<?php endif; ?>
		</header>

		<?php if ( ! empty( $cta_link['url'] ) ) : ?>
			<div class="cta-group">
				<a class="btn btn-icon btn-dark btn-md" href="<?php echo esc_url( $cta_link['url'] ); ?>" target="<?php echo esc_attr( $cta_link['target'] ); ?>" data-inview data-aos="fade-up">
					<span class="btn-label"><?php echo esc_html( $cta_link['title'] ); ?></span>
					<span class="svg-block">
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				</a>
			</div>
		<?php endif; ?>

		<div class="cards">

			<?php if ( $featured ) : ?>
				<article class="card card--featured">
					<div class="card-image">
						<?php
						echo wp_get_attachment_image(
							$featured['image'] ?: DEFAULT_THUMBNAIL_ID,
							'w1200',
							false,
							array(
								'class'   => 'img-responsive',
								'loading' => 'lazy',
								'sizes'   => '(min-width: 1024px) 50vw, 100vw',
								'alt'     => '',
							)
						);
						?>
					</div>

					<div class="card-content">
						<div class="card-top">
							<div class="card-text">
								<?php if ( ! empty( $featured['title'] ) ) : ?>
									<h3 class="title"><?php echo esc_html( $featured['title'] ); ?></h3>
								<?php endif; ?>

								<?php if ( ! empty( $featured['description'] ) ) : ?>
									<p class="description"><?php echo wp_kses_post( $featured['description'] ); ?></p>
								<?php endif; ?>
							</div>

							<?php if ( ! empty( $featured['date'] ) || ! empty( $featured['category'] ) ) : ?>
								<div class="meta">
									<?php if ( ! empty( $featured['date'] ) ) : ?>
										<span class="date"><?php echo esc_html( $featured['date'] ); ?></span>
									<?php endif; ?>

									<?php if ( ! empty( $featured['date'] ) && ! empty( $featured['category'] ) ) : ?>
										<span class="meta-divider" aria-hidden="true"></span>
									<?php endif; ?>

									<?php if ( ! empty( $featured['category'] ) ) : ?>
										<span class="category"><?php echo esc_html( $featured['category'] ); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $featured['link']['url'] ) ) : ?>
							<a
								href="<?php echo esc_url( $featured['link']['url'] ); ?>"
								target="<?php echo esc_attr( $featured['link']['target'] ); ?>"
								<?php echo ( '_blank' === $featured['link']['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>
								class="card-link">
								<span><?php echo esc_html( $featured['link']['title'] ?: __( 'Explore the Guide', 'skel' ) ); ?></span>
								<?php echo skel_get_svg( 'arrow-right', array( 'aria-hidden' => 'true' ) ); ?>
								<?php if ( '_blank' === $featured['link']['target'] ) : ?>
									<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'skel' ); ?></span>
								<?php endif; ?>
							</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endif; ?>

			<?php if ( ! empty( $rest ) ) : ?>
				<div class="cards-row">
					<?php foreach ( $rest as $item ) : ?>
						<article class="card card--small">
							<div class="card-image">
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

							<div class="card-content">
								<div class="card-top">
									<?php if ( ! empty( $item['title'] ) ) : ?>
										<h3 class="title"><?php echo esc_html( $item['title'] ); ?></h3>
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
										<span><?php echo esc_html( $item['link']['title'] ?: __( 'Read Guide', 'skel' ) ); ?></span>
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

	</div>

</section>
