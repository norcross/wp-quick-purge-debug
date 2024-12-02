<?php
/**
 * Handle the various admin notices.
 *
 * @package QuickPurgeDebug
 */

// Declare our namespace.
namespace Norcross\QuickPurgeDebug\Admin\Notices;

// Set our aliases.
use Norcross\QuickPurgeDebug as Core;
use Norcross\QuickPurgeDebug\Helpers as Helpers;

/**
 * Start our engines.
 */
add_action( 'admin_notices', __NAMESPACE__ . '\admin_result_notices' );

/**
 * Check for the result of adding or editing an attribute.
 *
 * @return void
 */
function admin_result_notices() {

	// Confirm this is one of our notices when done.
	$confirm_isdone = filter_input( INPUT_GET, 'qpd-purge-complete', FILTER_SANITIZE_SPECIAL_CHARS );

	// Make sure it is what we want.
	if ( empty( $confirm_isdone ) || 'yes' !== $confirm_isdone ) {
		return;
	}

	// Confirm this is one of our results when done.
	$confirm_result = filter_input( INPUT_GET, 'qpd-purge-result', FILTER_SANITIZE_SPECIAL_CHARS );

	// Make sure it is acceptable.
	if ( empty( $confirm_result ) || ! in_array( $confirm_result, ['success', 'error'], true )  ) {
		return;
	}

	// Handle the success first.
	if ( 'success' === $confirm_result ) {

		// Go get my text to display.
		$isdone_message = Helpers\get_admin_notice_text( 'purge-success' );

		// And handle the display.
		display_admin_notice_markup( $isdone_message, $confirm_result );

		// And be done.
		return;
	}

	// Figure out my error code.
	$confirm_error  = filter_input( INPUT_GET, 'qpd-purge-error', FILTER_SANITIZE_SPECIAL_CHARS );

	// Handle my error text retrieval.
	$error_message  = Helpers\get_admin_notice_text( $confirm_error );

	// And handle the display.
	display_admin_notice_markup( $error_message, 'error' );
}

/**
 * Build the markup for an admin notice.
 *
 * @param  string  $notice       The actual message to display.
 * @param  string  $result       Which type of message it is.
 * @param  boolean $dismiss      Whether it should be dismissable.
 * @param  boolean $show_button  Show the dismiss button (for Ajax calls).
 * @param  boolean $echo         Whether to echo out the markup or return it.
 *
 * @return HTML
 */
function display_admin_notice_markup( $notice = '', $result = 'error', $dismiss = true, $show_button = false, $echo = true ) {

	// Bail without the required message text.
	if ( empty( $notice ) ) {
		return;
	}

	// Set my base class.
	$class  = 'notice notice-' . esc_attr( $result ) . ' qk-prgdb-admin-message';

	// Add the dismiss class.
	if ( $dismiss ) {
		$class .= ' is-dismissible';
	}

	// Set an empty.
	$field  = '';

	// Start the notice markup.
	$field .= '<div class="' . esc_attr( $class ) . '">';

		// Display the actual message.
		$field .= '<p><strong>' . wp_kses_post( $notice ) . '</strong></p>';

		// Show the button if we set dismiss and button variables.
		$field .= $dismiss && $show_button ? '<button type="button" class="notice-dismiss">' . screen_reader_text() . '</button>' : '';

	// And close the div.
	$field .= '</div>';

	// Echo it if requested.
	if ( ! empty( $echo ) ) {
		echo $field; // WPCS: XSS ok.
	}

	// Just return it.
	return $field;
}
