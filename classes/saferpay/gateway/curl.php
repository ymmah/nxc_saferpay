<?php
/**
 * @package nxcSaferPay
 * @package nxcSaferPayGatewayCURL
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    21 Apr 2010
 **/


class nxcSaferPayGatewayCURL extends nxcSaferPayGateway {

	public function __construct( nxcSaferPayTransaction $transaction ) {
		parent::__construct( $transaction );
	}

	public function callMethod( $method, $params ) {
		parent::callMethod( $method, $params );

		$paramsStr = array();
		foreach( $params as $key => $value ) {
			$paramsStr[] = $key . '=' . $value;
		}

		$cs = curl_init( $this->gatewayURL . $method . '.asp?' . implode( '&', $paramsStr ) );
		curl_setopt( $cs, CURLOPT_PORT, 443 );
		curl_setopt( $cs, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $cs, CURLOPT_HEADER, 0 );
		curl_setopt( $cs, CURLOPT_RETURNTRANSFER, true );
		$payment_url = curl_exec( $cs );
		$error = curl_error( $cs );
		curl_close( $cs );

		if( strlen( $error ) > 0 ) {
			throw new Exception( $error );
		}

		return $payment_url;
	}
}
?>