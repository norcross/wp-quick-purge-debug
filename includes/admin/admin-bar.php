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

	// Get my purge file args.
	$purge_args = fetch_admin_bar_args();

	// If we have purge args, continue.
	if ( ! empty( $purge_args ) ) {
		$wp_admin_bar->add_node( $purge_args );
	}
}

/**
 * Set all the args for the purge debug admin bar item.
 *
 * @return array
 */
function fetch_admin_bar_args() {

	// Get the actual purge link.
	$adminbar_link  = Helpers\get_logfile_purge_link();

	// Bail if we don't have a link.
	if ( empty( $adminbar_link ) ) {
		return;
	}

	// Get the standard title, which is a trash can for us.
	$fetch_titles    = Helpers\get_admin_bar_titles();

	// Decide if a target blank.
	$adminbar_blank = ! is_admin() ? '_blank' : '';

	// Now set up the args.
	$adminbar_array = array(
		'id'       => Core\ADMIN_BAR_ID,
		'title'    => $fetch_titles['title'],
		'href'     => esc_url( $adminbar_link ),
		'position' => 0,
		'meta'     => array(
			'title'    => esc_attr( $fetch_titles['hover'] ),
			'target'   => $adminbar_blank,
		),
	);

	// And return them, filtered.
	return apply_filters( Core\HOOK_PREFIX . 'admin_bar_args', $adminbar_array );
}
