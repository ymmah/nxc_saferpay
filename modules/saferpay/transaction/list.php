<?php
/**
 * @package nxcSaferPay
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    22 Apr 2010
 **/

$transactions = eZPersistentObject::fetchObjectList(
	nxcSaferPayTransaction::definition()
);

include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( 'transactions', $transactions );

$Result = array();
$Result['content']   = $tpl->fetch( 'design:saferpay/transaction/list.tpl' );
$Result['left_menu'] = 'design:parts/saferpay/menu.tpl';
$Result['path']      = array(
	array(
		'text' => ezi18n( 'extension/saferpay', 'SaferPay Transactions' ),
		'url'  => false
	)
);
?>