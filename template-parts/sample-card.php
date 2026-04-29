<!-- Call the card -->
<?php $team = get_posts( $args ); ?>
<?php
foreach ( $team as $post ) {
	setup_postdata( $post );
	?>
	<?php get_template_part( 'template-parts/team-card', null, array( 'post_id' => $post ) ); ?>
	<?php
}
wp_reset_postdata();
?>

<!-- Call the passed argument inside template part file -->
<?php
	$post_id        = $args['post_id'];
	$post_title     = get_the_title( $post_id );
	$excerpt        = skel_get_the_excerpt( 20, $post_id );
	$featured_image = get_post_thumbnail_id( $post_id );
	$permalink      = get_the_permalink( $post_id );
	$date           = get_the_date( 'd.m.Y', $post_id );
?>
