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

$heading  = get_field( 'heading' )  ?: 'Final Message';
$emphasis = get_field( 'emphasis' ) ?: "My goal is to ensure that the piece you choose\nis not only beautiful, but meaningful and lasting.";
$body     = get_field( 'body' )     ?: "For the moments that matter most,\nthe right piece should feel personal, timeless, and truly yours.";
?>

<section
	class="message-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="inner">

			<?php if ( $heading ) : ?>
				<h2 class="heading h2"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<div class="content">

				<?php if ( $emphasis ) : ?>
					<p class="emphasis h2"><?php echo esc_html( $emphasis ); ?></p>
				<?php endif; ?>

				<?php if ( $body ) : ?>
					<p class="body h4"><?php echo esc_html( $body ); ?></p>
				<?php endif; ?>

			</div>

		</div>

	</div>

</section>
