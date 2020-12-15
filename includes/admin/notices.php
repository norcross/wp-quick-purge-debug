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

	// Make sure we have the completed flags.
	if ( empty( $_GET['qpd-purge-complete'] ) || empty( $_GET['qpd-purge-result'] ) ) {
		return;
	}

	// Determine the message type.
	$result = ! empty( $_GET['qpd-purge-result'] ) && 'success' === sanitize_text_field( $_GET['qpd-purge-result'] ) ? 'success' : 'error';

	// Handle the success first
	if ( 'error' !== $result ) {

		// Go get my text to display.
		$notice = Helpers\get_admin_notice_text( 'purge-success' );

		// And handle the display.
		display_admin_notice_markup( $notice, $result );

		// And be done.
		return;
	}

	// Figure out my error code.
	$error_code = ! empty( $_GET['qpd-purge-error'] ) ? $_GET['qpd-purge-error'] : 'unknown';

	// Handle my error text retrieval.
	$error_text = Helpers\get_admin_notice_text( $error_code );

	// And handle the display.
	display_admin_notice_markup( $error_text, 'error' );
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
