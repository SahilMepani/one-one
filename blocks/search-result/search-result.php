<?php
/**
 * Search result ACF block
 *
 * @package Skeleton
 * @subpackage ACF
 */

// Verify nonce.
if ( isset( $_GET['s'] ) ) {
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'search_nonce' ) ) {
		// Nonce verification failed, handle the error or exit gracefully.
		// Redirect the user to a safe page.
		wp_safe_redirect( home_url() );
		exit;
	}
}

// Set thumbnail preview in backend.
if ( skel_render_block_preview( $block ) ) {
	return;
}

// Return early if display is off.
if ( ! skel_should_display_block() ) {
	return;
}


// Data options.

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="search-result-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<?php $search_term = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : ''; ?>

	<div class="container">

		<?php get_search_form(); ?>

		<?php
		if ( $search_term ) {
			$custom_query = new WP_Query(
				array(
					's'              => $search_term, /* search query */
					'post_type'      => array( 'post', 'page' ),
					'posts_per_page' => 100,
					'post_status'    => 'publish',
					'fields'         => 'ids',
				)
			);
			$post_count   = $custom_query->post_count;
			?>
			<?php if ( is_array( $custom_query->posts ) && ! empty( $custom_query->posts ) ) { ?>
				<p>
					<?php
						echo esc_html( $post_count );
						esc_html_e( ' Search results found', 'skel' );
					?>
				</p>

				<ul class="list-result list-unstyled" data-inview>
					<?php
					foreach ( $custom_query->posts as $post_item ) :
						$item_post_type = get_post_type( $post_item );
						$post_type_obj  = get_post_type_object( $item_post_type );
						$post_title     = get_the_title( $post_item );
						$post_permalink = get_permalink( $post_item );
						?>
						<li data-aos="fade-up" data-aos-stagger-item>
							<a href="<?php echo esc_url( $post_permalink ); ?>">
								<div class="post-type">
									<?php
									echo esc_html( $post_type_obj->labels->singular_name );
									?>
								</div>
								<h6 class="title"><?php echo esc_html( $post_title ); ?></h6>
							</a>
						</li>
						<?php
					endforeach;
					wp_reset_postdata();
					?>
				</ul>
			<?php } else { ?>
				<p><?php esc_html_e( 'No search results found', 'skel' ); ?></p>
			<?php } ?>
		<?php } ?>

	</div> <!-- .container -->

</section>
