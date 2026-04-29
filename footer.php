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

<a href="#" class="scroll-to-top scroll-to" aria-label="Scroll to Top">
	<svg viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
		<path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>
</a>

<?php wp_footer(); ?>

<!-- Testing commit -->
</body>

</html>
