<?php
/**
 * SVG Helper Functions
 *
 * @package Skeleton
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize SVG content by removing potentially dangerous elements and attributes.
 *
 * @param  string $svg_content The raw SVG content to sanitize.
 * @return string|false The sanitized SVG content, or FALSE on failure.
 */
function skel_sanitize_svg( string $svg_content ): string|false {
	// Allowed SVG elements.
	$allowed_elements = array(
		'svg',
		'g',
		'path',
		'rect',
		'circle',
		'ellipse',
		'line',
		'polyline',
		'polygon',
		'text',
		'tspan',
		'textpath',
		'defs',
		'symbol',
		'use',
		'clippath',
		'mask',
		'pattern',
		'lineargradient',
		'radialgradient',
		'stop',
		'filter',
		'feblend',
		'fecolormatrix',
		'fecomponenttransfer',
		'fecomposite',
		'feconvolvematrix',
		'fediffuselighting',
		'fedisplacementmap',
		'feflood',
		'fegaussianblur',
		'feimage',
		'femerge',
		'femergenode',
		'femorphology',
		'feoffset',
		'fespecularlighting',
		'fetile',
		'feturbulence',
		'title',
		'desc',
		'metadata',
	);

	// Allowed attributes (safe subset).
	$allowed_attributes = array(
		'id',
		'class',
		'style',
		'fill',
		'stroke',
		'stroke-width',
		'stroke-linecap',
		'stroke-linejoin',
		'stroke-dasharray',
		'stroke-dashoffset',
		'stroke-miterlimit',
		'stroke-opacity',
		'fill-opacity',
		'fill-rule',
		'opacity',
		'transform',
		'viewbox',
		'width',
		'height',
		'x',
		'y',
		'x1',
		'y1',
		'x2',
		'y2',
		'cx',
		'cy',
		'r',
		'rx',
		'ry',
		'd',
		'points',
		'preserveaspectratio',
		'xmlns',
		'xmlns:xlink',
		'version',
		'clip-path',
		'clip-rule',
		'mask',
		'filter',
		'gradientunits',
		'gradienttransform',
		'spreadmethod',
		'offset',
		'stop-color',
		'stop-opacity',
		'patternunits',
		'patterntransform',
		'dx',
		'dy',
		'rotate',
		'textlength',
		'lengthadjust',
		'dominant-baseline',
		'text-anchor',
		'font-family',
		'font-size',
		'font-weight',
		'font-style',
		'letter-spacing',
		'word-spacing',
		'text-decoration',
		'alignment-baseline',
		'baseline-shift',
		'vector-effect',
		'display',
		'visibility',
		'color',
		'overflow',
		'marker-start',
		'marker-mid',
		'marker-end',
		'aria-hidden',
		'aria-label',
		'aria-labelledby',
		'aria-describedby',
		'role',
		'focusable',
		'tabindex',
		'data-*',
	);

	// Use libxml error handling.
	$use_errors = libxml_use_internal_errors( true );

	$dom = new DOMDocument();
	// Suppress warnings and load as XML.
	if ( ! $dom->loadXML( $svg_content, LIBXML_NONET | LIBXML_NOBLANKS ) ) {
		libxml_clear_errors();
		libxml_use_internal_errors( $use_errors );
		return false;
	}

	// Get the root element.
	$root = $dom->documentElement;
	if ( ! $root || 'svg' !== strtolower( $root->nodeName ) ) {
		libxml_use_internal_errors( $use_errors );
		return false;
	}

	// Recursively sanitize elements.
	skel_sanitize_svg_element( $root, $allowed_elements, $allowed_attributes );

	// Save and return the sanitized SVG.
	$sanitized = $dom->saveXML( $root );

	libxml_use_internal_errors( $use_errors );

	return $sanitized ? $sanitized : false;
}

/**
 * Recursively sanitize an SVG DOM element.
 *
 * @param  DOMElement $element            The element to sanitize.
 * @param  array      $allowed_elements   List of allowed element names.
 * @param  array      $allowed_attributes List of allowed attribute names.
 * @return void
 */
function skel_sanitize_svg_element( DOMElement $element, array $allowed_elements, array $allowed_attributes ): void {
	// Collect child nodes to process (we can't modify while iterating).
	$children_to_remove  = array();
	$children_to_process = array();

	foreach ( $element->childNodes as $child ) {
		if ( $child instanceof DOMElement ) {
			$tag_name = strtolower( $child->nodeName );

			// Remove disallowed elements (like script, foreignObject, etc.).
			if ( ! in_array( $tag_name, $allowed_elements, true ) ) {
				$children_to_remove[] = $child;
			} else {
				$children_to_process[] = $child;
			}
		}
	}

	// Remove disallowed children.
	foreach ( $children_to_remove as $child ) {
		$element->removeChild( $child );
	}

	// Process allowed children recursively.
	foreach ( $children_to_process as $child ) {
		skel_sanitize_svg_element( $child, $allowed_elements, $allowed_attributes );
	}

	// Sanitize attributes of the current element.
	$attrs_to_remove = array();

	foreach ( $element->attributes as $attr ) {
		$attr_name = strtolower( $attr->nodeName );

		// Remove event handlers (on*).
		if ( str_starts_with( $attr_name, 'on' ) ) {
			$attrs_to_remove[] = $attr->nodeName;
			continue;
		}

		// Check against allowed list (including data-* pattern).
		$is_allowed = in_array( $attr_name, $allowed_attributes, true )
			|| str_starts_with( $attr_name, 'data-' )
			|| str_starts_with( $attr_name, 'aria-' );

		// Allow xlink:href and href only for internal references (#id).
		if ( 'xlink:href' === $attr_name || 'href' === $attr_name ) {
			$value = $attr->value;
			// Only allow internal references (starting with #) or empty.
			if ( '' !== $value && '#' !== $value[0] ) {
				$attrs_to_remove[] = $attr->nodeName;
				continue;
			}
			$is_allowed = true;
		}

		if ( ! $is_allowed ) {
			$attrs_to_remove[] = $attr->nodeName;
			continue;
		}

		// Sanitize style attribute to remove javascript: URLs and expressions.
		if ( 'style' === $attr_name ) {
			$style = $attr->value;
			// Remove javascript:, expression(), url() with external refs, etc.
			$dangerous_patterns = array(
				'/javascript\s*:/i',
				'/expression\s*\(/i',
				'/url\s*\(\s*["\']?\s*(?!#)[^)]+\)/i', // url() not starting with #.
				'/-moz-binding/i',
				'/behavior\s*:/i',
			);
			foreach ( $dangerous_patterns as $pattern ) {
				if ( preg_match( $pattern, $style ) ) {
					$attrs_to_remove[] = $attr->nodeName;
					break;
				}
			}
		}
	}

	// Remove disallowed attributes.
	foreach ( $attrs_to_remove as $attr_name ) {
		$element->removeAttribute( $attr_name );
	}
}

/**
 * Retrieve the content of an SVG file located in the current active theme's '/assets/images/icons/' directory.
 *
 * @param  string $image      The name of the SVG file (without the '.svg' extension) to retrieve.
 * @param  array  $attributes Optional. Array of attributes to add to the SVG element. Default aria-hidden="true".
 * @return string|false The content of the SVG file if found, or FALSE if the file does not exist or cannot be read.
 */
function skel_get_svg_content( string $image, array $attributes = array() ): string|false {
	$cache_key = 'skel_get_svg_' . sanitize_key( $image );
	$cached    = wp_cache_get( $cache_key, 'skel_get_svgs' );

	if ( false !== $cached ) {
		return skel_add_svg_attributes( $cached, $attributes );
	}

	// Construct the full path to the SVG file.
	$file_path = get_template_directory() . '/assets/images/icons/' . sanitize_file_name( $image ) . '.svg';

	// Ensure the file exists and is within the theme directory (path traversal protection).
	$real_base = realpath( get_template_directory() . '/assets/images/icons/' );
	$real_file = realpath( $file_path );

	if ( false === $real_file || 0 !== strpos( $real_file, $real_base ) ) {
		return false;
	}

	// Check if the file exists and is readable.
	if ( file_exists( $real_file ) && is_readable( $real_file ) ) {
		// Safely read the file using WP_Filesystem API.
		$wp_filesystem = skel_init_filesystem();

		$content = $wp_filesystem->get_contents( $real_file );

		if ( false === $content ) {
			return false;
		}

		// Sanitize SVG content to remove potentially dangerous elements and attributes.
		$content = skel_sanitize_svg( $content );

		if ( false === $content ) {
			return false;
		}

		wp_cache_set( $cache_key, $content, 'skel_get_svgs' );

		return skel_add_svg_attributes( $content, $attributes );
	}

	return false;
}

/**
 * Output an SVG icon with optional attributes.
 *
 * @param  string $image      The name of the SVG file (without the '.svg' extension).
 * @param  array  $attributes Optional. Array of attributes to add to the SVG element.
 * @return string The SVG content or empty string on failure.
 */
function skel_get_svg( string $image, array $attributes = array() ): string {
	$svg = skel_get_svg_content( $image, $attributes );
	return $svg ? $svg : '';
}

/**
 * Output an attachment image, inlining SVGs and using <img> for raster images.
 *
 * @param  int    $attachment_id The attachment ID.
 * @param  string $size          Optional. Image size for raster images. Default 'w1400'.
 * @param  array  $attributes    Optional. Attributes for the SVG or img element.
 * @return string The SVG markup or img tag.
 */
function skel_get_attachment_image( int $attachment_id, string $size = 'w1400', array $attributes = array() ): string {
	if ( ! $attachment_id ) {
		return '';
	}

	if ( 'image/svg+xml' === get_post_mime_type( $attachment_id ) ) {
		$file = get_attached_file( $attachment_id );

		if ( ! $file || ! file_exists( $file ) ) {
			return '';
		}

		$content = skel_sanitize_svg( file_get_contents( $file ) );

		if ( ! $content ) {
			return '';
		}

		return skel_add_svg_attributes( $content, $attributes ) ?: '';
	}

	return wp_get_attachment_image( $attachment_id, $size, false, $attributes );
}

/**
 * Add attributes to an SVG element.
 *
 * @param  string $svg_content The SVG content.
 * @param  array  $attributes  Array of attributes to add to the SVG element.
 * @return string|false The modified SVG content, or FALSE on failure.
 */
function skel_add_svg_attributes( string $svg_content, array $attributes = array() ): string|false {
	// Default attributes.
	$default_attributes = array( 'aria-hidden' => 'true' );

	// Merge with provided attributes (provided attributes override defaults).
	$attributes = array_merge( $default_attributes, $attributes );

	// Use libxml error handling.
	$use_errors = libxml_use_internal_errors( true );

	$dom = new DOMDocument();
	if ( ! $dom->loadXML( $svg_content, LIBXML_NONET | LIBXML_NOBLANKS ) ) {
		libxml_clear_errors();
		libxml_use_internal_errors( $use_errors );
		return false;
	}

	$svg_element = $dom->documentElement; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	if ( ! $svg_element || 'svg' !== strtolower( $svg_element->nodeName ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		libxml_use_internal_errors( $use_errors );
		return false;
	}

	// Remove width and height so SVGs scale via CSS.
	$svg_element->removeAttribute( 'width' );
	$svg_element->removeAttribute( 'height' );

	// Add or merge attributes.
	foreach ( $attributes as $attr_name => $attr_value ) {
		// Special handling for class attribute (merge with existing).
		if ( 'class' === $attr_name && $svg_element->hasAttribute( 'class' ) ) {
			$existing_classes = $svg_element->getAttribute( 'class' );
			$attr_value       = trim( $existing_classes . ' ' . $attr_value );
		}

		$svg_element->setAttribute( $attr_name, $attr_value );
	}

	$result = $dom->saveXML( $svg_element );

	libxml_use_internal_errors( $use_errors );

	return $result ? $result : false;
}
