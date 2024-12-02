<?php
/**
 * The admin bar request function
 *
 * @package QuickPurgeDebug
 */

// Declare our namespace.
namespace Norcross\QuickPurgeDebug\Admin\Request;

// Set our aliases.
use Norcross\QuickPurgeDebug as Core;
use Norcross\QuickPurgeDebug\Helpers as Helpers;

// And pull in any other namespaces.
use WP_Error;

/**
 * Start our engines.
 */
add_action( 'admin_init', __NAMESPACE__ . '\purge_debug_via_user' );

/**
 * Handle the user request to purge the debug file.
 *
 * @return void
 */
function purge_debug_via_user( $args ) {

	// Bail if the user is not logged in, an Ajax call, or a CLI call.
	if ( ! is_user_logged_in() || ! is_admin() || wp_doing_ajax() || defined( 'WP_CLI' ) && WP_CLI ) { // phpcs:ignore -- the nonce check is happening soon.
		return;
	}

	// Confirm we requested this action.
	$confirm_action = filter_input( INPUT_GET, 'qpd-purge-run', FILTER_SANITIZE_SPECIAL_CHARS ); // phpcs:ignore -- the nonce check is happening soon.

	// Make sure it is what we want.
	if ( empty( $confirm_action ) || 'yes' !== $confirm_action ) {
		return;
	}

	// Bail on a non authorized user.
	if ( ! current_user_can( 'manage_options' ) ) { // phpcs:ignore -- the nonce check is happening soon.
		wp_die( __( 'You are not authorized to perform this function.', 'quick-purge-debug' ), __( 'Quick Purge Debug Tool', 'quick-purge-debug' ) );
	}

	// Make sure we have a nonce.
	$confirm_nonce  = filter_input( INPUT_GET, 'qpd-purge-nonce', FILTER_SANITIZE_SPECIAL_CHARS ); // phpcs:ignore -- the nonce check is happening after this.

	// Handle the nonce check.
	if ( empty( $confirm_nonce ) || ! wp_verify_nonce( $confirm_nonce, Core\NONCE_PREFIX . '_run' ) ) {

		// Let them know they had a failure.
		wp_die( esc_html__( 'There was an error validating the nonce.', 'quick-purge-debug' ), esc_html__( 'Quick Purge Debug Tool', 'quick-purge-debug' ), [ 'back_link' => true ] );
	}

	// Get our logfile.
	$fetch_logfile_src  = Helpers\get_logfile_src();

	// First bail if no file name came back.
	if ( empty( $fetch_logfile_src ) ) {
		run_redirect_after_purge( 'error', 'undefined-log-file' );
	}

	// Now bail if the file doesn't exist.
	if ( ! file_exists( $fetch_logfile_src ) ) {
		run_redirect_after_purge( 'error', 'missing-log-file' );
	}

	// Erase the debug file.
	$maybe_purge_file   = file_put_contents( $fetch_logfile_src, '' );

	// And run our redirect if it worked.
	if ( false !== $maybe_purge_file ) {
		run_redirect_after_purge( 'success', null );
	}

	// Redirect the error.
	run_redirect_after_purge( 'error', 'purge-file-error' );
}

/**
 * Handle running the redirect action.
 *
 * @param  string $purge_result  What the result was.
 * @param  string $error_code    The error code, if we had one.
 *
 * @return void
 */
function run_redirect_after_purge( $purge_result = '', $error_code = '' ) {

	// Make sure we have one of two possible.
	$set_purge_result   = ! empty( $purge_result ) && 'success' === sanitize_text_field( $purge_result ) ? 'success' : 'error';

	// Set up the args for the redirect.
	$set_redirect_args  = [
		'qpd-purge-complete' => 'yes',
		'qpd-purge-result'   => $set_purge_result,
		'qpd-purge-error'    => $error_code,
	];

	// And redirect with a query string.
	$set_redirect_url   = add_query_arg( $set_redirect_args, admin_url( '/' ) );

	// Then redirect.
	wp_safe_redirect( $set_redirect_url );
	exit;
}
