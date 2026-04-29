<?php
/**
 * 404 page
 *
 * @package Skeleton
 */

get_header();

if ( PAGE_404_ID ) {
	skel_insert_page( PAGE_404_ID, true );
}

get_footer();
