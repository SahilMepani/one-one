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

$intro_text       = get_field( 'intro_text' )       ?: 'Common diamond shapes include';
$featured_image   = get_field( 'featured_image' )   ?: DEFAULT_THUMBNAIL_ID;
$description      = get_field( 'description' )      ?: 'Different shapes can also influence how large a diamond appears on the hand. For example, oval and pear shapes often appear slightly larger than round diamonds of the same carat weight.';
$items            = get_field( 'items' )            ?: array(
	array(
		'label'     => 'Round',
		'image'     => DEFAULT_THUMBNAIL_ID,
		'is_active' => true,
	),
	array(
		'label'     => 'Oval',
		'image'     => DEFAULT_THUMBNAIL_ID,
		'is_active' => false,
	),
	array(
		'label'     => 'Emerald',
		'image'     => DEFAULT_THUMBNAIL_ID,
		'is_active' => false,
	),
	array(
		'label'     => 'Cushion',
		'image'     => DEFAULT_THUMBNAIL_ID,
		'is_active' => false,
	),
	array(
		'label'     => 'Pear',
		'image'     => DEFAULT_THUMBNAIL_ID,
		'is_active' => false,
	),
	array(
		'label'     => 'Radiant',
		'image'     => DEFAULT_THUMBNAIL_ID,
		'is_active' => false,
	),
	array(
		'label'     => 'Marquise',
		'image'     => DEFAULT_THUMBNAIL_ID,
		'is_active' => false,
	),
	array(
		'label'     => 'Asscher',
		'image'     => DEFAULT_THUMBNAIL_ID,
		'is_active' => false,
	),
);
?>

<section
	class="shapes-section section <?php echo esc_attr( "{$dev_options['display_class']} {$dev_options['spacing_top']} {$dev_options['spacing_bottom']} {$dev_options['custom_classes']}" ); ?>"
	style="<?php echo esc_attr( "{$dev_options['spacing_top_custom']} {$dev_options['spacing_bottom_custom']} {$dev_options['custom_css']}" ); ?>"
	id="<?php echo esc_attr( $dev_options['unique_id'] ); ?>">

	<div class="container">

		<div class="shapes">

			<?php if ( $intro_text ) : ?>
				<p class="intro"><?php echo esc_html( $intro_text ); ?></p>
			<?php endif; ?>

			<div class="layout">

				<div class="featured">
					<?php
					echo wp_get_attachment_image(
						$featured_image,
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

				<?php if ( ! empty( $items ) ) : ?>
					<ul class="grid">
						<?php foreach ( $items as $item ) : ?>
							<?php
							$item_image  = $item['image']     ?: DEFAULT_THUMBNAIL_ID;
							$item_label  = $item['label']     ?? '';
							$item_active = ! empty( $item['is_active'] );
							$card_class  = 'card' . ( $item_active ? ' card--active' : '' );
							?>
							<li class="<?php echo esc_attr( $card_class ); ?>">
								<div class="image">
									<?php
									echo wp_get_attachment_image(
										$item_image,
										'w600',
										false,
										array(
											'class'   => 'img-responsive',
											'loading' => 'lazy',
											'sizes'   => 'auto',
										)
									);
									?>
								</div>
								<?php if ( $item_label ) : ?>
									<p class="label"><?php echo esc_html( $item_label ); ?></p>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

			</div>

			<?php if ( $description ) : ?>
				<p class="description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

		</div>

	</div>

</section>
