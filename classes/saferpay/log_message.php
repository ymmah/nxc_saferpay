<?php
/**
 * @package nxcSaferPay
 * @package nxcSaferPayLogMessage
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    21 Apr 2010
 **/

class nxcSaferPayLogMessage extends eZPersistentObject {

	public function __construct( $row = array() ) {
		$this->eZPersistentObject( $row );

		if( $this->attribute( 'id' ) === null ) {
			$this->setAttribute( 'created', time() );
		}
	}

	public static function definition() {
		return array(
			'fields' => array(
				'id' => array(
					'name'     => 'id',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'transaction_id' => array(
					'name'     => 'transactionID',
					'datatype' => 'string',
					'default'  => '',
					'required' => true
				),
				'created' => array(
					'name'     => 'created',
					'datatype' => 'integer',
					'default'  => time(),
					'required' => true
				),
				'message' => array(
					'name'     => 'message',
					'datatype' => 'string',
					'default'  => '',
					'required' => true
				)
			),
			'keys'                => array( 'id' ),
			'sort'                => array( 'id' => 'asc' ),
			'increment_key'       => 'id',
			'class_name'          => 'nxcSaferPayLogMessage',
			'name'                => 'nxc_saferpay_log_messages'
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
}
?>