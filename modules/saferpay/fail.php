<?php
/**
 * @package nxcSaferPay
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    27 Apr 2010
 **/

$module = $Params['Module'];

$transaction = nxcSaferPayTransaction::fetch( $Params['transactionID'] );
if( !( $transaction instanceof nxcSaferPayTransaction ) ) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}
$transaction->debug( 'Redirected from payment gateway: "Transaction fail"' );
if( $transaction->attribute( 'status' ) !== nxcSaferPayTransaction::STATUS_AUTHORIZATION_FAILED ) {
	$transaction->setStatus( nxcSaferPayTransaction::STATUS_AUTHORIZATION_FAILED );
	$transaction->store();
}

include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( 'transaction', $transaction );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:saferpay/fail.tpl' );
$Result['path']    = array(
	array(
		'text' => ezi18n( 'extension/saferpay', 'SaferPay' ),
		'url'  => false
	)
);
?>
