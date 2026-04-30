<?php
/**
 * Text Banner ACF block
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
$label            = get_field( 'label' ) ?: 'My Story';
$heading          = get_field( 'heading' ) ?: 'Behind ONE·ONE';
$quote            = get_field( 'quote' ) ?: 'I’m Liudmyla Titova, an IGI-certified Diamond Grader and private jewelry consultant.';
$description      = get_field( 'description' ) ?: 'With access to trusted diamond suppliers and years of experience in customer service, I help clients navigate the world of diamonds and create meaningful jewelry pieces designed around their story.';
$cta              = get_field( 'cta' );
$background_image = get_field( 'background_image' ) ?: DEFAULT_THUMBNAIL_ID;
$portrait_image   = get_field( 'portrait_image' ) ?: DEFAULT_THUMBNAIL_ID;

if ( ! is_array( $cta ) || empty( $cta['url'] ) ) {
	$cta = array(
		'url'    => '#',
		'title'  => 'Read My Story',
		'target' => '',
	);
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="text-banner-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<header class="header">
			<?php if ( $label ) : ?>
				<span class="label"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
		</header>

		<div class="banner">
			<div class="banner-bg" aria-hidden="true">
				<?php
				echo wp_get_attachment_image(
					$background_image,
					'w1920',
					false,
					array(
						'class'   => 'bg-image',
						'loading' => 'lazy',
						'sizes'   => '100vw',
						'alt'     => '',
					)
				);
				?>
				<div class="banner-overlay"></div>
				<?php if ( $portrait_image ) : ?>
					<div class="banner-portrait">
						<?php
						echo wp_get_attachment_image(
							$portrait_image,
							'w800',
							false,
							array(
								'class'   => 'portrait-image',
								'loading' => 'lazy',
								'sizes'   => '(min-width: 1024px) 33vw, 100vw',
								'alt'     => '',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<div class="banner-content">
				<?php if ( $quote ) : ?>
					<p class="quote"><?php echo esc_html( $quote ); ?></p>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<p class="description"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<?php if ( $cta['url'] ) : ?>
					<div class="cta-group">
						<a
							class="btn"
							href="<?php echo esc_url( $cta['url'] ); ?>"
							target="<?php echo esc_attr( $cta['target'] ); ?>"
							<?php echo ( '_blank' === $cta['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>>
							<span class="btn-label"><?php echo esc_html( $cta['title'] ); ?></span>
							<?php if ( '_blank' === $cta['target'] ) : ?>
								<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'skel' ); ?></span>
							<?php endif; ?>
						</a>
						<a
							class="btn btn-icon"
							href="<?php echo esc_url( $cta['url'] ); ?>"
							target="<?php echo esc_attr( $cta['target'] ); ?>"
							<?php echo ( '_blank' === $cta['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>
							aria-hidden="true"
							tabindex="-1">
							<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>

	</div>

</section>
