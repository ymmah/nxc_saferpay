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

if( (int) $transaction->attribute( 'status' ) !== nxcSaferPayTransaction::STATUS_AMOUNT_CAPTURED ) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$order = eZOrder::fetch( $transaction->attribute( 'order_id' ) );
if( $order instanceof eZOrder ) {
	return $module->redirectTo( 'shop/orderview/' . $order->attribute( 'id' ) );
}

include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( 'transaction', $transaction );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:saferpay/success.tpl' );
$Result['path']    = array(
	array(
		'text' => ezi18n( 'extension/saferpay', 'SaferPay' ),
		'url'  => false
	)
);
?>