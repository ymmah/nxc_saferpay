<?php
/**
 * @package nxcSaferPay
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    21 Apr 2010
 **/

$transaction = new nxcSaferPayTransaction(
	array(
		'amount'                => 1000,
		'order_id'              => 1,
		'order_description'     => 'Order description 15',
		'extra_data'            => serialize( array( 1, 2, 5 ) )
	)
);
$transaction->store();

eZHTTPTool::redirect( $transaction->attribute( 'pay_url' ) );
?>