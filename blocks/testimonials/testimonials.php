<?php
/**
 * Testimonials ACF block
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
$heading = get_field( 'heading' ) ?: 'Why ONE·ONE';
$intro   = get_field( 'intro' )   ?: "Many clients come to me when they feel\nsomething like this";
$footer  = get_field( 'footer' )  ?: 'This is exactly where ONE·ONE was created to help.';
$items   = get_field( 'items' )   ?: array(
	array( 'quote' => 'I want to choose the right diamond, but I’m not sure what truly matters.' ),
	array( 'quote' => 'I’ve seen a piece I love, but I’m unsure if the price reflects its real value.' ),
	array( 'quote' => 'I want something meaningful and personal, not just what is available in a store.' ),
	array( 'quote' => 'I want guidance from someone who truly understands diamonds.' ),
);

// Developer options.
$dev_options = skel_get_block_developer_options();
?>

<section
	class="testimonials-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<header class="header">
			<?php if ( $heading ) : ?>
				<h2 class="heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php if ( $intro ) : ?>
				<p class="intro"><?php echo wp_kses_post( wpautop( $intro, false ) ); ?></p>
			<?php endif; ?>
		</header>

		<?php if ( ! empty( $items ) ) : ?>
			<ul class="cards" role="list">
				<?php foreach ( $items as $item ) : ?>
					<?php $quote = isset( $item['quote'] ) ? $item['quote'] : ''; ?>
					<?php if ( ! $quote ) : continue; endif; ?>
					<li class="card">
						<blockquote class="quote">
							<p><?php echo esc_html( $quote ); ?></p>
						</blockquote>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $footer ) : ?>
			<p class="footer"><?php echo esc_html( $footer ); ?></p>
		<?php endif; ?>

	</div>

</section>
