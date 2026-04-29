<?php get_header(); ?>
<div class="content-section">
<div class="container">

		<div class="row">

		<div class="col-sm-9">

				<section class="main-content clearfix">

					<?php
					if ( have_posts() ) :
						the_post();
						?>

						<article id="post-<?php the_ID(); ?>"<?php post_class( 'clearfix' ); ?>>

							<?php
							if ( has_post_thumbnail() ) {
								?>
								<aside class="featured-img-block">
									<?php the_post_thumbnail( 'post_featured_thumb' ); ?>
								</aside> <!-- .featured-thumb-block -->
							<?php } ?>

							<header>
								<h1 class="post-title"><?php the_title(); ?></h1>
								<div class="post-meta">
									Published by <?php the_author_posts_link(); ?> on
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php the_time( 'F j, Y' ); ?></time> under<?php the_category( ' / ' ); ?>
								</div> <!-- .post-meta -->
							</header>

							<?php the_content(); ?>

							<?php
							if ( has_tag() ) {
								?>
								<p class="tags"><?php the_tags( '<b>Tags:</b> ', ', ', '' ); ?> </p>
									<?php } ?>

						</article> <!-- .type-post -->

						<div class="author-box">

							<div class="avatar-block">
								<?php
								if ( function_exists( 'get_avatar' ) ) {
										echo get_avatar( get_the_author_meta( 'email' ), '100' );}
								?>
							</div> <!-- .avatar-block -->

							<div class="author-info">

								<h5><?php the_author_posts_link(); ?></h5>

								<p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>

				<ul class="list-author-meta list-connections list-unstyled">

						<?php
							$url = get_the_author_meta( 'user_url' );
						if ( $url ) {
							?>
						<li class="i-info"><a href="<?php echo esc_url( $url ); ?>" title="Visit my website">Website</a></li>
					<?php } ?>

						<?php $twitter = get_the_author_meta( 'twitter' ); ?>
						<?php
						if ( $twitter ) {
							?>
						<li class="i-twitter"><a href="https://twitter.com/<?php echo esc_attr( $twitter ); ?>"title="Follow me on twitter">Twitter</a></li>
					<?php } ?>

						<?php $facebook = get_the_author_meta( 'facebook' ); ?>
						<?php
						if ( $facebook ) {
							?>
						<li class="i-facebook"><a href="<?php echo esc_url( $facebook ); ?>" title="View my facebook profile">Facebook</a></li>
							<?php } ?>

						<?php // Google+ removed — service is defunct. ?>

				</ul>

							</div> <!-- .author-info -->

						</div> <!-- .author-box -->

			<div class="post-nav clearfix">

						<?php $prev_post = get_previous_post(); ?>

						<?php
						if ( ! empty( $prev_post ) ) {
							?>
					<div class="old-post">
						<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"
							title="<?php echo esc_attr( $prev_post->post_title ); ?>"><?php echo esc_html( $prev_post->post_title ); ?></a>
					</div>
						<?php } ?>

				<div class="text-center">
					<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">Blog Home</a>
				</div>

						<?php
							$next_post = get_next_post();
						if ( ! empty( $next_post ) ) {
							?>
					<div class="new-post">
						<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"
							title="<?php echo esc_attr( $next_post->post_title ); ?>"><?php echo esc_html( $next_post->post_title ); ?></a>
					</div>
						<?php } ?>

			</div> <!-- .post-nav End -->

						<?php
					else :
						?>

						<h2>Not Found</h2>
						<p>Sorry, but you are looking for something that isn&#8217;t here.</p>

					<?php endif; ?>

				</section> <!-- .main-content -->
			</div> <!-- .col -->

		</div> <!-- .row -->

	</div> <!-- .container -->
</div> <!-- .content-section -->

<?php get_footer(); ?>
