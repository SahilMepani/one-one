<li id="post-<?php the_ID(); ?>" <?php post_class( 'type-post--blog clearfix bg-grey' ); ?>>

	<?php if ( has_post_thumbnail() ) { ?>
	<aside class="img-block">
		<a href="<?php the_permalink(); ?>" title="Read more about<?php the_title_attribute(); ?>">
			<?php the_post_thumbnail( 'medium' ); ?>
		</a>
	</aside> <!-- .img-block -->
	<?php } ?>

	<div class="content-block">

		<h2 class="title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"
				title="Read more about <?php the_title_attribute(); ?>"><?php the_title(); ?> </a>
		</h2>

		<div class="meta-block">
			<time pubdate><?php the_time( 'F j, Y' ); ?></time> by <span
				class="author"><?php echo get_the_author(); ?></span> under<?php the_category( ' / ' ); ?>
		</div> <!-- .post-meta -->

		<p class="excerpt"><?php echo esc_html( skel_get_the_excerpt( 20 ) ); ?></p>

		<a href="<?php the_permalink(); ?>" class="more-link clear">Read More</a>

	</div> <!-- .content-block -->

</li>
