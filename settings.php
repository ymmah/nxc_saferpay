<?php
/**
 * @package nxcSaferPay
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    21 Apr 2010
 **/

class nxc_saferpaySettings extends nxcExtensionSettings {

	public $defaultOrder = 20;
	public $dependencies = array( 'nxc_mootools' );

	public function activate() {
		//$this->executeSQL( 'install.sql' );
	}

	public function deactivate() {
		//$this->executeSQL( 'uninstall.sql' );
	}
}
?>