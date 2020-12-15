<?php
/**
 * Our admin bar functions.
 *
 * @package QuickPurgeDebug
 */

// Declare our namespace.
namespace Norcross\QuickPurgeDebug\Admin\AdminBar;

// Set our aliases.
use Norcross\QuickPurgeDebug as Core;
use Norcross\QuickPurgeDebug\Helpers as Helpers;

/**
 * Start our engines.
 */
add_action( 'admin_bar_menu', __NAMESPACE__ . '\add_admin_bar_item', 9999 );

/**
 * Add our new icon in the admin bar.
 *
 * @param  WP_Admin_Bar $wp_admin_bar  The global WP_Admin_Bar object.
 *
 * @return void
 */
function add_admin_bar_item( \WP_Admin_Bar $wp_admin_bar ) {

	// Add the check to hide the empty.
	$check_show = maybe_hide_admin_icon();

	// Bail if we got a false.
	if ( false === $check_show ) {
		return;
	}

	// Get my purge file args.
	$purge_args = fetch_admin_bar_args();

	// If we have purge args, continue.
	if ( ! empty( $purge_args ) ) {
		$wp_admin_bar->add_node( $purge_args );
	}
}

/**
 * Check to see if we should show the empty.
 *
 * @return boolean
 */
function maybe_hide_admin_icon() {

	// First pass in our filter.
	$hide_empty = apply_filters( Core\HOOK_PREFIX . 'hide_icon_when_empty', true );

	// If we don't care, show it regardless.
	if ( false === $hide_empty ) {
		return true;
	}

	// Now get the file size.
	$debug_size = Helpers\get_logfile_size();

	// Now return the result.
	return ! empty( $debug_size ) ? true : false;
}

/**
 * Set all the args for the purge debug admin bar item.
 *
 * @return array
 */
function fetch_admin_bar_args() {

	// Get the actual purge link.
	$get_admin_bar_link = Helpers\get_logfile_purge_link();

	// Bail if we don't have a link.
	if ( empty( $get_admin_bar_link ) ) {
		return;
	}

	// Get the standard title, which is a trash can for us.
	$admin_bar_titles   = Helpers\get_admin_bar_titles();

	// Decide if a target blank.
	$maybe_link_target  = ! is_admin() ? '_blank' : '';

	// Now set up the args.
	$set_admin_bar_args = array(
		'id'       => Core\ADMIN_BAR_ID,
		'title'    => $admin_bar_titles['title'],
		'href'     => esc_url( $get_admin_bar_link ),
		'position' => 0,
		'meta'     => array(
			'title'    => esc_attr( $admin_bar_titles['hover'] ),
			'target'   => $maybe_link_target,
		),
	);

	// And return them, filtered.
	return apply_filters( Core\HOOK_PREFIX . 'admin_bar_args', $set_admin_bar_args );
}
