<?php
/**
 * CTA Banner ACF block
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
$label            = get_field( 'label' ) ?: 'Begin Your Experience';
$heading          = get_field( 'heading' ) ?: 'Start your Project';
$description      = get_field( 'description' ) ?: "If you are considering creating a custom jewelry piece or would like guidance selecting the right diamond, I would be happy to assist you.\n\nEvery project begins with a conversation.";
$cta              = get_field( 'cta' );
$background_image = get_field( 'background_image' ) ?: DEFAULT_THUMBNAIL_ID;

if ( ! is_array( $cta ) || empty( $cta['url'] ) ) {
	$cta = array(
		'url'    => '#',
		'title'  => 'Start Your Project',
		'target' => '',
	);
}

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="cta-banner-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="background" aria-hidden="true">
		<?php
		echo wp_get_attachment_image(
			$background_image,
			'w1920',
			false,
			array(
				'class'   => 'background-image',
				'loading' => 'lazy',
				'sizes'   => '100vw',
				'alt'     => '',
			)
		);
		?>
	</div>

	<div class="container">

		<div class="card">

			<header class="header">
				<?php if ( $label ) : ?>
					<span class="label"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<div class="description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
				<?php endif; ?>
			</header>

			<?php if ( $cta['url'] ) : ?>
				<div class="cta-group">
					<a
						class="btn-cta"
						href="<?php echo esc_url( $cta['url'] ); ?>"
						target="<?php echo esc_attr( $cta['target'] ); ?>"
						<?php echo ( '_blank' === $cta['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>>
						<span class="btn-label"><?php echo esc_html( $cta['title'] ?: __( 'Start Your Project', 'skel' ) ); ?></span>
						<?php if ( '_blank' === $cta['target'] ) : ?>
							<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'skel' ); ?></span>
						<?php endif; ?>
					</a>
					<a
						class="btn-cta btn-icon-only"
						href="<?php echo esc_url( $cta['url'] ); ?>"
						target="<?php echo esc_attr( $cta['target'] ); ?>"
						<?php echo ( '_blank' === $cta['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>
						aria-hidden="true"
						tabindex="-1">
						<span class="svg-block">
							<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
					</a>
				</div>
			<?php endif; ?>

		</div>

	</div>

</section>
