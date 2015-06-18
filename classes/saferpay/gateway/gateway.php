<?php
/**
 * @package nxcSaferPay
 * @package nxcSaferPayGateway
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    21 Apr 2010
 **/

abstract class nxcSaferPayGateway {

	protected $transacton;
	protected $gatewayURL;

	public function __construct( nxcSaferPayTransaction $transaction ) {
		$this->transacton = $transaction;
		$this->gatewayURL = ( $this->transacton->ini->hasVariable( 'Gateway', 'URL' ) )
			? $this->transacton->ini->variable( 'Gateway', 'URL' )
			: 'https://www.saferpay.com/hosting/';
	}

	public function callMethod( $method, $params ) {
		$this->transacton->debug( 'Executing "' . $method . '" request' );
		$this->transacton->debug( 'Request params: ' . eZDebug::dumpVariable( $params, true ) );
	}
}
?>