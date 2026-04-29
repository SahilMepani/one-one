<?php
// Retrieve front page data once.
$current_post_id = get_the_ID();
$parent_page     = get_field( 'parent_page', $current_post_id );
$frontpage_id    = get_option( 'page_on_front' );
$frontpage_title = get_the_title( $frontpage_id );

// Get the current post type.
$item_post_type = get_post_type( $current_post_id );

// Initialize CPT data as null (or an empty array for a safer default).
// Using null ensures we explicitly know if it's set later.
$cpt_data = null;

// You could use a mapping array if you have many more CPTs.
$cpt_mapping = array(
	'team'           => defined( 'PAGE_TEAMS_ID' ) ? PAGE_TEAMS_ID : 0,
	'tawrid-insight' => $parent_page,
	// Add other CPTs here: 'custom_post_type' => defined('PAGE_CPT_ID') ? PAGE_CPT_ID : 0,.
);

// Search result.
$search_result_id    = PAGE_SEARCH_ID ?: null;
$search_result_title = $search_result_id ? get_the_title( $search_result_id ) : '';
$search_result_link  = $search_result_id ? get_permalink( $search_result_id ) : '';

// 404
$page_404_id    = PAGE_404_ID ?: null;
$page_404_title = $page_404_id ? get_the_title( $page_404_id ) : '';
$page_404_link  = $page_404_id ? get_permalink( $page_404_id ) : '';

// Check if the current post type has a corresponding ID defined and is valid.
$associated_page_id = $cpt_mapping[ $item_post_type ] ?? 0;

if ( $associated_page_id && get_post_status( $associated_page_id ) ) { // Check if ID is not 0 and post exists.
	$cpt_data = array(
		'title' => get_the_title( $associated_page_id ),
		'link'  => get_permalink( $associated_page_id ),
	);
}

// Parent Page.
$parent_id   = wp_get_post_parent_id( $current_post_id );
$parent_link = $parent_title = '';

if ( $parent_id ) {
	$parent_link  = get_permalink( $parent_id );
	$parent_title = get_the_title( $parent_id );
}
?>
<div class="breadcrumbs-section" data-inview data-aos="fade">
	<div class="container">
		<div class="inner-container">
			<nav aria-label="breadcrumbs" class="breadcrumb">
				<p>
					<a href="<?php echo esc_url( site_url() ); ?>" title="<?php echo esc_attr( $frontpage_title ); ?>"><?php echo esc_html( $frontpage_title ); ?></a>

					<span class="separator" role="presentation"></span>

					<?php if ( is_search() && $search_result_id ) { ?>
						<a href="<?php echo esc_url( $search_result_link ); ?>" title="<?php echo esc_attr( $search_result_title ); ?>" class="last">
							<?php echo esc_html( $search_result_title ); ?>
						</a>

					<?php } elseif ( is_404() ) { ?>
						<a href="<?php echo esc_url( $page_404_link ); ?>" title="<?php echo esc_attr( $page_404_title ); ?>" class="last">
							<?php echo esc_html( $page_404_title ); ?>
						</a>

					<?php } else { ?>
						<?php if ( $parent_id ) { ?>
							<a href="<?php echo esc_url( $parent_link ); ?>" title="<?php echo esc_attr( $parent_title ); ?>" class="middle">
								<span class="title"><?php echo esc_html( $parent_title ); ?></span>
								<span class="dots" role="presentation">...</span>
							</a>
							<span class="separator" role="presentation"></span>

							<?php
						} elseif ( $cpt_data ) {
							?>
							<a href="<?php echo esc_url( $cpt_data['link'] ); ?>" title="<?php echo esc_attr( $cpt_data['title'] ); ?>" class="middle">
								<span class="title"><?php echo esc_html( $cpt_data['title'] ); ?></span>
								<span class="dots" role="presentation">...</span>
							</a>

							<span class="separator" role="presentation"></span>
						<?php } ?>

						<?php if ( 'tawrid-insight' !== $item_post_type ) { ?>
							<span class="last"><?php echo esc_html( get_the_title() ); ?></span>
						<?php } ?>

						<?php if ( 'tawrid-insight' === $item_post_type ) { ?>
							<span class="last">
								<?php
									$term_obj_list = get_the_terms(
										$current_post_id,
										'insight-type'
									);
									$terms_string  = join( ', ', wp_list_pluck( $term_obj_list, 'name' ) );
									echo esc_html( $terms_string );
								?>
							</span>
						<?php } ?>
					<?php } ?>
				</p>
			</nav>
		</div>
	</div>
</div>
