<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cache Invalidation Hooks
 *
 * Clears transient caches when content is saved or deleted.
 */

/**
 * Invalidate caches when a post is saved or updated.
 *
 * @param int $post_id Post ID.
 */
function skel_invalidate_caches_on_save( $post_id ) {
	// Skip autosaves and revisions.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	skel_invalidate_caches_by_post_type( $post_id );
}
add_action( 'save_post', 'skel_invalidate_caches_on_save' );

/**
 * Invalidate caches when a post is deleted.
 *
 * @param int $post_id Post ID.
 */
function skel_invalidate_caches_on_delete( $post_id ) {
	skel_invalidate_caches_by_post_type( $post_id );
}
add_action( 'delete_post', 'skel_invalidate_caches_on_delete' );
add_action( 'trashed_post', 'skel_invalidate_caches_on_delete' );

/**
 * Clear transient caches based on post type.
 *
 * @param int $post_id Post ID.
 */
function skel_invalidate_caches_by_post_type( $post_id ) {
	$post_type = get_post_type( $post_id );

	switch ( $post_type ) {
		case 'product':
			delete_transient( 'skel_all_products' );
			delete_transient( 'skel_product_count' );
			// Delete paginated product cache keys.
			skel_delete_transients_by_prefix( 'skel_products_' );
			// Delete AJAX load more cache for products.
			skel_delete_transients_by_prefix( 'skel_ajax_product_' );
			break;

		case 'case-study':
			delete_transient( 'skel_case_studies' );
			delete_transient( 'skel_case_studies_count' );
			break;

		case 'member':
			delete_transient( 'skel_team_members' );
			delete_transient( 'skel_team_members_count' );
			break;

		case 'post':
			delete_transient( 'skel_recent_posts' );
			// Delete AJAX load more cache for posts.
			skel_delete_transients_by_prefix( 'skel_ajax_post_' );
			break;
	}
}

/**
 * Delete transients matching a prefix pattern.
 *
 * @param string $prefix Transient name prefix.
 */
function skel_delete_transients_by_prefix( $prefix ) {
	global $wpdb;

	$like_prefix = $wpdb->esc_like( '_transient_' . $prefix ) . '%';

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$transient_keys = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
			$like_prefix
		)
	);

	foreach ( $transient_keys as $key ) {
		$transient_name = str_replace( '_transient_', '', $key );
		delete_transient( $transient_name );
	}
}

/**
 * Invalidate taxonomy-related caches when a term is edited.
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function skel_invalidate_taxonomy_caches( $term_id, $tt_id, $taxonomy ) {
	if ( 'product-type' === $taxonomy ) {
		delete_transient( 'skel_all_products' );
		skel_delete_transients_by_prefix( 'skel_products_' );
	}

	if ( 'team' === $taxonomy ) {
		delete_transient( 'skel_team_members' );
	}
}
add_action( 'edited_term', 'skel_invalidate_taxonomy_caches', 10, 3 );
add_action( 'delete_term', 'skel_invalidate_taxonomy_caches', 10, 3 );
