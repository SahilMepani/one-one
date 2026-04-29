<?php
/**
 * Search form
 *
 * @package Skeleton
 */

$search_term = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
?>


<form method="get" action="<?php echo esc_url( home_url() ); ?>" class="search-form" role="search">
	<label for="search-field" class="screen-reader-text"><?php esc_html_e( 'Search', 'skel' ); ?></label>
	<input
		type="search"
		id="search-field"
		name="s"
		class="search"
		placeholder="<?php echo esc_attr__( 'Search', 'skel' ); ?>"
		value="<?php echo esc_attr( $search_term ); ?>"
		required
	/>
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'search_nonce' ) ); ?>" />
	<button type="submit" class="submit btn">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
			<path d="M19.4817 20.154L13.2197 13.892C12.7197 14.318 12.1447 14.6477 11.4947 14.881C10.8447 15.1144 10.1914 15.231 9.53469 15.231C7.93335 15.231 6.57802 14.6767 5.46869 13.568C4.35935 12.4587 3.80469 11.1037 3.80469 9.50304C3.80469 7.90238 4.35869 6.54671 5.46669 5.43604C6.57535 4.32471 7.93002 3.76904 9.53069 3.76904C11.132 3.76904 12.488 4.32371 13.5987 5.43304C14.7094 6.54238 15.2647 7.89804 15.2647 9.50004C15.2647 10.1947 15.1417 10.867 14.8957 11.517C14.649 12.167 14.3257 12.723 13.9257 13.185L20.1877 19.446L19.4817 20.154ZM9.53469 14.23C10.8614 14.23 11.9817 13.7734 12.8957 12.86C13.809 11.9467 14.2657 10.8264 14.2657 9.49904C14.2657 8.17238 13.809 7.05238 12.8957 6.13904C11.9824 5.22571 10.8624 4.76904 9.53569 4.76904C8.20902 4.76904 7.08869 5.22571 6.17469 6.13904C5.26135 7.05238 4.80469 8.17238 4.80469 9.49904C4.80469 10.8257 5.26135 11.9457 6.17469 12.859C7.08802 13.7724 8.20802 14.23 9.53469 14.23Z" fill="black"/>
		</svg>
	</button>
</form>
