<?php
/**
 * @package nxcSaferPay
 * @package nxcSaferPayGatewayFile
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    21 Apr 2010
 **/


class nxcSaferPayGatewayFile extends nxcSaferPayGateway {

	public function __construct( nxcSaferPayTransaction $transaction ) {
		parent::__construct( $transaction );
	}

	public function callMethod( $method, $params ) {
		parent::callMethod( $method, $params );
	}
}
?>