<?php
/**
 * [Block Name] ACF block
 *
 * Template for creating new ACF blocks.
 * Copy this file to acf-blocks/{slug}.php
 *
 * @package Skeleton
 * @subpackage ACF
 *
 * ACF Fields Structure:
 * - display (true_false): Show/hide block
 * - items (repeater):
 *   - title (text)
 *   - description (wysiwyg)
 *   - image (image, return ID)
 *   - link (link)
 * - spacing (group):
 *   - top (group): spacing_top (select), custom_value (text)
 *   - bottom (group): spacing_bottom (select), custom_value (text)
 * - custom_classes (text)
 * - custom_css (text)
 * - unique_id (text)
 */

// ============================================
// PREVIEW IMAGE (Required for block preview)
// ============================================
if ( isset( $block['data']['preview_image'] ) ) {
	echo '<img src="' . esc_url( $block['data']['preview_image'] ) . '" style="width:100%; height:auto;">';
	return;
}

// ============================================
// DISPLAY CHECK
// ============================================
$display = get_field( 'display' );
if ( 'on' !== $display ) {
	return;
}

// ============================================
// DATA OPTIONS
// ============================================
$section_heading = get_field( 'section_heading' );
$items           = get_field( 'items' );
$button          = get_field( 'button' );

// Early return if no data
if ( ! is_array( $items ) || empty( $items ) ) {
	return;
}

// ============================================
// DEVELOPER OPTIONS
// ============================================
$spacing        = get_field( 'spacing' );
$spacing_top    = $spacing['top']['spacing_top'] ?? '';
$spacing_bottom = $spacing['bottom']['spacing_bottom'] ?? '';
$custom_classes = get_field( 'custom_classes' );
$custom_css     = get_field( 'custom_css' );
$unique_id      = get_field( 'unique_id' );

// Custom Spacing CSS Variables
$spacing_top_custom    = 'custom' === $spacing_top ? "--spacing-top-custom: {$spacing['top']['custom_value']};" : '';
$spacing_bottom_custom = 'custom' === $spacing_bottom ? "--spacing-bottom-custom: {$spacing['bottom']['custom_value']};" : '';
?>

<section
	class="block-name-section section <?php echo esc_attr( "section-display-{$display} {$spacing_top} {$spacing_bottom} {$custom_classes}" ); ?>"
	style="<?php echo esc_attr( "{$spacing_top_custom} {$spacing_bottom_custom} {$custom_css}" ); ?>"
	id="<?php echo esc_attr( $unique_id ); ?>">

	<div class="container">

		<?php if ( $section_heading ) : ?>
			<h2 class="section-heading">
				<?php echo esc_html( $section_heading ); ?>
			</h2>
		<?php endif; ?>

		<div class="items-grid">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$title       = $item['title'] ?? '';
				$description = $item['description'] ?? '';
				$image_id    = $item['image'] ?? '';
				$link        = $item['link'] ?? array();
				?>

				<div class="item">

					<?php if ( $image_id ) : ?>
						<div class="img-cover-block">
							<?php
							echo wp_get_attachment_image(
								$image_id,
								'w768',
								false,
								array(
									'class'   => 'img-cover',
									'sizes'   => '(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw',
									'loading' => 'lazy',
								)
							);
							?>
						</div>
					<?php endif; ?>

					<div class="content-block">
						<?php if ( $title ) : ?>
							<h3 class="item-title h4"><?php echo esc_html( $title ); ?></h3>
						<?php endif; ?>

						<?php if ( $description ) : ?>
							<div class="item-description">
								<?php echo wp_kses_post( $description ); ?>
							</div>
						<?php endif; ?>

						<?php if ( is_array( $link ) && ! empty( $link['url'] ) ) : ?>
							<a
								href="<?php echo esc_url( $link['url'] ); ?>"
								target="<?php echo esc_attr( $link['target'] ); ?>"
								<?php echo ( '_blank' === $link['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>
								class="btn btn-link">
								<?php
								$text = ( $link['title'] ) ? $link['title'] : __( 'Learn More', 'skel' );
								echo '<span>' . esc_html( $text ) . '</span>';
								if ( '_blank' === $link['target'] ) {
									echo '<span class="sr-only">' . esc_html__( '(opens in a new tab)', 'skel' ) . '</span>';
								}
								echo skel_get_svg( 'arrow-right', array( 'aria-hidden' => 'true' ) );
								?>
							</a>
						<?php endif; ?>
					</div>

				</div>

			<?php endforeach; ?>
		</div>

		<?php if ( is_array( $button ) && ! empty( $button['url'] ) ) : ?>
			<div class="section-cta">
				<a
					href="<?php echo esc_url( $button['url'] ); ?>"
					target="<?php echo esc_attr( $button['target'] ); ?>"
					<?php echo ( '_blank' === $button['target'] ) ? 'rel="noopener noreferrer"' : ''; ?>
					class="btn btn-primary btn-lg">
					<?php
					$text = ( $button['title'] ) ? $button['title'] : __( 'View All', 'skel' );
					echo '<span>' . esc_html( $text ) . '</span>';
					if ( '_blank' === $button['target'] ) {
						echo '<span class="sr-only">' . esc_html__( '(opens in a new tab)', 'skel' ) . '</span>';
					}
					?>
				</a>
			</div>
		<?php endif; ?>

	</div>

</section>
