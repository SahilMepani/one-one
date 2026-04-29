<?php
/**
 * Custom Select
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

get_header();
?>

<div class="container-fluid padding-4">

	<h4>Mobile Select</h4>

	<div class="chosen-select-block hidden-sm hidden-md hidden-lg">
		<label for="mobile-select-category" class="sr-only">Select Category</label>
		<select class="redirect-chosen-select" id="mobile-select-category">
			<option value="
			<?php echo esc_url( home_url( '/news-and-events/' ) ); ?>">All</option>
			<?php
				$taxonomy_args = array(
					'taxonomy'   => 'category',
					'hide_empty' => true,
				);
				$taxonomies    = get_categories( $taxonomy_args );
				foreach ( $taxonomies as $taxonomy_term ) :
					?>
			<option value="
					<?php echo esc_url( home_url( '/category/' ) ); ?>
					<?php echo esc_html( $taxonomy_term->slug ); ?>"
					<?php
					if ( is_category( $taxonomy_term->slug ) ) {
						echo ' selected="selected"';}
					?>
				>
					<?php echo esc_attr( $taxonomy_term->name ); ?>
			</option>
			<?php endforeach; ?>
		</select>
	</div> <!-- .redirect-select-block -->

</div> <!-- .container-fluid -->

<?php get_footer(); ?>
