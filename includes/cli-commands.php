<?php
/**
 * The functionality tied to the WP-CLI stuff.
 *
 * @package QuickPurgeDebug
 */

// Call our namepsace (same as the base).
namespace Norcross\QuickPurgeDebug;

// Set our alias items.
use Norcross\QuickPurgeDebug as Core;
use Norcross\QuickPurgeDebug\Helpers as Helpers;

// Pull in the CLI items.
use WP_CLI;
use WP_CLI_Command;

/**
 * Include a CLI command to clear out the debug file.
 */
class QuickPurgeDebugCLICommands extends WP_CLI_Command {

	/**
	 * Go ahead and run the CLI command.
	 *
	 * ## EXAMPLES
	 *
	 *     wp quick-debug-purge run
	 *
	 * @when after_wp_load
	 */
	function run() {

		// Get our logfile.
		$fetch_logfile_src  = Helpers\get_logfile_src();

		// First bail if no file name came back.
		if ( empty( $fetch_logfile_src ) ) {
			WP_CLI::error( Helpers\get_admin_notice_text( 'undefined-log-file' ) );
		}

		// Now bail if the file doesn't exist.
		if ( ! file_exists( $fetch_logfile_src ) ) {
			WP_CLI::error( Helpers\get_admin_notice_text( 'missing-log-file' ) );
		}

		// Erase the debug file.
		$maybe_purge_file   = file_put_contents( $fetch_logfile_src, '' );

		// Throw the error message if that failed.
		if ( false === $maybe_purge_file ) {
			WP_CLI::error( Helpers\get_admin_notice_text( 'purge-file-error' ) );
		}

		// Tell me I did a good job.
		WP_CLI::success( Helpers\get_admin_notice_text( 'purge-success' ) );
		WP_CLI::halt( 0 );
	}

	// End all custom CLI commands.
}
