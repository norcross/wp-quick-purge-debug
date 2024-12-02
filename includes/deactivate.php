<?php
/**
 * Our deactivation call.
 *
 * @package QuickPurgeDebug
 */

// Declare our namespace.
namespace Norcross\QuickPurgeDebug\Deactivate;

// Set our aliases.
use Norcross\QuickPurgeDebug as Core;

/**
 * Delete various options when deactivating the plugin.
 *
 * @return void
 */
function deactivate() {

	// Include our action so that we may add to this later.
	do_action( Core\HOOK_PREFIX . 'deactivate_process' );
}
register_deactivation_hook( Core\FILE, __NAMESPACE__ . '\deactivate' );
