<?php
/**
 * Our activation call
 *
 * @package QuickPurgeDebug
 */

// Declare our namespace.
namespace Norcross\QuickPurgeDebug\Activate;

// Set our aliases.
use Norcross\QuickPurgeDebug as Core;

/**
 * Our inital setup function when activated.
 *
 * @return void
 */
function activate() {

	// Include our action so that we may add to this later.
	do_action( Core\HOOK_PREFIX . 'activate_process' );

	// And flush our rewrite rules.
	flush_rewrite_rules();
}
register_activation_hook( Core\FILE, __NAMESPACE__ . '\activate' );
