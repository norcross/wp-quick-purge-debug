<?php
/**
 * Our basic admin setup functions.
 *
 * @package QuickPurgeDebug
 */

// Declare our namespace.
namespace Norcross\QuickPurgeDebug\Admin\Setup;

// Set our aliases.
use Norcross\QuickPurgeDebug as Core;
use Norcross\QuickPurgeDebug\Helpers as Helpers;

/**
 * Start our engines.
 */
add_filter( 'removable_query_args', __NAMESPACE__ . '\admin_removable_args' );

/**
 * Add our custom strings to the vars.
 *
 * @param  array $args  The existing array of args.
 *
 * @return array $args  The modified array of args.
 */
function admin_removable_args( $args ) {

	// Set an array of the args we wanna exclude.
	$remove = [
		'qpd-purge-run',
		'qpd-purge-nonce',
		'qpd-purge-complete',
		'qpd-purge-result',
		'qpd-purge-error',
	];

	// Include my new args and return.
	return wp_parse_args( $remove, $args );
}
