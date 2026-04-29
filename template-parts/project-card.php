<li <?php post_class( 'col' ); ?> id="post-<?php the_ID(); ?>">
	<a href="<?php the_permalink(); ?>" class="post-link">

	<?php
	if ( has_post_thumbnail() ) {
		$image_id        = get_post_thumbnail_id();
		$image_url_array = wp_get_attachment_image_src( $image_id, 'medium_crop', true );
		$image_url       = $image_url_array[0];
		$image_css       = 'background-image: url(' . esc_url( $image_url ) . ');';
	} else {
		$image_css = 'background-image: url(' . esc_url( get_template_directory_uri() . '/assets/images/placeholder.png' ) . ')';
	}
	?>

	<aside class="thumbnail" style="<?php echo esc_attr( $image_css ); ?>"></aside>

		<div class="content-block">

			<div class="category">
				<?php
				if ( has_term( '', 'tax-one' ) ) {
					echo skel_get_the_terms( $post->ID, 'tax-one' );
					if ( has_term( '', 'tax-two' ) ) {
						echo ', ';
					}
				}
				if ( has_term( '', 'tax-two' ) ) {
					echo skel_get_the_terms( $post->ID, 'tax-two' );
				}
				?>
			</div>

			<h2 class="post-title">
				<?php the_title(); ?>
			</h2>

			<time pubdate><?php the_time( 'F j, Y' ); ?></time>
		</div> <!-- .content-block -->

	</a> <!-- .type-post -->
</li>
