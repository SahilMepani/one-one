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
 * Behaviour: the trigger file is NOT consumed — it persists across requests so
 * tools (playwright, browser refresh, etc.) can navigate to /claude/ repeatedly
 * without rewriting the file each time. The page is only updated when the slug
 * list in the file differs from what was last applied (tracked via post meta).
 *
 * To reset, edit or delete the trigger file; to override blocks manually, edit the
 * page in wp-admin (the trigger only re-applies when the slug list changes).
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

	$raw = trim( (string) file_get_contents( $trigger_file ) );

	if ( '' === $raw ) {
		return;
	}

	// Parse comma-separated slugs and validate each one.
	$slugs       = array_filter( array_map( 'trim', explode( ',', $raw ) ) );
	$valid_slugs = array();

	foreach ( $slugs as $slug ) {
		// Sanitize: only allow lowercase alphanumeric characters and hyphens.
		if ( ! preg_match( '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug ) ) {
			error_log( '[claude-preview] Invalid block slug skipped: ' . $slug );
			continue;
		}

		$valid_slugs[] = $slug;
	}

	if ( empty( $valid_slugs ) ) {
		error_log( '[claude-preview] No valid block slugs found in trigger file.' );
		return;
	}

	$canonical = implode( ',', $valid_slugs );

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

	// Skip if these slugs have already been applied to the page.
	$last_applied = get_post_meta( $page->ID, '_claude_preview_slugs', true );
	if ( $last_applied === $canonical ) {
		return;
	}

	$block_markup_parts = array();

	foreach ( $valid_slugs as $slug ) {
		$slug_snake  = str_replace( '-', '_', $slug );
		$display_key = 'field_' . $slug_snake . '_display';
		$block_data  = wp_json_encode( array( $display_key => 'on' ) );

		$block_markup_parts[] = '<!-- wp:acf/' . $slug . ' {"id":"block_' . $slug_snake . '","name":"acf/' . $slug . '","data":' . $block_data . ',"mode":"preview"} /-->';
	}

	wp_update_post( array(
		'ID'           => $page->ID,
		'post_content' => implode( "\n\n", $block_markup_parts ),
	) );

	update_post_meta( $page->ID, '_claude_preview_slugs', $canonical );
}
