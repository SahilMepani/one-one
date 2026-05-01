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

$heading     = get_field( 'heading' )     ?: 'The 4Cs of Diamonds';
$description = get_field( 'description' ) ?: 'The quality and value of a diamond are determined by four characteristics known as the 4Cs: Cut, Color, Clarity and Carat. Understanding how these elements interact helps you choose a diamond that balances brilliance, beauty and value.';

$items = get_field( 'items' ) ?: array(
	array(
		'title'       => 'CUT',
		'description' => "The cut of a diamond determines how well it reflects light. A well-proportioned diamond allows light to enter and reflect back through the top of the stone, creating brilliance and sparkle.\n\nCut is often considered the most important factor influencing a diamond's visual beauty.",
		'image'       => DEFAULT_THUMBNAIL_ID,
	),
	array(
		'title'       => 'COLOR',
		'description' => "Diamond color measures how colorless the stone appears. The grading scale ranges from D (colorless) to Z (noticeable color).\n\nIn fine jewelry, diamonds in the colorless and near-colorless range (D–H) are most commonly selected. Careful selection within this range ensures exceptional brightness and elegance.",
		'image'       => DEFAULT_THUMBNAIL_ID,
	),
	array(
		'title'       => 'CLARITY',
		'description' => "Clarity refers to natural characteristics inside the diamond known as inclusions. Most inclusions are microscopic and invisible to the naked eye.\n\nClarity grades such as VVS and VS diamonds offer excellent transparency and are widely chosen for fine jewelry.",
		'image'       => DEFAULT_THUMBNAIL_ID,
	),
	array(
		'title'       => 'CARAT',
		'description' => "Carat refers to the weight of the diamond. However, visual size is influenced not only by carat weight but also by shape and proportions.\n\nTwo diamonds with the same carat weight may appear different in size depending on their cut.",
		'image'       => DEFAULT_THUMBNAIL_ID,
	),
);
?>

<section
	class="features-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="features">

			<header class="intro">
				<?php if ( $heading ) : ?>
					<h2 class="heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<div class="description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
				<?php endif; ?>
			</header>

			<?php if ( ! empty( $items ) ) : ?>
				<ul class="items" role="list">
					<?php foreach ( $items as $item ) : ?>
						<?php
						$item_title       = $item['title']       ?? '';
						$item_description = $item['description'] ?? '';
						$item_image       = $item['image']       ?: DEFAULT_THUMBNAIL_ID;
						?>
						<li class="item">
							<div class="body">
								<?php if ( $item_title ) : ?>
									<h3 class="title"><?php echo esc_html( $item_title ); ?></h3>
								<?php endif; ?>

								<?php if ( $item_description ) : ?>
									<div class="copy"><?php echo wp_kses_post( wpautop( $item_description ) ); ?></div>
								<?php endif; ?>
							</div>

							<div class="image">
								<?php
								echo wp_get_attachment_image(
									$item_image,
									'w1920',
									false,
									array(
										'class'   => 'img-responsive',
										'loading' => 'lazy',
										'sizes'   => 'auto',
									)
								);
								?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div>

	</div>

</section>
