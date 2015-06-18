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

if( $transaction->attribute( 'status' ) !== nxcSaferPayTransaction::STATUS_USER_CANCELED ) {
	$transaction->setStatus( nxcSaferPayTransaction::STATUS_USER_CANCELED );
	$transaction->store();
}

include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( 'transaction', $transaction );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:saferpay/back.tpl' );
$Result['path']    = array(
	array(
		'text' => ezi18n( 'extension/saferpay', 'SaferPay' ),
		'url'  => false
	)
);
?>