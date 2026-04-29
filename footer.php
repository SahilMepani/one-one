<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #site-content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @since 1.0.0
 */

?>

</main> <!-- #site-content -->

<footer class="site-footer">
	<div class="container">

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer-menu',
				'container'      => '',
				'menu_class'     => 'footer-menu',
			)
		);
		?>

		<p class="copyright">
			&copy;<?php echo esc_html( gmdate( 'Y' ) ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'skel' ); ?>
		</p>

	</div> <!-- .container -->
</footer> <!-- #footer -->

<a href="#" class="scroll-to-top scroll-to" aria-label="<?php esc_attr_e( 'Scroll to Top', 'skel' ); ?>"></a>

<?php wp_footer(); ?>

<!-- Testing commit -->
</body>

</html>
