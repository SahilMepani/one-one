<?php
/**
 * Search
 *
 * @package Skeleton
 */

get_header();

if ( PAGE_SEARCH_ID ) {
	skel_insert_page( PAGE_SEARCH_ID, true );
}

get_footer();
