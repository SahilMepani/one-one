<?php
/**
 * ACF Block Helpers
 *
 * @package Skeleton
 * @subpackage ACF
 */

/**
 * Get ACF block paths.
 *
 * @return array
 */
function skel_get_acf_block_paths() {
	return array(
		'blocks' => get_template_directory() . '/blocks/',
	);
}

/**
 * Get block slug from name.
 *
 * @param string $block_name Block name.
 * @return string
 */
function skel_get_block_slug( string $block_name ): string {
	return sanitize_title( $block_name );
}

/**
 * Handle preview image rendering for ACF blocks.
 */
function skel_render_block_preview( array $block ): bool {
	if ( isset( $block['data']['preview_image'] ) ) {
		echo '<img src="' . esc_url( $block['data']['preview_image'] ) . '" style="width:100%; height:auto;">';
		return true;
	}
	return false;
}

/**
 * Check if block should display.
 * Defaults to true when the field has no saved value (new block instances).
 */
function skel_should_display_block(): bool {
	$display = get_field( 'display' );
	return 'off' !== ( $display ?: 'on' );
}

/**
 * Get developer options for block.
 */
function skel_get_block_developer_options(): array {
	$display        = get_field( 'display' );
	$spacing        = get_field( 'spacing' );
	$spacing        = is_array( $spacing ) ? $spacing : array();
	$spacing_top    = $spacing['top']['spacing_top'] ?? '';
	$spacing_bottom = $spacing['bottom']['spacing_bottom'] ?? '';

	$spacing_top_custom    = '';
	$spacing_bottom_custom = '';

	if ( 'custom' === $spacing_top ) {
		$top_mobile         = (int) ( $spacing['top']['custom_value_top_mobile'] ?? 0 );
		$top_desktop        = (int) ( $spacing['top']['custom_value_top_desktop'] ?? 0 );
		$spacing_top_custom = "--spacing-top-mobile: {$top_mobile}; --spacing-top-desktop: {$top_desktop};";
		$spacing_top        = 'spacing-top-custom';
	}
	if ( 'custom' === $spacing_bottom ) {
		$bottom_mobile         = (int) ( $spacing['bottom']['custom_value_bottom_mobile'] ?? 0 );
		$bottom_desktop        = (int) ( $spacing['bottom']['custom_value_bottom_desktop'] ?? 0 );
		$spacing_bottom_custom = "--spacing-bottom-mobile: {$bottom_mobile}; --spacing-bottom-desktop: {$bottom_desktop};";
		$spacing_bottom        = 'spacing-bottom-custom';
	}

	return array(
		'display_class'         => 'off' === ( $display ?: 'on' ) ? 'section-display-off' : 'section-display-on',
		'spacing_top'           => $spacing_top,
		'spacing_bottom'        => $spacing_bottom,
		'spacing_top_custom'    => $spacing_top_custom,
		'spacing_bottom_custom' => $spacing_bottom_custom,
		'custom_classes'        => get_field( 'custom_classes' ),
		'custom_css'            => get_field( 'custom_css' ),
		'unique_id'             => get_field( 'unique_id' ),
	);
}

/**
 * Get background image CSS string.
 */
function skel_get_background_image_css( int $image_id, string $size = 'medium_crop' ): string {
	if ( $image_id ) {
		$image_url = wp_get_attachment_image_url( $image_id, $size );
	} else {
		$image_url = get_template_directory_uri() . '/assets/images/placeholder.png';
	}
	return 'background-image: url(' . esc_url( $image_url ) . ');';
}

/**
 * Render block section opening tag with developer options.
 *
 * @param array  $dev_options   Developer options array from skel_get_block_developer_options().
 * @param string $section_class Additional section class names.
 */
function skel_render_block_section_open( array $dev_options, string $section_class = '' ): void {
	$classes = esc_attr( trim( "{$section_class} section {$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ) );

	$style_parts = array_filter( array(
		$dev_options['spacing_top_custom'],
		$dev_options['spacing_bottom_custom'],
		wp_strip_all_tags( $dev_options['custom_css'] ),
	) );
	$style_attr = $style_parts ? sprintf( ' style="%s"', esc_attr( implode( ' ', $style_parts ) ) ) : '';

	$id_attr = $dev_options['unique_id'] ? sprintf( ' id="%s"', esc_attr( $dev_options['unique_id'] ) ) : '';

	printf( '<section class="%s"%s%s>', $classes, $style_attr, $id_attr );
}

/**
 * Render ACF link field as HTML anchor tag.
 *
 * @param array  $link         ACF link field array.
 * @param string $classes      CSS classes for the link. Default 'btn'.
 * @param string $default_text Default text if link title is empty.
 */
function skel_render_acf_link( $link, string $classes = 'btn', string $default_text = '' ): void {
	if ( ! is_array( $link ) || empty( $link['url'] ) ) {
		return;
	}
	printf(
		'<a href="%s" target="%s" class="%s"><span>%s</span></a>',
		esc_url( $link['url'] ),
		esc_attr( $link['target'] ?? '_self' ),
		esc_attr( $classes ),
		esc_html( $link['title'] ?: $default_text )
	);
}
