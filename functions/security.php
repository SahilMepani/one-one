<?php
/**
 * Description: Sets security-related constants to enhance WordPress security.
 *
 * @package Skeleton
 */

// Disallow File Modifications.
// Be careful using this, as it will disable the ability to update both core, plugins and themes in the WordPress Admin.
// It's very helpful for security or locking a website into a static version.
define( 'DISALLOW_FILE_MODS', true );

// Disable File Editing in WordPress Admin.
define( 'DISALLOW_FILE_EDIT', true );

// Disable XML-RPC.
add_filter( 'xmlrpc_enabled', '__return_false' );
