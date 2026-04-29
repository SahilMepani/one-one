<?php
/**
 * Not Found 404 ACF Block
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
$heading     = get_field( 'heading' );
$heading     = $heading ? $heading : __( 'Page not found.', 'skel' );
$description = get_field( 'description' );
$description = $description ? $description : __(
	'<p>The page you're looking for might have been removed, had its name changed, or is temporarily unavailable . Please check the URL or return to the homepage . < / p > ',
	'skel'
);

// Developer options.
$opts                  = skel_get_block_developer_options();
$display_class         = $opts['display_class'];
$spacing_top           = $opts['spacing_top'];
$spacing_bottom        = $opts['spacing_bottom'];
$spacing_top_custom    = $opts['spacing_top_custom'];
$spacing_bottom_custom = $opts['spacing_bottom_custom'];
$custom_classes        = $opts['custom_classes'];
$custom_css            = $opts['custom_css'];
$unique_id             = $opts['unique_id'];
?>

<section
	class="not-found-404-section section <?php echo esc_attr( "{$display_class} {$spacing_top} {$spacing_bottom} {$custom_classes}" ); ?>"
	style="<?php echo esc_attr( "{$spacing_top_custom} {$spacing_bottom_custom} {$custom_css}" ); ?>"
	id="<?php echo esc_attr( $unique_id ); ?>">

	<div class="container-small">

		<?php if ( $heading ) { ?>
			<h1 class="heading"><?php echo esc_html( $heading ); ?></h1>
		<?php } ?>

		<?php if ( $description ) { ?>
			<?php echo wp_kses_post( $description ); ?>
		<?php } ?>

	</div><!-- .container -->
</section>
