<?php
/**
 * @package nxcSaferPay
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    21 Apr 2010
 **/

$Module = array(
	'name'            => 'SaferPay Payment Gateway',
 	'variable_params' => true
);

$ViewList = array();
$ViewList['test'] = array(
	'functions' => array( 'pay' ),
	'script'    => 'test.php'
);
$ViewList['success'] = array(
	'functions' => array( 'pay' ),
	'script'    => 'success.php',
	'params'    => array( 'transactionID' )
);
$ViewList['back'] = array(
	'functions' => array( 'pay' ),
	'script'    => 'back.php',
	'params'    => array( 'transactionID' )
);
$ViewList['fail'] = array(
	'functions' => array( 'pay' ),
	'script'    => 'fail.php',
	'params'    => array( 'transactionID' )
);
$ViewList['notify'] = array(
	'functions' => array( 'pay' ),
	'script'    => 'notify.php',
	'params'    => array( 'transactionID' )
);
$ViewList['transactions'] = array(
	'functions'               => array( 'admin' ),
	'script'                  => 'transaction/list.php',
	'default_navigation_part' => 'nxcsaferpaynavigationpart'
);
$ViewList['details'] = array(
	'functions'               => array( 'admin' ),
	'script'                  => 'transaction/view.php',
	'params'                  => array( 'transactionID' ),
	'default_navigation_part' => 'nxcsaferpaynavigationpart'
);

$FunctionList          = array();
$FunctionList['pay']   = array();
$FunctionList['admin'] = array();
?>