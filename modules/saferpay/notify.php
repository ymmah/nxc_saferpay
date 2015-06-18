<?php
/**
 * @package nxcSaferPay
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    27 Apr 2010
 **/

$transaction = nxcSaferPayTransaction::fetch( $Params['transactionID'] );
if( $transaction instanceof nxcSaferPayTransaction ) {
	if( in_array( $transaction->attribute( 'status' ), array( nxcSaferPayTransaction::STATUS_REDIRECTED_TO_PAYMENT_URL ) ) === false ) {
		$transaction->debug( 'Notification is skipped. Reason: transaction has wrong status' );
	} else {
		if(
			$transaction->VerifyPayConfirm( $_POST['DATA'], $_POST['SIGNATURE'] ) === true &&
			$transaction->PayComplete() === true
		) {
			$paymentObject = eZPaymentObject::fetchByOrderID( $transaction->attribute( 'order_id' ) );
			if( $paymentObject instanceof eZPaymentObject ) {
				$paymentObject->approve();
				$paymentObject->store();
				eZPaymentObject::continueWorkflow( $paymentObject->attribute( 'workflowprocess_id' ) );
			}
		}
	}
}

eZExecution::cleanExit();
?>