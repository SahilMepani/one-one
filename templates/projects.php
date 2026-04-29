<?php
/**
 * Project template
 *
 * This is the template that displays all of the projects
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

get_header();
?>

<div class="container padding-4">

	<div class="project-filters-block margin-bottom-3">

		<div class="custom-select-block hidden-sm hidden-md hidden-lg">
			<label for="ajax-first-select-post-categories" class="sr-only"><?php esc_html_e( 'Select Tax One', 'skel' ); ?></label>
			<select id="ajax-first-select-post-categories">
				<option data-cpt="project" data-first-cpt-tax="tax-one" data-first-cat-id="-1">
					<?php esc_html_e( 'All Tax One', 'skel' ); ?>
				</option>
				<?php
				$taxonomy_args = array(
					'taxonomy' => 'tax-one',
				);
				$taxonomies    = get_categories( $taxonomy_args );
				foreach ( $taxonomies as $taxonomy_term ) :
					?>
					<option data-cpt="project" data-first-cpt-tax="tax-one" data-first-cat-id="<?php echo esc_attr( $taxonomy_term->term_id ); ?>">
						<?php echo esc_html( $taxonomy_term->name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<ul id="ajax-first-list-post-categories" class="list-tax-one-filters list-filters hidden-xs">
			<li class="js-active">
				<a href="javascript:void(0);" data-cpt="project" data-first-cpt-tax="tax-one" data-first-cat-id="-1"><?php esc_html_e( 'ALL', 'skel' ); ?></a>
			</li>
			<?php
			$taxonomy_args = array(
				'taxonomy' => 'tax-one',
			);
			$taxonomies    = get_categories( $taxonomy_args );
			foreach ( $taxonomies as $taxonomy_term ) :
				?>
				<li>
					<a href="javascript:void(0);" data-cpt="project" data-first-cpt-tax="tax-one" data-first-cat-id="<?php echo esc_attr( $taxonomy_term->term_id ); ?>"><?php echo esc_html( $taxonomy_term->name ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>

	<ul id="ajax-list-post" class="row row-grid-4 blog-grid">

		<input type="hidden" id="filter-first-cat-id" value="-1" />
		<input type="hidden" id="filter-pagenum" value="1" />

		<?php
		$page_number  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args         = array(
			'post_type'      => 'project',
			'posts_per_page' => 6, // Pagination won't work if page and CPT slug are same.
			'paged'          => $page_number,
		);
		$custom_query = new WP_Query( $args );
		$post_count   = $custom_query->post_count;
		?>

		<?php if ( $custom_query->have_posts() ) : ?>
			<?php
			while ( $custom_query->have_posts() ) :
				$custom_query->the_post();
				?>
				<?php get_template_part( 'template-parts/project-card' ); ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

	</ul>

	<div class="text-center clear">
		<div class="spinner margin-bottom-2">
			<div class="bounce1"></div>
			<div class="bounce2"></div>
			<div class="bounce3"></div>
		</div>

		<div class="btn-load-more-block clear">
			<button id="ajax-load-more-post-dual" data-cpt="project" data-first-cpt-tax="tax-one"
					class="<?php echo ( $post_count < 6 ) ? 'disabled' : 'js-active'; ?> btn btn-black btn-lg btn-load-more">
				<b><?php esc_html_e( 'LOAD MORE', 'skel' ); ?></b>
			</button>
		</div>
	</div>

</div>

<?php get_footer(); ?>
