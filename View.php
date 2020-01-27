<?php

require_once('Table_Data.php');

class View extends Table_Data {
	public $logger;

	function __construct(  $logger ) {
		$this->logger = $logger;
	}

	public function add_view_hooks() {
		add_action( 'admin_menu', array( $this, 'admin_menu_setup' ) );
	}

	public function admin_menu_setup() {
    add_menu_page( 'Project Log', 'Log Plugin', 'manage_options', 'Project-Log', array( $this, 'customize' ) );
	}

	public function customize() {
		require_once('Table_setup.php');
	}
}