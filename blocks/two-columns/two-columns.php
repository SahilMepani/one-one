<?php
/**
 * Two Columns block
 *
 * @package Skeleton
 * @subpackage ACF
 */

// Set thumbnail preview in backend.
if ( skel_render_block_preview( $block ) ) {
	return;
}

// Return early if display is off.
if ( ! skel_should_display_block() ) {
	return;
}

// Data options.
$columns = get_field( 'columns' );

if ( ! is_array( $columns ) || empty( $columns ) ) {
	return;
}

$section_background = get_field( 'section_background' );
$section_heading    = get_field( 'section_heading' );
$column_order       = get_field( 'column_order' );
$column_width       = get_field( 'column_width' );

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="two-columns-section section <?php echo esc_attr( "{$dev_options['display_class']} bg-" . esc_attr( $section_background ) . " {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container" style="--grid-first-col: <?php echo esc_html( $column_width ); ?>;">

		<?php if ( $section_heading ) { ?>
		<h3 class="h2 section-heading">
			<?php
				// phpcs:ignore
				echo esc_html( $section_heading );
			?>
		</h3>
		<?php } ?>

		<div class="row <?php echo esc_attr( $column_order ); ?>" data-inview data-aos="fade-up">
			<?php
			foreach ( $columns as $col ) :
					$col_content_type = $col['content_type'];
					$col_text         = $col['text'];
					$col_video        = $col['video'];
					$col_video_type   = $col_video['video_type'];
					$col_video_file   = $col_video['video_file'];
					$col_stream_url   = skel_extract_oembed_src( $col_video['stream_url'] );
					$col_image        = $col['image'];
					$dialog_id        = skel_get_random_string();
				?>

					<div class="col">
							<?php
							if ( 'text' === $col_content_type && $col_text ) {
								echo wp_kses_post( $col_text );
							}
							?>

							<?php
							if ( ( 'text' !== $col_content_type ) && $col_image ) {
								?>
						<div class="img-dialog-block">
								<?php
								echo wp_get_attachment_image(
									$col_image,
									'w768',
									false,
									array(
										'class'   => 'img-responsive',
										'sizes'   => '100vw',
										'loading' => 'lazy',
									)
								);
								?>

								<?php if ( 'video' === $col_content_type ) { ?>
							<button class="btn btn-reset btn-dialog-open js-dialog-open" data-dialog="<?php echo esc_attr( $dialog_id ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 249 239" fill="none">
									<path d="M248.857 119.332L0.609375 0.166016V238.499L248.857 119.332Z" fill="#fff" />
								</svg>
							</button>

							<dialog class="dialog js-dialog" data-dialog="<?php echo esc_attr( $dialog_id ); ?>">
								<button class="btn btn-reset btn-dialog-close js-dialog-close">
									<svg viewBox="0 0 24 24" fill="none">
										<path
											d="M5.293 6.707l5.293 5.293-5.293 5.293c-0.391 0.391-0.391 1.024 0 1.414s1.024 0.391 1.414 0l5.293-5.293 5.293 5.293c0.391 0.391 1.024 0.391 1.414 0s0.391-1.024 0-1.414l-5.293-5.293 5.293-5.293c0.391-0.391 0.391-1.024 0-1.414s-1.024-0.391-1.414 0l-5.293 5.293-5.293-5.293c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414z"
											fill="currentColor"></path>
									</svg>
								</button>

									<?php if ( 'file' === $col_video_type && $col_video_file ) { ?>

								<video class="js-video" controls loop preload="none">
									<source src="<?php echo esc_url( $col_video_file ); ?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>

								<?php } elseif ( 'file' !== $col_video_type && $col_stream_url ) { ?>
								<iframe class="js-iframe" frameborder="0"
									allow="autoplay; encrypted-media; fullscreen; picture-in-picture" loading="lazy"
									data-video-url="<?php echo esc_url( $col_stream_url ); ?>"></iframe>
								<?php } ?>
							</dialog>
							<?php } ?>
						</div> <!-- .img-block -->
						<?php } ?>
					</div> <!-- .col -->

					<?php
			endforeach;
			?>
		</div> <!-- .row -->

	</div><!-- .container -->
</section>
