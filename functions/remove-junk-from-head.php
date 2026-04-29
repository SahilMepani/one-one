<?php //phpcs:ignore file comment
/**
 * Description: Removes unnecessary items from the head section of the website.
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since 1.0.0
 */

/**
 * Remove unnecessary items from the head section.
 *
 * This function removes various items from the head section of the website such as RSD link,
 * WordPress version, RSS feed links, index link, Windows Live Writer link, random post link,
 * parent post link, next and previous post links, and shortlinks.
 *
 * @return void
 */
function skel_remove_junk_from_head() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
}
add_action( 'init', 'skel_remove_junk_from_head' );

/**
 * Disable Emojis.
 *
 * This function disables emojis from the website to reduce unnecessary overhead.
 *
 * @return void
 */
function skel_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'skel_disable_emojis' );

/**
 * Remove the TinyMCE emoji plugin.
 *
 * @param array $plugins Array of TinyMCE plugins.
 * @return array Modified array of TinyMCE plugins.
 */
function disable_emojis_tinymce( $plugins ): array {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array  $urls Array of URLs.
 * @param string $relation_type The relation type.
 * @return array Modified array of URLs.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ): array {
	if ( 'dns-prefetch' === $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls          = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}
