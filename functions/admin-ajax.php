<?php
/**
 * Update posts based on provided parameters.
 *
 * @package WordPress
 * @since 1.0.0
 */

/**
 * This function retrieves posts based on the provided parameters from the $_POST array.
 * It then queries the database using WP_Query and outputs the posts accordingly.
 */
function update_post() {
	// Verify nonce.
	if (
		! isset( $_POST['skel-nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['skel-nonce'] ) ), 'update_post_nonce' )
	) {
		wp_send_json_error( 'Invalid nonce.' );
		return;
	}

	// Verify user capability.
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( 'Insufficient permissions.' );
		return;
	}

	// Sanitize each field.
	$data = array(
		'cpt'          => isset( $_POST['cpt'] ) ? sanitize_text_field( wp_unslash( $_POST['cpt'] ) ) : '',
		'postsPerPage' => isset( $_POST['postsPerPage'] ) ? absint( $_POST['postsPerPage'] ) : 10,
		'pageNumber'   => isset( $_POST['pageNumber'] ) ? absint( $_POST['pageNumber'] ) : 0,
		'cat'          => isset( $_POST['cat'] ) ? sanitize_text_field( wp_unslash( $_POST['cat'] ) ) : '',
		'tax'          => isset( $_POST['tax'] ) ? sanitize_text_field( wp_unslash( $_POST['tax'] ) ) : '',
		'tagID'        => isset( $_POST['tagID'] ) ? absint( $_POST['tagID'] ) : 0,
		'search'       => isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '',
	);

	// Define query arguments.
	$args = array(
		'post_type'      => $data['cpt'],
		'posts_per_page' => $data['postsPerPage'],
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);

	// Check if pagenumber is set for load more.
	if ( ! empty( $data['pageNumber'] ) ) {
		$args['paged'] = $data['pageNumber'] + 1;
	}

	// Add taxonomy query if category is set.
	if ( '' !== $data['cat'] && '' !== $data['tax'] ) {
		// Validate taxonomy against public taxonomies to prevent querying private ones.
		$allowed_taxonomies = get_taxonomies( array( 'public' => true ) );
		if ( ! isset( $allowed_taxonomies[ $data['tax'] ] ) ) {
			wp_send_json_error( 'Invalid taxonomy.' );
			return;
		}

		// phpcs:ignore -- Detected use of tax query - possible slow query
		$args['tax_query'] = array(
			array(
				'taxonomy' => $data['tax'],
				'field'    => 'slug',
				'terms'    => $data['cat'],
			),
		);
	}

	// Add tag ID if provided.
	if ( ! empty( $data['tagID'] ) ) {
		$args['tag_id'] = $data['tagID'];
	}

	// Add search query if provided.
	if ( '' !== $data['search'] ) {
		$args['s'] = $data['search'];
	}

	// Add query optimizations.
	$args['no_found_rows']          = true;
	$args['update_post_meta_cache'] = in_array( $data['cpt'], array( 'insights', 'knowledge-base' ), true );
	$args['update_post_term_cache'] = false;

	// Generate cache key based on query arguments.
	$cache_key = 'ajax_query_' . md5( wp_json_encode( $args ) );
	$results   = get_transient( $cache_key );

	if ( false === $results ) {
		// Query posts based on arguments.
		$custom_query = new WP_Query( $args );
		$results      = $custom_query->posts;
		set_transient( $cache_key, $results, HOUR_IN_SECONDS );
	} else {
		// Create query object from cached posts.
		$custom_query             = new WP_Query();
		$custom_query->posts      = $results;
		$custom_query->post_count = count( $results );
	}

	// Output posts if found.
	if ( $custom_query->have_posts() ) :
		while ( $custom_query->have_posts() ) :
			$custom_query->the_post();

			// Output template part based on post type.
			if ( 'post' === $data['cpt'] ) {
				get_template_part( 'template-parts/post-card' );
			} elseif ( 'project' === $data['cpt'] ) {
				get_template_part( 'template-parts/project-card' );
			} elseif ( 'insights' === $data['cpt'] ) {
				get_template_part( 'template-parts/insight-card' );
			} elseif ( 'knowledge-base' === $data['cpt'] ) {
				get_template_part( 'template-parts/kb-article-card' );
			}

		endwhile;
		wp_reset_postdata();
	endif;

	wp_die();
}

// Hook the update_post function to AJAX action.
add_action( 'wp_ajax_update_post_ajax', 'update_post' );

// Hook the update_post function to AJAX action for non-logged in users.
add_action( 'wp_ajax_nopriv_update_post_ajax', 'update_post' );
