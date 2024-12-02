<?php
/**
 * Our uninstall call.
 *
 * @package QuickPurgeDebug
 */

// Declare our namespace.
namespace Norcross\QuickPurgeDebug\Uninstall;

// Set our aliases.
use Norcross\QuickPurgeDebug as Core;

/**
 * Delete various options when uninstalling the plugin.
 *
 * @return void
 */
function uninstall() {

	// Include our action so that we may add to this later.
	do_action( Core\HOOK_PREFIX . 'uninstall_process' );
}
register_uninstall_hook( Core\FILE, __NAMESPACE__ . '\uninstall' );
