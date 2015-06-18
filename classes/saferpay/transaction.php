<?php
/**
 * @package nxcSaferPay
 * @package nxcSaferPayTransaction
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    21 Apr 2010
 **/

class nxcSaferPayTransaction extends eZPersistentObject {

	const STATUS_OBJECT_CREATED             = 1;
	const STATUS_OBJECT_STORED              = 2;
	const STATUS_REDIRECTED_TO_PAYMENT_URL  = 3;
	const STATUS_USER_CANCELED              = 4;
	const STATUS_AUTHORIZATION_FAILED       = 5;
	const STATUS_AUTHORIZATION_SUCCESSED    = 6;
	const STATUS_PAYMENT_CONFIRMED          = 7;
	const STATUS_PAYMENT_CONFIRATION_FAILED = 8;
	const STATUS_AMOUNT_CAPTURED            = 9;
	const STATUS_CAPTURE_FAILED             = 10;

	public $ini;
	public $gateway;

	public static $defaultFields = array(
		'account_id', 'currency', 'request_card_verification_number', 'request_cardholder_name', 'request_card_verification_number', 'request_cardholder_name', 'delivery', 'user_notify_email', 'merchant_notify_email', 'autoclose', 'provider_set', 'language', 'success_url', 'back_url', 'fail_url', 'notify_url'
	);
	public static $possibleFiledValues = array(
		'currency'                         => array( 'EUR', 'USD', 'GBP', 'CHF', 'JPY' ),
		'request_card_verification_number' => array( 'yes', 'no' ),
		'request_cardholder_name'          => array( 'yes', 'no' ),
		'delivery'                         => array( 'no', 'yes' ),
		'language'                         => array( 'en', 'de', 'fr', 'it' )
	);
	public static $boolFields = array(
		'request_card_verification_number', 'request_cardholder_name', 'delivery'
	);

	public function __construct( $row = array() ) {
		$this->eZPersistentObject( $row );

		// Settuping ini file
		if( $this->attribute( 'settings_file' ) === null ) {
			$this->setAttribute( 'settings_file', 'saferpay.ini' );
		}
		$this->ini = eZINI::instance( $this->attribute( 'settings_file' ) );

		// Transofrm bool attributes (in db they are stored like int, but saferpay needs "yes"/"no" values)
		foreach( self::$boolFields as $field ) {
			if( is_numeric( $this->attribute( $field ) ) ) {
				$this->setAttribute( $field, ( (bool) $this->attribute( $field ) ) ? 'yes' : 'no' );
			}
		}

		// Setuping default attributes
		if( $this->attribute( 'id' ) === null ) {
			foreach( self::$defaultFields as $field ) {
				if( $this->attribute( $field ) === null ) {
					$iniField = str_replace( ' ', '', ucwords( str_replace( '_', ' ', $field ) ) );
					if( $this->ini->hasVariable( 'LocalShopSettings', $iniField ) ) {
						$this->setAttribute( $field, $this->ini->variable( 'LocalShopSettings', $iniField ) );
					}
				}
			}

			$user = eZUser::currentUser();
			$this->setAttribute( 'user_notify_email', ( $this->attribute( 'user_notify_email' ) == 'yes' ) ? $user->attribute( 'email' ) : null );

			if( $this->attribute( 'autoclose' ) == 'no' ) {
				$this->setAttribute( 'autoclose', null );
			}

			if( $this->attribute( 'provider_set' ) == 'no' ) {
				$this->setAttribute( 'provider_set', null );
			}

			$defaultURLs = array(
				'success_url' => '/saferpay/success',
				'back_url'    => '/saferpay/back',
				'fail_url'    => '/saferpay/fail',
				'notify_url'  => '/saferpay/notify'
			);
			foreach( $defaultURLs as $field => $url ) {
				if( $this->attribute( $field ) === null ) {
					$this->setAttribute( $field, $url );
				}
			}

			if( $this->attribute( 'user_id' ) === null ) {
				$this->setAttribute( 'user_id', $user->currentUserID() );
			}
			$this->setAttribute( 'user_ip', ip2long( $_SERVER['REMOTE_ADDR'] ) );
			$this->setAttribute( 'status', self::STATUS_OBJECT_CREATED );
			$this->setAttribute( 'created', time() );
		}

		// Setuping gateway
		switch( strtolower( $this->ini->variable( 'LocalShopSettings', 'GatewayType' ) ) ) {
			case 'curl':
				$gatewayClassName = 'nxcSaferPayGatewayCURL';
				break;
			case 'file':
			default:
				$gatewayClassName = 'nxcSaferPayGatewayFile';
				break;
		}
		$this->gateway = new $gatewayClassName( $this );
	}

	public static function definition() {
		return array(
			'fields'              => array(
				/**
				 * Meta fields
				 **/
				'id' => array(
					'name'     => 'id',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'status' => array(
					'name'     => 'status',
					'datatype' => 'integer',
					'default'  => self::STATUS_OBJECT_CREATED,
					'required' => true
				),
				'user_id' => array(
					'name'     => 'userID',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'user_ip' => array(
					'name'     => 'userIP',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'created' => array(
					'name'     => 'created',
					'datatype' => 'integer',
					'default'  => time(),
					'required' => true
				),
				'changed' => array(
					'name'     => 'changed',
					'datatype' => 'integer',
					'default'  => time(),
					'required' => true
				),
				'settings_file' => array(
					'name'     => 'settingsFile',
					'datatype' => 'string',
					'default'  => 'saferpay.ini',
					'required' => true
				),
				'extra_data' => array(
					'name'     => 'extraData',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'payment_url' => array(
					'name'     => 'paymentURL',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),

				/**
				 * SaferPay payment link fields
				 **/
				'account_id' => array(
					'name'     => 'accountID',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),
				'amount' => array(
					'name'     => 'amount',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'currency' => array(
					'name'     => 'currency',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),
				'order_description' => array(
					'name'     => 'orderDescription',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),
				'request_card_verification_number' => array(
					'name'     => 'requestCardVerificationNumber',
					'datatype' => 'int',
					'default'  => null,
					'required' => false
				),
				'request_cardholder_name' => array(
					'name'     => 'requestCardholderName',
					'datatype' => 'int',
					'default'  => null,
					'required' => false
				),
				'order_id' => array(
					'name'     => 'orderID',
					'datatype' => 'int',
					'default'  => 0,
					'required' => false
				),
				'success_url' => array(
					'name'     => 'successURL',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),
				'back_url' => array(
					'name'     => 'backURL',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),
				'fail_url' => array(
					'name'     => 'failURL',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),
				'notify_url' => array(
					'name'     => 'notifyURL',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),
				'delivery' => array(
					'name'     => 'delivery',
					'datatype' => 'int',
					'default'  => null,
					'required' => true
				),
				'user_notify_email' => array(
					'name'     => 'userNotifyEmail',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'merchant_notify_email' => array(
					'name'     => 'merchantNotifyEmail',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'autoclose' => array(
					'name'     => 'autoclose',
					'datatype' => 'integer',
					'default'  => null,
					'required' => false
				),
				// https://www.saferpay.com/help/ProviderTable.asp
				'provider_set' => array(
					'name'     => 'providerSet',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'language' => array(
					'name'     => 'language',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),

				/**
				 * SaferPay success link fields
				 **/
				'saferpay_transaction_id' => array(
					'name'     => 'saferpayTransactionID',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'token' => array(
					'name'     => 'token',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'provider_id' => array(
					'name'     => 'providerID',
					'datatype' => 'int',
					'default'  => null,
					'required' => false
				),
				'provider_name' => array(
					'name'     => 'providerName',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'authcode' => array(
					'name'     => 'authcode',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'contract_number' => array(
					'name'     => 'contractNumber',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'eci' => array(
					'name'     => 'eci',
					'datatype' => 'int',
					'default'  => null,
					'required' => false
				),
				'cavv' => array(
					'name'     => 'cavv',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'xid' => array(
					'name'     => 'xid',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'ip_country' => array(
					'name'     => 'ipCountry',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				),
				'cc_country' => array(
					'name'     => 'ccCountry',
					'datatype' => 'string',
					'default'  => null,
					'required' => false
				)
			),
			'function_attributes' => array(
				'status_description' => 'getStatusDescription',
				'user'               => 'getUser',
				'user_ip_string'     => 'getUserIPString',
				'order'              => 'getOrder',
				'log_messages'       => 'getLogMessages',
				'pay_url'            => 'CreatePayInit',
			),
			'keys'                => array( 'id' ),
			'sort'                => array( 'id' => 'desc' ),
			'increment_key'       => 'id',
			'class_name'          => 'nxcSaferPayTransaction',
			'name'                => 'nxc_saferpay_transactions'
		);
	}

	public static function fetch( $id ) {
		return eZPersistentObject::fetchObject(
			self::definition(),
			null,
			array( 'id' => $id ),
			true
		);
	}

	public static function fetchByOrderID( $orderID ) {
		return eZPersistentObject::fetchObject(
			self::definition(),
			null,
			array( 'order_id' => $orderID ),
			true
		);
	}

	public function setStatus( $statusID ) {
		$oldStatusDescription = $this->attribute( 'status_description' );
		$this->setAttribute( 'status', $statusID );
		$newStatusDescription = $this->attribute( 'status_description' );

		$this->debug( 'Changing transaction`s status from "' . $oldStatusDescription . '" to "' . $newStatusDescription . '"' );
	}

	public function getStatusDescription() {
		switch( $this->attribute( 'status' ) ) {
			case self::STATUS_OBJECT_CREATED:
				return ezi18n( 'extension/saferpay', 'Object created' );
			case self::STATUS_OBJECT_STORED:
				return ezi18n( 'extension/saferpay', 'Object stored' );
			case self::STATUS_REDIRECTED_TO_PAYMENT_URL:
				return ezi18n( 'extension/saferpay', 'Redirected to payment URL' );
			case self::STATUS_USER_CANCELED:
				return ezi18n( 'extension/saferpay', 'Canceled by user' );
			case self::STATUS_AUTHORIZATION_FAILED:
				return ezi18n( 'extension/saferpay', 'Authorization denied/failed' );
			case self::STATUS_AUTHORIZATION_SUCCESSED:
				return ezi18n( 'extension/saferpay', 'Authorization successed' );
			case self::STATUS_PAYMENT_CONFIRMED:
				return ezi18n( 'extension/saferpay', 'Payment confirmed' );
			case self::STATUS_PAYMENT_CONFIRATION_FAILED:
				return ezi18n( 'extension/saferpay', 'Payment confirmation failed' );
			case self::STATUS_AMOUNT_CAPTURED:
				return ezi18n( 'extension/saferpay', 'Amount captured' );
			case self::STATUS_CAPTURE_FAILED:
				return ezi18n( 'extension/saferpay', 'Capture failed' );
			default:
				return ezi18n( 'extension/saferpay', 'Unknown status' );
		}
	}

	public function getUser() {
		if( eZContentObject::exists( $this->attribute( 'user_id' ) ) ) {
			return eZContentObject::fetch( $this->attribute( 'user_id' ) );
		} else {
			return null;
		}
	}

	public function getUserIPString() {
		return long2ip( $this->attribute( 'user_ip' ) );
	}

	public function getOrder() {
		return eZOrder::fetch( $this->attribute( 'order_id' ) );
	}

	public function getLogMessages() {
		return eZPersistentObject::fetchObjectList(
			nxcSaferPayLogMessage::definition(),
			null,
			array( 'transaction_id' => $this->attribute( 'id' ) ),
			true
		);
	}

	public function store( $fieldFilters = null ) {
		if( $this->attribute( 'status' ) == self::STATUS_OBJECT_CREATED ) {
			$this->setStatus( self::STATUS_OBJECT_STORED );
		}

		$this->setAttribute( 'changed', time() );

		// Checking righte values in the list attributes
		foreach( self::$possibleFiledValues as $field => $values ) {
			if( in_array( $this->attribute( $field ), $values ) === false ) {
				$this->setAttribute( $field, $values[0] );
			}
		}

		// Transofrm bool attributes (in db they are stored like int, but saferpay needs "yes"/"no" values)
		foreach( self::$boolFields as $field ) {
			$this->setAttribute( $field, ( $this->attribute( $field ) == 'yes' ) ? 1 : 0 );
		}

		eZPersistentObject::storeObject( $this, $fieldFilters );

		// Transofrm bool attributes (in db they are stored like int, but saferpay needs "yes"/"no" values)
		foreach( self::$boolFields as $field ) {
			$this->setAttribute( $field, ( (bool) $this->attribute( $field ) === true ) ? 'yes' : 'no' );
		}
	}

	public function debug( $message, $verbosityLevel = eZDebug::LEVEL_DEBUG ) {
		$fileData = array( 'var/log/', 'saferpay.log' );

		$debug = eZDebug::instance();
		$debug->writeFile( $fileData, $message, $verbosityLevel );
		eZDebug::writeDebug( $message, 'SaferPay Transaction' );

		if( $this->attribute( 'id' ) !== null ) {
			$logMessage = new nxcSaferPayLogMessage(
				array(
					'transaction_id' => $this->attribute( 'id' ),
					'message'        => $message
				)
			);
			$logMessage->store();
		}
	}

	public function CreatePayInit() {
		if( $this->attribute( 'payment_url' ) !== null ) {
			return $this->attribute( 'payment_url' );
		}

		$sys = eZSys::instance();

		$attributes = array(
			'ACCOUNTID' => $this->attribute( 'account_id' ),
			'AMOUNT' => $this->attribute( 'amount' ),
			'CURRENCY' => $this->attribute( 'currency' ),
			'DESCRIPTION' => urlencode( $this->attribute( 'order_description' ) ),
			'CCCVC' => $this->attribute( 'request_card_verification_number' ),
			'CCNAME' => $this->attribute( 'request_cardholder_name' ),
			'ORDERID' => $this->attribute( 'order_id' ),
			'SUCCESSLINK' => urlencode( $sys->serverURL() . $sys->wwwDir() . $this->attribute( 'success_url' ) . '/' . $this->attribute( 'id' ) ),
			'BACKLINK' => urlencode( $sys->serverURL() . $sys->wwwDir() . $this->attribute( 'back_url' ) . '/' . $this->attribute( 'id' ) ),
			'FAILLINK' => urlencode( $sys->serverURL() . $sys->wwwDir() . $this->attribute( 'fail_url' ) . '/' . $this->attribute( 'id' ) ),
			'NOTIFYURL' => urlencode( $sys->serverURL() . $sys->wwwDir() . $this->attribute( 'notify_url' ) . '/' . $this->attribute( 'id' ) ),
			'DELIVERY' => $this->attribute( 'delivery' ),
			'USERNOTIFY' => $this->attribute( 'user_notify_email' ),
			'NOTIFYADDRESS' => $this->attribute( 'merchant_notify_email' ),
			'AUTOCLOSE' => $this->attribute( 'autoclose' ),
			'PROVIDERSET' => $this->attribute( 'provider_set' ),
			'LANGID' => $this->attribute( 'language' )
		);
		foreach( $attributes as $key => $value ) {
			if( is_null( $value ) ) {
				unset( $attributes[ $key ] );
			}
		}

		$stylingAttributes = array(
			'ShowLanguages', 'BodyColor', 'HeadColor', 'HeadlineColor', 'MenuColor', 'BodyFontColor', 'HeadFontColor', 'MenuFontColor', 'Font'
		);
		foreach( $stylingAttributes as $attribute ) {
			if( $this->ini->hasVariable( 'VirtualTerminalStyling', $attribute ) ) {
				$value = $this->ini->variable( 'VirtualTerminalStyling', $attribute );
				if( strlen( $value ) > 0 ) {
					$attributes[ strtoupper( $attribute ) ] = $value;
				}
			}
		}

		try{
			$paymentURL = $this->gateway->callMethod( 'CreatePayInit', $attributes );

			if( substr( $paymentURL, 0, 5 ) === 'ERROR' ) {
				$this->debug( 'CreatePayInit failed: ' . $paymentURL );
			} else {
				$this->setAttribute( 'payment_url', $paymentURL );
				$this->setStatus( self::STATUS_REDIRECTED_TO_PAYMENT_URL );
				$this->store();

				return $paymentURL;
			}
		} catch( Exception $e ) {
			$this->debug( 'CreatePayInit failed: ' . $e->getMessage() );
		}

		return false;
	}

	public function VerifyPayConfirm( $data, $signature ) {
		$attributes = array(
			'DATA' => urlencode( $data ),
			'SIGNATURE' => urlencode( $signature )
		);
		//check transaction status if it's not Authorization denied/failed
		/*
		if ( $this->attribute( 'status' ) == self::STATUS_AUTHORIZATION_FAILED ){
			$this->setStatus( self::STATUS_PAYMENT_CONFIRATION_FAILED );
			$this->store();

			return false;
		}
		*/
		try{
			$verification = $this->gateway->callMethod( 'VerifyPayConfirm', $attributes );

			if( strtoupper( substr( $verification, 0, 3 ) ) != 'OK:' ) {
				$this->debug( 'Confirmation failed: ' . $verification );
			} else {
				$dom = new DOMDocument();
				$dom->loadXML( $data );

				if(
					$dom->documentElement->getAttribute( 'ACCOUNTID' ) == $this->attribute( 'account_id' ) &&
					$dom->documentElement->getAttribute( 'AMOUNT' ) == $this->attribute( 'amount' ) &&
					$dom->documentElement->getAttribute( 'CURRENCY' ) == $this->attribute( 'currency' )
				) {
					$this->setAttribute( 'saferpay_transaction_id', $dom->documentElement->getAttribute( 'ID' ) );
					$this->setAttribute( 'token', $dom->documentElement->getAttribute( 'TOKEN' ) );
					$this->setAttribute( 'provider_id', $dom->documentElement->getAttribute( 'PROVIDERID' ) );
					$this->setAttribute( 'provider_name', $dom->documentElement->getAttribute( 'PROVIDERNAME' ) );
					$this->setAttribute( 'authcode', $dom->documentElement->getAttribute( 'AUTHCODE' ) );
					$this->setAttribute( 'contract_number', $dom->documentElement->getAttribute( 'CONTRACTNUMBER' ) );
					$this->setAttribute( 'eci', $dom->documentElement->getAttribute( 'ECI' ) );
					$this->setAttribute( 'cavv', $dom->documentElement->getAttribute( 'CAVV' ) );
					$this->setAttribute( 'xid', $dom->documentElement->getAttribute( 'XID' ) );
					$this->setAttribute( 'ip_country', $dom->documentElement->getAttribute( 'IPCOUNTRY' ) );
					$this->setAttribute( 'cc_country', $dom->documentElement->getAttribute( 'CCCOUNTRY' ) );

					$this->setStatus( self::STATUS_PAYMENT_CONFIRMED );
					$this->store();

					return true;
				}
			}
		} catch( Exception $e ) {
			$this->debug( 'VerifyPayConfirm failed: ' . $e->getMessage() );
		}

		$this->setStatus( self::STATUS_PAYMENT_CONFIRATION_FAILED );
		$this->store();

		return false;
	}

	public function PayComplete() {
		$attributes = array(
			'ACCOUNTID' => $this->attribute( 'account_id' ),
			'ID' => urlencode( $this->attribute( 'saferpay_transaction_id' ) ),
			'TOKEN' => urlencode( $this->attribute( 'token' ) )
		);

		if( substr(	$this->attribute( 'account_id' ), 0, 6 ) == '99867-' ) {
			$attributes['spPassword'] = 'XAjc3Kna';
		}

		try{
			$result = $this->gateway->callMethod( 'PayComplete', $attributes );

			if( $result != 'OK' ) {
				$this->debug( 'Capture failed: ' . $result );
			} else {
				$this->setStatus( self::STATUS_AMOUNT_CAPTURED );
				$this->store();

				return true;
			}
		} catch( Exception $e ) {
			$this->debug( 'Capture failed: ' . $e->getMessage() );
		}

		$this->setStatus( self::STATUS_CAPTURE_FAILED );
		$this->store();

		return false;
	}
}
?>