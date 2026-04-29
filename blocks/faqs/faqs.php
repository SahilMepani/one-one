<?php
/**
 * FAQs ACF block
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
$faq_type = get_field( 'faq_type' );

// Validate FAQ data exists before proceeding.
if ( 'latest' === $faq_type ) {
	$faq_categories = get_field( 'faq_categories' );
	$faq_dividers   = get_field( 'faq_dividers' );
	// At least one taxonomy should be selected for latest type.
	if ( empty( $faq_categories ) && empty( $faq_dividers ) ) {
		return;
	}
}
if ( 'selected' === $faq_type ) {
	$selected_faq = get_field( 'selected_faq' );
	if ( ! is_array( $selected_faq ) || empty( $selected_faq ) ) {
		return;
	}
}
if ( 'custom' === $faq_type ) {
	$custom_faq = get_field( 'custom_faq' );
	if ( ! is_array( $custom_faq ) || empty( $custom_faq ) ) {
		return;
	}
}

$section_heading = get_field( 'section_heading' );
$faq_count       = get_field( 'faq_count' ) ?: -1; // phpcs:ignore
$button          = get_field( 'button' );

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section class="faq-section js-faq-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>" id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<?php if ( $section_heading ) { ?>
			<h4 class="section-heading h3" data-inview data-aos="fade-up"><?php echo esc_html( $section_heading ); ?></h4>
		<?php } ?>

		<div class="list-accordion" data-inview>
			<?php
			// Custom.
			if ( 'custom' === $faq_type ) :
				foreach ( $custom_faq as $faq ) :
					$question = esc_html( $faq['question'] ?? '' );
					$answer   = wp_kses_post( $faq['answer'] ?? '' );
					?>
				<div class="accordion" data-aos-stagger-item data-aos="fade-up">
					<?php if ( $question ) { ?>
						<p class="accordion-heading h5">
							<?php echo $question; ?>
							<span class="icon"></span>
						</p>
					<?php } ?>
					<?php if ( $answer ) { ?>
						<div class="accordion-content">
							<div class="inner-block">
								<?php echo $answer; ?>
							</div> <!-- .inner-block -->
						</div>
					<?php } ?>
				</div>
					<?php
			endforeach;
		endif;
			?>

			<?php
			// Selcted or Latest.
			if ( 'latest' === $faq_type || 'selected' === $faq_type ) :
				if ( 'latest' === $faq_type ) {
					$faq_args = array(
						'post_type'      => 'faq',
						'post_status'    => 'publish',
						'fields'         => 'ids',
						'posts_per_page' => $faq_count,
						'no_found_rows'  => true,
					);

					$tax_query = array(
						'relation' => 'OR',
					);

					if ( ! empty( $faq_categories ) && is_array( $faq_categories ) ) {
						$tax_query[] = array(
							'taxonomy' => 'faq-category',
							'terms'    => $faq_categories,
							'field'    => 'id',
						);
					}

					if ( ! empty( $faq_dividers ) && is_array( $faq_dividers ) ) {
						$tax_query[] = array(
							'taxonomy' => 'faq-divider',
							'terms'    => $faq_dividers,
							'field'    => 'id',
						);
					}

					if ( ! empty( $tax_query ) ) {
						$faq_args['tax_query'] = $tax_query; //phpcs:ignore
					}

					$faqs = get_posts( $faq_args );

				} elseif ( 'selected' === $faq_type ) {
					$faqs = $selected_faq;
				}

				if ( ! is_array( $faqs ) || empty( $faqs ) ) {
					return;
				}

				foreach ( $faqs as $faq_id ) :
						$question = esc_html( get_post_field( 'post_title', $faq_id ) );
						$answer   = wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', $faq_id ) ) );
					?>
					<div class="accordion" data-aos-stagger-item data-aos="fade-up">
						<?php if ( $question ) { ?>
							<p class="accordion-heading h5">
								<?php echo $question; ?>
								<span class="icon"></span>
							</p>
						<?php } ?>
						<?php if ( $answer ) { ?>
							<div class="accordion-content">
								<div class="inner-block">
									<?php echo $answer; ?>
								</div> <!-- .inner-block -->
							</div>
						<?php } ?>
					</div>
						<?php
				endforeach;
			endif;
			?>
		</div>

		<?php if ( is_array( $button ) && $button['url'] ) { ?>
		<div data-inview data-aos="fade-up">
			<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"
				class="btn btn-cta btn-md btn-dark-blue">
				<?php
					$text = ( $button['title'] ) ? $button['title'] : __( 'View More', 'skel' );
					echo esc_html( $text );
				?>
			</a>
		</div>
		<?php } ?>

	</div> <!-- .container -->
</section>
