<?php
/**
 * Auto-updates the 'claude' preview page when a trigger file is present.
 * Runs on init (before main query) so the updated content renders in the same request.
 *
 * This is a development-only utility for previewing ACF blocks via Claude Code.
 * A trigger file at blocks/.claude-preview-pending contains the block slug(s) to preview.
 *
 * Supports:
 * - Single block: file contains one slug, e.g. "hero-banner"
 * - Multiple blocks: file contains comma-separated slugs, e.g. "hero-banner,two-columns"
 *
 * Security: Only runs in local environments (wp_get_environment_type() must return 'local').
 * Auto-creates the 'claude' page if it doesn't exist.
 *
 * @package Skeleton
 * @subpackage Functions
 */
add_action( 'init', 'skel_apply_pending_claude_preview' );

function skel_apply_pending_claude_preview() {
	// Only run on local sites.
	if ( 'local' !== wp_get_environment_type() ) {
		return;
	}

	$trigger_file = get_template_directory() . '/blocks/.claude-preview-pending';

	if ( ! file_exists( $trigger_file ) ) {
		return;
	}

	// Atomic read-and-delete to prevent race conditions.
	$temp_file = $trigger_file . '.' . getmypid();
	if ( ! @rename( $trigger_file, $temp_file ) ) {
		return; // Another request already consumed it.
	}

	$raw = trim( file_get_contents( $temp_file ) );
	unlink( $temp_file );

	if ( empty( $raw ) ) {
		return;
	}

	// Parse comma-separated slugs and validate each one.
	$slugs = array_filter( array_map( 'trim', explode( ',', $raw ) ) );

	if ( empty( $slugs ) ) {
		return;
	}

	$block_markup_parts = array();

	foreach ( $slugs as $slug ) {
		// Sanitize: only allow lowercase alphanumeric characters and hyphens.
		if ( ! preg_match( '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug ) ) {
			error_log( '[claude-preview] Invalid block slug skipped: ' . $slug );
			continue;
		}

		$slug_snake  = str_replace( '-', '_', $slug );
		$display_key = 'field_' . $slug_snake . '_display';
		$block_data  = wp_json_encode( array( $display_key => 'on' ) );

		$block_markup_parts[] = '<!-- wp:acf/' . $slug . ' {"id":"block_' . $slug_snake . '","name":"acf/' . $slug . '","data":' . $block_data . ',"mode":"preview"} /-->';
	}

	if ( empty( $block_markup_parts ) ) {
		error_log( '[claude-preview] No valid block slugs found in trigger file.' );
		return;
	}

	$page = get_page_by_path( 'claude' );

	if ( ! $page ) {
		$page_id = wp_insert_post( array(
			'post_title'  => 'Claude Preview',
			'post_name'   => 'claude',
			'post_status' => 'publish',
			'post_type'   => 'page',
		) );

		if ( is_wp_error( $page_id ) ) {
			error_log( '[claude-preview] Failed to create preview page: ' . $page_id->get_error_message() );
			return;
		}

		$page = get_post( $page_id );
	}

	wp_update_post( array(
		'ID'           => $page->ID,
		'post_content' => implode( "\n\n", $block_markup_parts ),
	) );
}
