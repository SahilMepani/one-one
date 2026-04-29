<?php
/**
 * The header.
 *
 * This file contains filters and actions for various purpose
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Initialize WordPress Filesystem API.
 *
 * @return WP_Filesystem_Base|null The filesystem object or null on failure.
 */
function skel_init_filesystem() {
	global $wp_filesystem;
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
	}
	return $wp_filesystem;
}

/**
 * Retrieves the thumbnail ID for a given post.
 *
 * If the specified post has a featured image (thumbnail), its ID is returned.
 * Otherwise, a default image thumbnail ID is returned.
 *
 * @param int $post_id The ID of the post to retrieve the thumbnail for.
 * @return int The ID of the post thumbnail or the default thumbnail ID (2231).
 */
function skel_get_post_thumbnail_id( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_post_thumbnail_id( $post_id );
	}
	return DEFAULT_THUMBNAIL_ID ?: 0;
}

/**
 * Check if the current page is the login or registration page.
 *
 * @return bool True if the current page is the login or registration page, false otherwise.
 */
function is_login_or_registration_page(): bool {
	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ), true );
}

/**
 * Display pagination for posts.
 * http://wp.tutsplus.com/tutorials/wordpress-pagination-a-primer
 *
 * @param int $total_pages The total number of pages.
 */
function skel_posts_pagination( int $total_pages ): void {
	if ( 1 < $total_pages ) {
		$current_page = max( 1, get_query_var( 'paged' ) );

		echo '<nav class="posts-pagination" role="navigation" aria-label="' . esc_attr__( 'Posts Pagination', 'skel' ) . '">';

		$big = 999999999; // A large number for replacing in the pagination link.
		// phpcs:ignore -- Allow non escaping html
		echo paginate_links(
			array(
				'base'       => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'     => '?paged=%#%',
				'current'    => $current_page,
				'total'      => $total_pages,
				'prev_text'  => skel_get_svg( 'arrow-left', array( 'aria-hidden' => 'true' ) ) . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'skel' ) . '</span>',
				'next_text'  => skel_get_svg( 'arrow-right', array( 'aria-hidden' => 'true' ) ) . '<span class="screen-reader-text">' . esc_html__( 'Next page', 'skel' ) . '</span>',
				'mid_size'   => 1,
				'start_size' => 0,
				'end_size'   => 0,
			)
		);

		echo '</nav>';
	}
}

/**
 * Check if the current language is RTL (right-to-left).
 *
 * This function checks if the current language is Arabic (ar) using WPML.
 *
 * @requires WPML plugin
 * @return bool True if the language is RTL, false otherwise.
 */
function skel_is_rtl(): bool {
	return defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE === 'ar';
}

/**
 * Retrieve the Swiper direction-based class for slides.
 *
 * This function returns a class name based on the language direction for Swiper slides.
 * If the current language is English ('en'), it returns 'swiper' for left-to-right direction.
 * If the current language is Arabic ('ar'), it returns 'swiper-rtl' for right-to-left direction.
 * This function requires the WPML plugin to work properly.
 *
 * @requires WPML plugin
 * @return string The class name for the Swiper direction.
 */
function skel_swiper_direction_class(): string {
	return skel_is_rtl() ? 'swiper-rtl' : 'swiper';
}

/**
 * Retrieve the language direction-based class for elements.
 *
 * This function returns a class name based on the language direction.
 * If the current language is English ('en'), it returns 'dir-ltr' for left-to-right direction.
 * If the current language is Arabic ('ar'), it returns 'dir-rtl' for right-to-left direction.
 * This function requires the WPML plugin to work properly.
 *
 * @requires WPML plugin
 * @return string The class name for the language direction.
 */
function skel_direction_class(): string {
	return skel_is_rtl() ? 'dir-rtl' : 'dir-ltr';
}

/**
 * Validates and retrieves a YouTube video link from a given input string.
 *
 * This function extracts YouTube video IDs from various URL formats including:
 * - Standard watch URLs: https://www.youtube.com/watch?v=VIDEO_ID
 * - Short URLs: https://youtu.be/VIDEO_ID
 * - Embed URLs: https://www.youtube.com/embed/VIDEO_ID
 * - URLs with parameters: https://www.youtube.com/watch?v=VIDEO_ID&t=10s
 *
 * @param string $link The input string containing a potential YouTube video link.
 * @return string|false The valid YouTube video link in standard format, or false if invalid.
 */
function skel_get_validate_youtube_link( string $link ): string|false {
	// Return false for empty strings.
	if ( empty( trim( $link ) ) ) {
		return false;
	}

	// Comprehensive regex pattern to extract video IDs from various YouTube URL formats.
	// Supports video IDs with letters, numbers, underscores, and hyphens (standard YouTube ID format).
	$pattern = '#(?:https?://)?(?:www\.)?(?:youtube\.com/(?:watch\?v=|embed/|v/)|youtu\.be/)([a-zA-Z0-9_-]{11})#i';

	// Check if the input string matches a valid YouTube URL format.
	if ( preg_match( $pattern, $link, $matches ) ) {
		// Construct and return the standardized YouTube watch URL.
		return 'https://www.youtube.com/watch?v=' . $matches[1];
	}

	// Fallback pattern for edge cases (v= or after last slash).
	$fallback_pattern = '#(?:v=|/)([a-zA-Z0-9_-]{11})(?:[&\?]|$)#';
	if ( preg_match( $fallback_pattern, $link, $matches ) ) {
		return 'https://www.youtube.com/watch?v=' . $matches[1];
	}

	// If no valid YouTube video link found, return false.
	return false;
}

/**
 * Generates a valid YouTube video link from a given YouTube video URL.
 *
 * This is an alias/wrapper for skel_get_validate_youtube_link() for backward compatibility.
 *
 * @param string $url The YouTube video URL from which to extract the video ID.
 * @return string The valid YouTube video link generated from the provided URL.
 */
function skel_get_yt_link( string $url ): string {
	$result = skel_get_validate_youtube_link( $url );
	// Return the validated link or the original URL if validation fails.
	return false !== $result ? $result : $url;
}

/**
 * Random String Generator
 *
 * @param int $length   The maximum number of characters.
 * @return string
 */
function skel_get_random_string( int $length = 10 ): string {
	return substr( wp_generate_password( $length, false, false ), 0, $length );
}


/**
 * Retrieves a customized excerpt for a specified post.
 *
 * This function retrieves the excerpt of the specified post and customizes it based on the provided limit.
 * It truncates the excerpt to the specified word limit and adds ellipsis (...) if the excerpt exceeds the limit.
 * Additionally, it removes any shortcodes from the excerpt before returning it.
 *
 * @param int $limit   The maximum number of words in the excerpt.
 * @param int $post_id The ID of the post for which to retrieve the excerpt.
 * @return string The customized excerpt of the specified post.
 */
function skel_get_the_excerpt( int $limit = 50, ?int $post_id = null ): string {

	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}

	// Retrieve the excerpt of the specified post.
	$excerpt = explode( ' ', get_the_excerpt( $post_id ), $limit );

	// If the excerpt exceeds the limit, truncate and add ellipsis.
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( ' ', $excerpt ) . '...';
	} else {
		$excerpt = implode( ' ', $excerpt );
	}

	// Remove any shortcodes from the excerpt before returning.
	$excerpt = strip_shortcodes( $excerpt );

	return $excerpt;
}


/**
 * Shorten a text string to a specified length, preserving whole words.
 *
 * This function shortens the given text string to the specified length while preserving whole words.
 * If the text is already shorter than the specified length, it is returned as is.
 * If the text needs to be shortened, it finds the last space within the specified length and trims the text up to that space.
 * It then appends ellipses (...) to indicate that the text has been shortened.
 * Uses multibyte string functions for proper UTF-8 handling.
 *
 * @param  string $input  The input text string to be shortened.
 * @param  int    $length The maximum length of the shortened text.
 * @return string The shortened text string.
 */
function skel_get_text_shorter( string $input, int $length ): string {
	// No need to trim, already shorter than trim length.
	if ( mb_strlen( $input ) <= $length ) {
		return $input;
	}

	// Find last space within length.
	$trimmed    = mb_substr( $input, 0, $length );
	$last_space = mb_strrpos( $trimmed, ' ' );

	if ( false !== $last_space && $last_space > 0 ) {
		$trimmed = mb_substr( $trimmed, 0, $last_space );
	}

	// Add ellipses (...).
	return $trimmed . '...';
}

/**
 * Retrieve terms data.
 *
 * This function retrieves the terms associated with a specified post and taxonomy
 * and returns an array containing term names and IDs.
 *
 * @param  int    $post_id   The ID of the post for which to retrieve the terms.
 * @param  string $taxonomy  The taxonomy from which to retrieve the terms.
 * @return array An array containing term names and IDs.
 */
function skel_get_the_terms_data( int $post_id, string $taxonomy ): array {
	// Retrieve term objects associated with the post and taxonomy.
	$terms = get_the_terms( $post_id, $taxonomy );

	// Return early if no terms found or error occurred.
	if ( ! is_array( $terms ) || is_wp_error( $terms ) ) {
		return array();
	}

	// Map terms to structured array with name and ID.
	return array_map(
		function ( $term ) {
			return array(
				'name' => $term->name,
				'id'   => $term->term_id,
			);
		},
		$terms
	);
}

/**
 * Return terms without link.
 *
 * This function retrieves the terms associated with a specified post and taxonomy
 * and returns them as a string without any HTML links.
 *
 * @param  int    $post_id   The ID of the post for which to retrieve the terms.
 * @param  string $taxonomy  The taxonomy from which to retrieve the terms.
 * @param  string $separator (Optional) The separator to use between terms. Default is a single space.
 * @return string The terms associated with the specified post, separated by the specified separator.
 */
function skel_get_the_terms( int $post_id, string $taxonomy, string $separator = ' ' ): string {
	$terms_data = skel_get_the_terms_data( $post_id, $taxonomy );

	if ( empty( $terms_data ) ) {
		return '';
	}

	// Extract names and join with separator.
	return implode( $separator, array_column( $terms_data, 'name' ) );
}

/**
 * Generate a URL for a phone number.
 *
 * This function takes a phone number and removes any non-numeric characters such as parentheses, hyphens, dots, etc.
 * Then it constructs a tel: URL with the sanitized phone number and returns it.
 *
 * @param  string|false $phone_number (Optional) The phone number to generate the URL for. If not provided or false, an empty string is returned.
 * @return string       The URL for the phone number in the format tel:phonenumber.
 */
function skel_get_phone_url( string|false $phone_number = false ): string {
	if ( false === $phone_number || '' === $phone_number ) {
		return '';
	}

	// Keep only digits, plus sign, and remove all other characters.
	$phone_number = preg_replace( '/[^\d+]/', '', $phone_number );

	return esc_url( 'tel:' . $phone_number );
}


/**
 * Retrieve the content of a specified page and apply content filters.
 *
 * This function retrieves the content of the specified page by its ID using `get_post()`.
 * It then applies the content filters using `apply_filters('the_content', $content)` to ensure
 * that any necessary transformations are applied, such as shortcode processing.
 *
 * @param int  $id The ID of the page to retrieve and insert into another page.
 * @param bool $display Whether to echo the output. Default is false.
 * @return string|null The content of the specified page,
 * or null if the page with the provided ID does not exist.
 */
function skel_insert_page( int $id, bool $display = false ): ?string {
	// Retrieve the post object based on the provided ID.
	$post   = get_post( $id );
	$output = null;

	// If the post exists, retrieve its content and apply content filters.
	if ( $post ) {
		$output = apply_filters( 'the_content', $post->post_content );
	}

	if ( $display ) {
		echo wp_kses_post( $output );
	}

	return $output;
}


/**
 * Extracts the src attribute value from an oembed ACF field.
 *
 * This function uses a regular expression to find the src attribute in an iframe tag
 * and returns its value. If no src attribute is found, it returns null.
 *
 * @param string|null $html The HTML string containing the iframe element.
 *
 * @return string|null The value of the src attribute, or null if not found.
 */
function skel_extract_oembed_src( ?string $html ): ?string {
	if ( ! $html ) {
		return null;
	}

	// Regular expression to match the src attribute in the iframe tag.
	$regex = '/<iframe[^>]*src=["\']([^"\']+)["\']/';

	// Check if there's a match.
	if ( preg_match( $regex, $html, $matches ) ) {
		// Return the first capture group, which is the value of the src attribute.
		$src = $matches[1];

		// Replace youtube.com with youtube-nocookie.com.
		$src = str_replace( 'youtube.com', 'youtube-nocookie.com', $src );

		// Remove the feature=oembed query parameter.
		$src = remove_query_arg( 'feature', $src );

		// Return the modified src attribute.
		return $src;
	}

	// Return null if no match is found.
	return null;
}

/**
 * Get the full URL of the current request.
 *
 * Safely constructs the full URL based on the current request, ensuring
 * all server variables are checked, unslashed, and sanitized.
 *
 * @since 1.0.0
 *
 * @return string The full URL of the current request.
 */
function skel_get_full_url(): string {
	// Safely get and sanitize the request URI.
	$server_uri = isset( $_SERVER['REQUEST_URI'] )
		? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) )
		: '';

	// Safely get and sanitize the server port.
	$server_port = isset( $_SERVER['SERVER_PORT'] )
		? absint( wp_unslash( $_SERVER['SERVER_PORT'] ) )
		: 80;

	// Safely get and sanitize the host.
	$server_host = isset( $_SERVER['HTTP_HOST'] )
		? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) )
		: 'localhost';

	// Safely determine the protocol.
	$https_flag = isset( $_SERVER['HTTPS'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTPS'] ) ) : '';
	$protocol   = ( ! empty( $https_flag ) && 'off' !== strtolower( $https_flag ) ) || 443 === $server_port
		? 'https://'
		: 'http://';

	return esc_url_raw( $protocol . $server_host . $server_uri );
}


/**
 * Replaces a text placeholder with an icon HTML in the provided text.
 *
 * This function searches for the placeholder '[i-play]' in the given text
 * and replaces it with the corresponding HTML for an icon. If the echo
 * parameter is set to true, it also outputs the modified text.
 *
 * @param string $text The text in which to replace the placeholder with an icon.
 * @return void
 */
function skel_replace_text_with_icon( string $text ): void {
	// Check if the text is not falsy.
	if ( ! $text ) {
		return;
	}

	// Escape text first, then replace placeholders with icon HTML.
	$output = esc_html( $text );
	$output = str_replace( '[play]', '<i class="i-play"></i>', $output );
	$output = str_replace( '[play-image]', '<i class="i-play w-image"></i>', $output );

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $output;
}

/**
 * Sanitize superglobal values safely.
 *
 * Note: Caller is responsible for nonce verification when appropriate.
 *
 * @param string $key     The superglobal key to retrieve.
 * @param string $type    The superglobal type ('GET' or 'POST'). Default 'POST'.
 * @param string $default Default value if key doesn't exist. Default empty string.
 * @return string Sanitized value or default.
 */
function skel_sanitize_superglobal( string $key, string $type = 'POST', string $default = '' ): string {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing
	$superglobal = 'GET' === $type ? $_GET : $_POST;
	return isset( $superglobal[ $key ] )
		? sanitize_text_field( wp_unslash( $superglobal[ $key ] ) )
		: $default;
}


/**
 * Determine whether a hex color is dark (needs light text).
 *
 * Uses WCAG 2.0 relative luminance to decide contrast. Returns true when
 * the luminance is at or below 0.179 (~4.5:1 ratio against white).
 *
 * Handles #-prefixed and bare hex, 3-digit and 6-digit formats.
 *
 * @param string $hex Hex color value, e.g. '#d2fe45', '212368', '#fff'.
 * @return bool True if the color is dark, false otherwise (including invalid input).
 */
function skel_is_dark_color( string $hex ): bool {
	$hex = ltrim( $hex, '#' );

	// Expand 3-digit shorthand to 6-digit.
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	// Must be exactly 6 hex characters.
	if ( ! preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}

	$r = hexdec( substr( $hex, 0, 2 ) ) / 255;
	$g = hexdec( substr( $hex, 2, 2 ) ) / 255;
	$b = hexdec( substr( $hex, 4, 2 ) ) / 255;

	// sRGB to linear conversion per WCAG 2.0.
	$r = $r <= 0.03928 ? $r / 12.92 : pow( ( $r + 0.055 ) / 1.055, 2.4 );
	$g = $g <= 0.03928 ? $g / 12.92 : pow( ( $g + 0.055 ) / 1.055, 2.4 );
	$b = $b <= 0.03928 ? $b / 12.92 : pow( ( $b + 0.055 ) / 1.055, 2.4 );

	$luminance = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;

	return $luminance <= 0.179;
}

/**
 * Convert {text} to <i>text</i>, [text] to <strong>text</strong>, and sanitize.
 *
 * @param string $text Raw text potentially containing {braces} for italic or [brackets] for bold.
 * @return string Sanitized HTML with <i> and <strong> tags.
 */
/**
 * Limit WP Revisions
 */
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', 5 );
}

function skel_get_italic_braces( $text ) {
	$text = preg_replace( '/\{(.+?)\}/', '<i class="ff-serif">$1</i>', $text );
	$text = preg_replace( '/\[(.+?)\]/', '<strong>$1</strong>', $text );
	return wp_kses(
		$text,
		array(
			'i'      => array( 'class' => array() ),
			'strong' => array(),
			'br'     => array(),
			'span'   => array( 'class' => array() ),
		)
	);
}
