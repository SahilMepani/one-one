<?php get_header(); ?>

<div class="content-section">
	<div class="container">
		<section class="main-content">

			<?php
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
			}

			if ( have_posts() ) :
				the_post();

				$post_id     = get_the_ID();
				$author_id   = get_the_author_meta( 'ID' );
				$author_url  = esc_url( get_the_author_meta( 'user_url' ) );
				$twitter     = sanitize_text_field( get_the_author_meta( 'twitter' ) );
				$facebook    = esc_url( get_the_author_meta( 'facebook' ) );
				$google      = sanitize_text_field( get_the_author_meta( 'googleplus' ) );
				$skills_list = get_the_term_list( $post_id, 'skills', '', ', ', '' );
				?>
				<article id="post-<?php echo esc_attr( $post_id ); ?>" <?php post_class(); ?>>

					<?php if ( has_post_thumbnail() ) : ?>
						<aside class="featured-thumb-block">
							<?php the_post_thumbnail( 'post_featured_thumb', array( 'loading' => 'lazy' ) ); ?>
						</aside>
					<?php endif; ?>

					<header>
						<h1 class="post-title"><?php the_title(); ?></h1>
						<div class="post-meta">
							<?php
								printf(
									'Published by %s on <time datetime="%s">%s</time> under %s',
									get_the_author_posts_link(),
									esc_attr( get_the_date( 'c' ) ),
									esc_html( get_the_date( 'F j, Y' ) ),
									wp_kses_post( $skills_list ?: 'Uncategorized' )
								);
							?>
						</div>
					</header>

					<div class="post-content">
						<?php the_content(); ?>
					</div>

					<?php if ( has_tag() ) : ?>
						<p class="tags"><?php the_tags( 'Tags: ', ', ', '' ); ?></p>
					<?php endif; ?>
				</article>

				<section class="author-box">
					<div class="avatar-block">
						<?php echo get_avatar( $author_id, 100, '', '', array( 'loading' => 'lazy' ) ); ?>
					</div>
					<div class="author-info">
						<h5><?php the_author_posts_link(); ?></h5>
						<p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>

						<ul class="list-author-meta list-connections list-unstyled">
							<?php if ( $author_url ) : ?>
								<li class="i-info"><a href="<?php echo $author_url; ?>" target="_blank" rel="noopener">Website</a></li>
							<?php endif; ?>
							<?php if ( $twitter ) : ?>
								<li class="i-twitter"><a href="https://twitter.com/<?php echo esc_attr( $twitter ); ?>" target="_blank" rel="noopener">Twitter</a></li>
							<?php endif; ?>
							<?php if ( $facebook ) : ?>
								<li class="i-facebook"><a href="<?php echo $facebook; ?>" target="_blank" rel="noopener">Facebook</a></li>
							<?php endif; ?>
							<?php if ( $google ) : ?>
								<li class="i-gplus"><a href="https://googleplus.com/<?php echo esc_attr( $google ); ?>" target="_blank" rel="noopener">Google+</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</section>

				<nav class="single-post-nav">
					<div class="next-post"><?php previous_post_link( '%link &rarr;' ); ?></div>
					<div class="prev-post"><?php next_post_link( '&larr; %link' ); ?></div>
				</nav>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>

			<?php else : ?>
				<h2>Not Found</h2>
				<p>Sorry, but you are looking for something that isn&#8217;t here.</p>
			<?php endif; ?>

		</section>
	</div>
</div>

<?php get_footer(); ?>
