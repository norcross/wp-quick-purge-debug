<?php
/**
 * Our helper functions to use across the plugin.
 *
 * @package QuickPurgeDebug
 */

// Declare our namespace.
namespace Norcross\QuickPurgeDebug\Helpers;

// Set our aliases.
use Norcross\QuickPurgeDebug as Core;

/**
 * Get the source for the logfile to debug.
 *
 * @return string
 */
function get_logfile_src() {

	// Pull the default, which is a defined constant and return it, filtered.
	return apply_filters( Core\HOOK_PREFIX . 'logfile_src', Core\DEBUG_LOGFILE );
}

/**
 * Determine if the file exists, and possibly it's size.
 *
 * @param  string $return_type  How to return the data.
 *
 * @return mixed                A boolean if exists, integer if size.
 */
function get_logfile_size( $return_type = 'bytes' ) {

	// Get our file.
	$fetch_logfile  = get_logfile_src();

	// If no file, return the zero regardless of return type.
	if ( empty( $fetch_logfile ) ) {
		return 0;
	}

	// Check for some bytes.
	$maybe_has_size = filesize( $fetch_logfile );

	// If it's empty, return the zero regardless of return type.
	if ( empty( $maybe_has_size ) ) {
		return 0;
	}

	// Return the boolean or bytes.
	return 'boolean' === sanitize_text_field( $return_type ) ? true : $maybe_has_size;
}

/**
 * Create the link to run the purge.
 *
 * @return string
 */
function get_logfile_purge_link() {

	// Create the purge link, and decide if a target blank.
	$set_purge_args = [
		'qpd-purge-run'   => 'yes',
		'qpd-purge-nonce' => wp_create_nonce( Core\NONCE_PREFIX . '_run' ),
	];

	// Now create the link and return it.
	return add_query_arg( $set_purge_args, admin_url( '/' ) );
}

/**
 * Get the two titles, filtered.
 *
 * @return array
 */
function get_admin_bar_titles() {

	// Get the debug file size.
	$debug_filesize = get_logfile_size();

	// Determine the hover title.
	$adminbar_hover = ! empty( $debug_filesize ) ? sprintf( __( 'Purge Debug File (%d bytes)', 'quick-purge-debug' ), absint( $debug_filesize ) ) : __( 'Purge Debug File (empty)', 'quick-purge-debug' );

	// Now set the array.
	$set_admin_ttls = [
		'title' => '<span class="ab-icon dashicons-trash"></span>',
		'hover' => $adminbar_hover,
	];

	// Return the titles, filtered.
	return apply_filters( Core\HOOK_PREFIX . 'admin_bar_titles', $set_admin_ttls );
}

/**
 * Check an code and (usually an error) return the appropriate text.
 *
 * @param  string $return_code  The code provided.
 *
 * @return string
 */
function get_admin_notice_text( $return_code = '' ) {

	// Allow a possible custom message first.
	$maybe_custom   = apply_filters( Core\HOOK_PREFIX . 'custom_message_text', '', $return_code );

	// If someone passed a custom, do that.
	if ( ! empty( $maybe_custom ) ) {
		return $maybe_custom;
	}

	// Handle my different message codes.
	switch ( esc_attr( $return_code ) ) {

		case 'purge-success' :
			return __( 'Success! The debug log file was successfully purged.', 'quick-purge-debug' );
			break;

		case 'undefined-log-file' :
			return __( 'Error! There was no log file defined to purge.', 'quick-purge-debug' );
			break;

		case 'missing-log-file' :
			return __( 'Error! The defined log file does not exist.', 'quick-purge-debug' );
			break;

		case 'purge-file-error' :
			return __( 'The system was unable to purge the debug file.', 'quick-purge-debug' );
			break;

		case 'unknown' :
		case 'unknown-error' :
			return __( 'There was an unknown error with your request.', 'quick-purge-debug' );
			break;

		default :
			return __( 'There was an error with your request.', 'quick-purge-debug' );
			break;

		// End all case breaks.
	}
}
