<?php
/**
 * Plugin Helpers
 *
 * @package Skeleton
 */

// Search WP plugin, allow big words search.
// add_filter( 'searchwp_big_selects', '__return_true' );
// .

// ACF plugin, increase the backend loading performance.
// add_filter( 'acf/settings/remove_wp_meta_box', '__return_true' );
// .

/**
 * Dequeue Styles
 */
// function skel_dequeue_scripts() {
// wp_dequeue_style( 'searchwp-forms' );
// wp_deregister_style( 'searchwp-forms' );
// }
// add_action( 'wp_enqueue_scripts', 'skel_dequeue_scripts' );

/**
 * Gravity Form.
 * Filters the next, previous and submit buttons.
 * Replaces the forms <input> buttons with <button> while maintaining attributes from original <input>.
 *
 * @param  string $button Contains the <input> tag to be filtered.
 * @param  object $form   Contains all the properties of the current form.
 * @return string The filtered button.
 */
function input_to_button( $button, $form ): string {
	$dom = new DOMDocument();
	$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
	$input      = $dom->getElementsByTagName( 'input' )->item( 0 );
	$new_button = $dom->createElement( 'button' );
	$new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
	$input->removeAttribute( 'value' );
	// add custom classes.
	$classes  = $input->getAttribute( 'class' );
	$classes .= ' btn';
	$input->setAttribute( 'class', $classes );
	foreach ( $input->attributes as $attribute ) {
		$new_button->setAttribute( $attribute->name, $attribute->value );
	}
	$input->parentNode->replaceChild( $new_button, $input );

	return $dom->saveHtml( $new_button );
}

add_filter( 'gform_next_button', 'input_to_button', 10, 2 );
add_filter( 'gform_previous_button', 'input_to_button', 10, 2 );
add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );

// Gravity forms plugin, change the submission loading icon.
// add_filter( 'gform_ajax_spinner_url', 'spinner_url', 10, 2 );
// function spinner_url( $image_src, $form ) {
// relative to you theme images folder
// return  'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
// }

// Email Address Encoder plugin, for ACF fields.
// add_filter('acf/load_value', 'eae_encode_emails');
