<?php
/**
 * Register ACF Blocks
 *
 * @package Skeleton
 * @subpackage ACF
 */

// Load configuration.
require_once get_template_directory() . '/blocks/config.php';

/**
 * Load ACF blocks from JSON files.
 */
function skel_load_acf_json_blocks() {
	$blocks_dir = get_template_directory() . '/blocks';

	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}

	$block_folders = glob( $blocks_dir . '/*', GLOB_ONLYDIR );

	if ( empty( $block_folders ) ) {
		return;
	}

	foreach ( $block_folders as $folder ) {
		$slug      = basename( $folder );
		$json_file = $folder . '/' . $slug . '.json';

		if ( ! file_exists( $json_file ) ) {
			continue;
		}

		$json_content = file_get_contents( $json_file );
		$block_data   = json_decode( $json_content, true );

		if ( ! $block_data || ! isset( $block_data['title'] ) ) {
			continue;
		}

		skel_register_single_block( $block_data, $slug );
	}
}
add_action( 'acf/init', 'skel_load_acf_json_blocks' );

/**
 * Register a single ACF block and its fields.
 *
 * @param array  $block_data The block data from JSON.
 * @param string $slug       The block slug.
 */
function skel_register_single_block( $block_data, $slug ) {
	// Check if block is active.
	if ( isset( $block_data['active'] ) && false === $block_data['active'] ) {
		return;
	}

	// 1. Register Block.
	$default_block_args = array(
		'description'     => '',
		'render_template' => 'blocks/' . $slug . '/' . $slug . '.php',
		'category'        => 'uncategorized',
		'icon'            => 'layout',
		'mode'            => 'edit',
		'supports'        => array(
			'align'           => false,
			'customClassName' => false,
			'mode'            => false,
		),
		'example'         => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'preview_image' => get_template_directory_uri() . '/blocks/' . $slug . '/preview.png',
				),
			),
		),
	);

	$block_settings = $block_data['settings'] ?? array();
	$args           = wp_parse_args( $block_settings, $default_block_args );

	$args['name']  = $slug;
	$args['title'] = $block_data['title'];

	// Capture swiper dependency from JSON to avoid re-reading at render time.
	$needs_swiper = ! empty( $block_data['needs_swiper'] );

	// Enqueue block CSS/JS at render time (covers inserted pages, 404, search).
	$args['enqueue_assets'] = function () use ( $slug, $needs_swiper ) {
		$blocks_dir = get_template_directory() . '/blocks';
		$blocks_uri = get_template_directory_uri() . '/blocks';
		$css_path   = "{$blocks_dir}/{$slug}/{$slug}.css";
		$js_path    = "{$blocks_dir}/{$slug}/{$slug}.js";

		if ( file_exists( $css_path ) ) {
			wp_enqueue_style(
				"block-{$slug}",
				"{$blocks_uri}/{$slug}/{$slug}.css",
				array(),
				filemtime( $css_path )
			);
		}

		if ( file_exists( $js_path ) ) {
			if ( $needs_swiper ) {
				wp_enqueue_script(
					'skel-swiper',
					get_template_directory_uri() . '/assets/js/swiper-bundle.js',
					array(),
					filemtime( get_template_directory() . '/assets/js/swiper-bundle.js' ),
					true
				);
			}

			wp_enqueue_script(
				"block-{$slug}",
				"{$blocks_uri}/{$slug}/{$slug}.js",
				$needs_swiper ? array( 'skel-swiper' ) : array(),
				filemtime( $js_path ),
				true
			);
		}
	};

	if ( isset( $block_data['post_types'] ) && is_array( $block_data['post_types'] ) ) {
		$args['post_types'] = $block_data['post_types'];
	} else {
		$args['post_types'] = apply_filters( 'skel_acf_block_post_types', array( 'page', 'service', 'project', 'insights', 'knowledge-base' ), $slug );
	}

	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( $args );
	}

	// 2. Register Field Group.
	if ( function_exists( 'acf_add_local_field_group' ) ) {
		$default_field_group = array(
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		);

		// Pick only field-group-relevant keys from block data.
		$field_group_data = array_intersect_key(
			$block_data,
			array(
				'fields'                => true,
				'menu_order'            => true,
				'position'              => true,
				'style'                 => true,
				'label_placement'       => true,
				'instruction_placement' => true,
				'hide_on_screen'        => true,
				'active'                => true,
				'description'           => true,
				'skip_settings'         => true,
			)
		);

		$field_group = wp_parse_args( $field_group_data, $default_field_group );

		// Inject default settings fields.
		$slug_snake = str_replace( '-', '_', $slug );

		if ( ! empty( $field_group['skip_settings'] ) ) {
			unset( $field_group['skip_settings'] );
			$default_fields = array();
		} else {
			$default_fields = skel_get_block_default_settings_tab_fields( $slug_snake );
		}

		$existing_keys = array();
		if ( ! empty( $field_group['fields'] ) ) {
			foreach ( $field_group['fields'] as $field ) {
				if ( isset( $field['key'] ) ) {
					$existing_keys[] = $field['key'];
				}
			}
		} else {
			$field_group['fields'] = array();
		}

		foreach ( $default_fields as $default_field ) {
			if ( ! in_array( $default_field['key'], $existing_keys, true ) ) {
				$field_group['fields'][] = $default_field;
			}
		}

		// Ensure all fields have keys.
		$field_group_key = 'group_' . $slug_snake;
		skel_ensure_field_keys( $field_group['fields'], $field_group_key );

		$field_group['key']      = $field_group_key;
		$field_group['location'] = array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/' . $slug,
				),
			),
		);

		acf_add_local_field_group( $field_group );
	}
}

/**
 * Recursively ensure all fields have a key.
 *
 * @param array  $fields The fields array.
 * @param string $prefix The prefix for generating keys.
 */
function skel_ensure_field_keys( &$fields, $prefix ) {
	foreach ( $fields as &$field ) {
		// If key is missing, generate one.
		if ( empty( $field['key'] ) ) {
			$suffix       = $field['name'] ?? uniqid();
			$field['key'] = $prefix . '_' . $suffix;
		}

		// Recurse for sub_fields (Group, Repeater).
		if ( ! empty( $field['sub_fields'] ) ) {
			skel_ensure_field_keys( $field['sub_fields'], $field['key'] );
		}

		// Recurse for layouts (Flexible Content).
		if ( ! empty( $field['layouts'] ) ) {
			foreach ( $field['layouts'] as &$layout ) {
				if ( empty( $layout['key'] ) ) {
					$layout['key'] = $field['key'] . '_' . $layout['name'];
				}
				if ( ! empty( $layout['sub_fields'] ) ) {
					skel_ensure_field_keys( $layout['sub_fields'], $layout['key'] );
				}
			}
		}
	}
}

// Check if block config changed and regenerate files if needed.
$blocks_current_hash = md5( wp_json_encode( $block_types ) );
$blocks_stored_hash  = get_option( 'acf_block_types_hash' );

if ( $blocks_current_hash !== $blocks_stored_hash ) {
	update_option( 'acf_block_types_hash', $blocks_current_hash );

	if ( 'local' === wp_get_environment_type() ) {
		skel_create_acf_block_files( $block_types );
		skel_delete_unwanted_acf_block_files( $block_types );
	}
}

/**
 * Get default fields for ACF blocks.
 *
 * @param string $slug_snake The block slug in snake_case.
 * @return array Default fields.
 */
function skel_get_block_default_settings_tab_fields( $slug_snake ) {
	return array(
		array(
			'key'   => 'field_' . $slug_snake . '_settings_tab',
			'label' => 'Settings',
			'type'  => 'tab',
		),
		array(
			'key'           => 'field_' . $slug_snake . '_display',
			'label'         => 'Show on Page',
			'name'          => 'display',
			'type'          => 'button_group',
			'choices'       => array(
				'on'  => 'Yes',
				'off' => 'No',
			),
			'default_value' => 'on',
		),
		array(
			'key'        => 'field_' . $slug_snake . '_spacing',
			'label'      => 'Spacing',
			'name'       => 'spacing',
			'type'       => 'group',
			'layout'     => 'row',
			'sub_fields' => array(
				array(
					'key'        => 'field_' . $slug_snake . '_spacing_top_group',
					'label'      => 'Top',
					'name'       => 'top',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => array(
						array(
							'key'           => 'field_' . $slug_snake . '_spacing_top',
							'label'         => 'Spacing Top',
							'name'          => 'spacing_top',
							'type'          => 'button_group',
							'choices'       => array(
								'none'               => 'None',
								'spacing-top-small'  => 'Small',
								'spacing-top-medium' => 'Medium',
								'spacing-top-large'  => 'Large',
								'spacing-top-xlarge' => 'X-Large',
								'custom'             => 'Custom',
							),
							'default_value' => 'spacing-top-medium',
						),
						array(
							'key'               => 'field_' . $slug_snake . '_custom_value_top_mobile',
							'label'             => 'Mobile Value',
							'name'              => 'custom_value_top_mobile',
							'type'              => 'range',
							'min'               => 0,
							'max'               => 400,
							'step'              => 5,
							'append'            => 'px',
							'wrapper'           => array(
								'width' => '50',
							),
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_' . $slug_snake . '_spacing_top',
										'operator' => '==',
										'value'    => 'custom',
									),
								),
							),
						),
						array(
							'key'               => 'field_' . $slug_snake . '_custom_value_top_desktop',
							'label'             => 'Desktop Value',
							'name'              => 'custom_value_top_desktop',
							'type'              => 'range',
							'min'               => 0,
							'max'               => 400,
							'step'              => 5,
							'append'            => 'px',
							'wrapper'           => array(
								'width' => '50',
							),
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_' . $slug_snake . '_spacing_top',
										'operator' => '==',
										'value'    => 'custom',
									),
								),
							),
						),
					),
				),
				array(
					'key'        => 'field_' . $slug_snake . '_spacing_bottom_group',
					'label'      => 'Bottom',
					'name'       => 'bottom',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => array(
						array(
							'key'           => 'field_' . $slug_snake . '_spacing_bottom',
							'label'         => 'Spacing Bottom',
							'name'          => 'spacing_bottom',
							'type'          => 'button_group',
							'choices'       => array(
								'none'                  => 'None',
								'spacing-bottom-small'  => 'Small',
								'spacing-bottom-medium' => 'Medium',
								'spacing-bottom-large'  => 'Large',
								'spacing-bottom-xlarge' => 'X-Large',
								'custom'                => 'Custom',
							),
							'default_value' => 'spacing-bottom-xlarge',
						),
						array(
							'key'               => 'field_' . $slug_snake . '_custom_value_bottom_mobile',
							'label'             => 'Mobile Value',
							'name'              => 'custom_value_bottom_mobile',
							'type'              => 'range',
							'min'               => 0,
							'max'               => 400,
							'step'              => 5,
							'append'            => 'px',
							'wrapper'           => array(
								'width' => '50',
							),
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_' . $slug_snake . '_spacing_bottom',
										'operator' => '==',
										'value'    => 'custom',
									),
								),
							),
						),
						array(
							'key'               => 'field_' . $slug_snake . '_custom_value_bottom_desktop',
							'label'             => 'Desktop Value',
							'name'              => 'custom_value_bottom_desktop',
							'type'              => 'range',
							'min'               => 0,
							'max'               => 400,
							'step'              => 5,
							'append'            => 'px',
							'wrapper'           => array(
								'width' => '50',
							),
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_' . $slug_snake . '_spacing_bottom',
										'operator' => '==',
										'value'    => 'custom',
									),
								),
							),
						),
					),
				),
			),
		),
		array(
			'key'          => 'field_' . $slug_snake . '_custom_css',
			'label'        => 'Custom CSS',
			'name'         => 'custom_css',
			'type'         => 'text',
			'instructions' => 'If unsure, do not edit.',
			'wrapper'      => array(
				'width' => '33',
			),
		),
		array(
			'key'          => 'field_' . $slug_snake . '_custom_classes',
			'label'        => 'Custom Class(es)',
			'name'         => 'custom_classes',
			'type'         => 'text',
			'instructions' => 'If unsure, do not edit.',
			'wrapper'      => array(
				'width' => '33',
			),
		),
		array(
			'key'          => 'field_' . $slug_snake . '_unique_id',
			'label'        => 'Unique ID',
			'name'         => 'unique_id',
			'type'         => 'text',
			'instructions' => 'only small case word allowed',
			'wrapper'      => array(
				'width' => '33',
			),
		),
	);
}
