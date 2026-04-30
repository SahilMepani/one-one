<?php
// Set thumbnail preview in backend.
if ( skel_render_block_preview( $block ) ) {
	return;
}

// Return early if display is off.
if ( ! skel_should_display_block() ) {
	return;
}

// Developer options.
$dev_options = skel_get_block_developer_options();

$label   = get_field( 'label' )   ?: 'Our Process';
$heading = get_field( 'heading' ) ?: 'How the Process Works';
$steps   = get_field( 'steps' )   ?: array(
	array(
		'title'       => 'Consultation',
		'description' => 'We begin with a conversation to understand your vision, preferences, and priorities.',
	),
	array(
		'title'       => 'Diamond Selection',
		'description' => 'Carefully sourced diamonds or gemstones are presented for review based on your criteria.',
	),
	array(
		'title'       => 'Design',
		'description' => 'Once the stone is selected, the design of the jewelry piece is developed according to your preferences.',
	),
	array(
		'title'       => 'Creation',
		'description' => 'The piece is crafted by experienced jewelers using refined techniques and materials.',
	),
	array(
		'title'       => 'Delivery',
		'description' => 'Your finished jewelry is carefully prepared and presented once the piece is completed.',
	),
);
?>

<section
	class="process-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="header">
			<?php if ( $label ) : ?>
				<span class="label"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h2 class="heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $steps ) && is_array( $steps ) ) : ?>
			<ol class="steps">
				<?php foreach ( $steps as $index => $step ) : ?>
					<li class="step">
						<p class="step-number" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></p>
						<div class="step-content">
							<?php if ( ! empty( $step['title'] ) ) : ?>
								<h3 class="step-title"><?php echo esc_html( $step['title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $step['description'] ) ) : ?>
								<p class="step-description"><?php echo esc_html( $step['description'] ); ?></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>

	</div>

</section>
