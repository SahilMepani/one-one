<?php
	$class = $args['class'] ?? '';
	$attr  = $args['attr'] ?? '';
	$style = isset( $args['style'] ) ? "style-{$args['style']}" : '';
?>

<div class="swiper-navigation <?php echo esc_attr( "{$class} {$style}" ); ?>" <?php echo wp_kses_post( $attr ); ?>>
	<div class="swiper-button-prev">
		<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 24 24">
			<path d="m10.308 17.308.707-.72L6.927 12.5H19v-1H6.927l4.088-4.088-.707-.72L5 12l5.308 5.308Z" />
		</svg>
	</div>
	<div class="swiper-button-next">
		<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 24 24">
			<path d="m13.692 17.308-.707-.72 4.088-4.088H5v-1h12.073l-4.088-4.088.707-.72L19 12l-5.308 5.308Z" />
		</svg>
	</div>
</div> <!-- .swiper-navigation -->
