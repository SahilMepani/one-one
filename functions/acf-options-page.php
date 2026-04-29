<?php
/**
 * ACF Options Pages
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title' => 'Global Options',
			'menu_title' => 'Global Options',
			'menu_slug'  => 'global-options',
			'capability' => 'manage_options',
			'redirect'   => true,
			'icon_url'   => 'dashicons-admin-generic',
			'position'   => 2,
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'General',
			'menu_title'  => 'General',
			'menu_slug'   => 'global-options-general',
			'parent_slug' => 'global-options',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Header',
			'menu_title'  => 'Header',
			'menu_slug'   => 'global-options-header',
			'parent_slug' => 'global-options',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Footer',
			'menu_title'  => 'Footer',
			'menu_slug'   => 'global-options-footer',
			'parent_slug' => 'global-options',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'CTA',
			'menu_title'  => 'CTA',
			'menu_slug'   => 'global-options-cta',
			'parent_slug' => 'global-options',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Analytics',
			'menu_title'  => 'Analytics',
			'menu_slug'   => 'global-options-analytics',
			'parent_slug' => 'global-options',
		)
	);
}

/**
 * Load ACF options field groups from JSON files in /options/.
 */
function skel_load_acf_json_options() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$options_dir = get_template_directory() . '/options';

	if ( ! is_dir( $options_dir ) ) {
		return;
	}

	$json_files = glob( $options_dir . '/*.json' );

	if ( empty( $json_files ) ) {
		return;
	}

	foreach ( $json_files as $json_file ) {
		$json_content = file_get_contents( $json_file );
		if ( false === $json_content ) {
			continue;
		}

		$slug = sanitize_key( basename( $json_file, '.json' ) );
		$data = json_decode( $json_content, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( 'Skeleton: Invalid JSON in ' . basename( $json_file ) . ' — ' . json_last_error_msg() );
			}
			continue;
		}

		if ( ! $data || ! isset( $data['title'], $data['options_page'], $data['fields'] ) ) {
			continue;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_options_' . $slug,
				'title'                 => $data['title'],
				'fields'                => $data['fields'],
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => $data['options_page'],
						),
					),
				),
				'menu_order'            => $data['menu_order'] ?? 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'active'                => true,
			)
		);
	}
}
add_action( 'acf/init', 'skel_load_acf_json_options' );

