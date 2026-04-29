<?php
/**
 * Block editor settings
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * When set, users will be restricted to the default sizes provided
 *  in the block editor or the sizes provided via the
 * editor-font-sizes theme support setting.
 */
add_theme_support( 'disable-custom-font-sizes' );


/**
 * This flag will make sure users are only able to choose colors from
 * the editor-color-palette the theme provided or from the editor default
 * colors if the theme did not provide one.
 */
add_theme_support( 'disable-custom-colors' );


/**
 * Enable responsive embedded content
 */
add_theme_support( 'responsive-embeds' );


/**
 * Load custom css in editor
 *
 * @return void
 */
function skel_editor_css(): void {
	?>
	<style type="text/css">

	.edit-post-visual-editor .core-block-preview {
		padding: 30px;
		color: gray;
		text-align: center;
		border: 2px dashed rgba(0,0,0, 0.5);
	}

	.edit-post-visual-editor .acf-block-fields > .acf-field > .acf-accordion-content > .acf-fields > .acf-tab-wrap > .acf-tab-group {
		display: flex;
		margin: 0;
		padding: 0;
		border: none;
	}

	.edit-post-visual-editor .acf-block-fields > .acf-field > .acf-accordion-content > .acf-fields > .acf-tab-wrap > .acf-tab-group li {
		margin: 0;
	}

	.edit-post-visual-editor .acf-block-fields > .acf-field > .acf-accordion-content > .acf-fields > .acf-tab-wrap > .acf-tab-group li:nth-child(1) {
		inline-size: 100%;
	}

	.edit-post-visual-editor .acf-block-fields > .acf-field > .acf-accordion-content > .acf-fields > .acf-tab-wrap > .acf-tab-group li:nth-child(2) {
		flex-shrink: 0;
	}

	.edit-post-visual-editor .acf-block-fields > .acf-field > .acf-accordion-content > .acf-fields > .acf-tab-wrap > .acf-tab-group li a {
		padding: 5px 20px 4px;
		font-size: 10px;
		font-weight: bold;
		text-transform: uppercase;
		border: none;
	}

	.edit-post-visual-editor .acf-block-fields > .acf-field > .acf-accordion-content > .acf-fields > .acf-tab-wrap > .acf-tab-group li:not(.active) a {
		background: rgba(0,0,0, 0.03);
	}

	.edit-post-visual-editor .acf-block-fields > .acf-field > .acf-accordion-content > .acf-fields > .acf-tab-wrap > .acf-tab-group li:not(.active) a:hover {
		background: rgba(0,0,0, 0.01);
	}

	</style>
	<?php
}
add_action( 'admin_head-post.php', 'skel_editor_css' );
add_action( 'admin_head-post-new.php', 'skel_editor_css' );
