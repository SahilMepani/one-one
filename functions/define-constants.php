<?php // phpcs:ignore file comment
/**
 * Define global constants based on ACF option fields.
 *
 * This function retrieves global option values from the ACF Options Page
 * and defines them as constants for use throughout the theme or plugin.
 *
 * Hooked into the 'acf/init' action to ensure that ACF functions
 * (like get_field) are available before this runs.
 *
 * Constants defined (0 if the ACF field is empty):
 * - DEFAULT_THUMBNAIL_ID : The default featured image ID.
 * - PAGE_404_ID          : The page ID used for the 404 error page.
 * - PAGE_SEARCH_ID       : The page ID used for the search results page.
 * - PAGE_KB_ID           : The page ID used for the knowledge base page.
 *
 * @since 1.0.0
 *
 * @return void
 */
function define_constants() {
	$constants = array(
		'DEFAULT_THUMBNAIL_ID' => get_field( 'default_featured_image', 'option' ) ?: 0,
		'PAGE_404_ID'          => get_field( 'four_four_page', 'option' ) ?: 0,
		'PAGE_SEARCH_ID'       => get_field( 'search_page', 'option' ) ?: 0,
		'PAGE_KB_ID'           => get_field( 'kb_page', 'option' ) ?: 0,
	);

	foreach ( $constants as $name => $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
}

add_action( 'acf/init', 'define_constants' );
