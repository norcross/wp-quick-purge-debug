<?php
/**
 * Plugin Name:     Quick Purge Debug for WordPress
 * Plugin URI:      https://github.com/norcross/wp-quick-purge-debug
 * Description:     Add ways to quickly purge the debug log file.
 * Author:          Andrew Norcross
 * Author URI:      http://andrewnorcross.com
 * Text Domain:     quick-purge-debug
 * Domain Path:     /languages
 * Version:         0.0.1
 *
 * @package         QuickPurgeDebug
 */

// Call our namepsace.
namespace Norcross\QuickPurgeDebug;

// Call our CLI namespace.
use WP_CLI;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Define our plugin version.
define( __NAMESPACE__ . '\VERS', '0.0.1-dev' );

// Plugin root file.
define( __NAMESPACE__ . '\FILE', __FILE__ );

// Define our file base.
define( __NAMESPACE__ . '\BASE', plugin_basename( __FILE__ ) );

// Plugin Folder URL.
define( __NAMESPACE__ . '\URL', plugin_dir_url( __FILE__ ) );

// Set our includes path constants.
define( __NAMESPACE__ . '\INCLUDES_PATH', __DIR__ . '/includes' );

// Set the various prefixes for our actions and filters.
define( __NAMESPACE__ . '\HOOK_PREFIX', 'quick_purge_debug_' );
define( __NAMESPACE__ . '\NONCE_PREFIX', 'qk_prgdb_nonce_' );

// Set some defined IDs.
define( __NAMESPACE__ . '\ADMIN_BAR_ID', 'quick-purge-debug-ab' );

// Set the name of the debug file.
define( __NAMESPACE__ . '\DEBUG_LOGFILE', WP_CONTENT_DIR . '/debug.log' );

// Now we handle all the various file loading.
ncr_quick_purge_debug_file_load();

/**
 * Actually load our files.
 *
 * @return void
 */
function ncr_quick_purge_debug_file_load() {

	// Pull in the helper.
	require_once __DIR__ . '/includes/helpers.php';

	// Now our admin pieces.
	require_once __DIR__ . '/includes/admin/admin-bar.php';
	require_once __DIR__ . '/includes/admin/setup.php';
	require_once __DIR__ . '/includes/admin/request.php';
	require_once __DIR__ . '/includes/admin/notices.php';

	// Check that we have the CLI constant available.
	if ( defined( 'WP_CLI' ) && WP_CLI ) {

		// Load our commands file.
		require_once dirname( __FILE__ ) . '/includes/cli-commands.php';

		// And add our command.
		WP_CLI::add_command( 'quick-debug-purge', QuickPurgeDebugCLICommands::class );
	}

	// Load the triggered file loads.
	require_once __DIR__ . '/includes/activate.php';
	require_once __DIR__ . '/includes/deactivate.php';
	require_once __DIR__ . '/includes/uninstall.php';
}
