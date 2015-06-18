<?php
/**
 * @package nxcSaferPay
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    22 Apr 2010
 **/

$module = $Params['Module'];

$transaction = nxcSaferPayTransaction::fetch( $Params['transactionID'] );
if( !( $transaction instanceof nxcSaferPayTransaction ) ) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( 'transaction', $transaction );

$Result = array();
$Result['content']   = $tpl->fetch( 'design:saferpay/transaction/details.tpl' );
$Result['left_menu'] = 'design:parts/saferpay/menu.tpl';
$Result['path']      = array(
	array(
		'text' => ezi18n( 'extension/saferpay', 'SaferPay Transactions' ),
		'url'  => 'saferpay/transactions'
	),
	array(
		'text' => ezi18n( 'extension/saferpay', 'Details' ),
		'url'  => false
	)
);
?>