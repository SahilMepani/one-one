<?php
/**
 * ACF Post Field Groups
 *
 * Loads ACF field groups for post types from JSON files in /post-fields/.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Load ACF post field groups from JSON files in /post-fields/.
 */
function skel_load_acf_json_post_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$dir = get_template_directory() . '/post-fields';

	if ( ! is_dir( $dir ) ) {
		return;
	}

	$json_files = glob( $dir . '/*.json' );

	if ( empty( $json_files ) ) {
		return;
	}

	foreach ( $json_files as $json_file ) {
		$slug = sanitize_title( basename( $json_file, '.json' ) );
		$data = wp_json_file_decode( $json_file, array( 'associative' => true ) );

		if ( ! is_array( $data ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( 'Skeleton: Invalid JSON in ' . basename( $json_file ) );
			}
			continue;
		}

		if ( ! isset( $data['title'], $data['post_type'], $data['fields'] ) ) {
			continue;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_post_fields_' . $slug,
				'title'                 => $data['title'],
				'fields'                => $data['fields'],
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => $data['post_type'],
						),
					),
				),
				'menu_order'            => 0,
				'position'              => $data['position'] ?? 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'active'                => true,
			)
		);
	}
}
add_action( 'acf/init', 'skel_load_acf_json_post_fields' );
