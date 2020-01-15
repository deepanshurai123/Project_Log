<?php
class View {
	
	public $Tabledata;
	public $inserter;

	function __construct($Tabledata, $inserter) {
		$this->Tabledata = $Tabledata;
		$this->inserter= $inserter;
	}

	public function add_view_hooks(){
		add_action('admin_menu', array($this, 'admin_menu_setup'));
	}

	public function admin_menu_setup() {
    add_menu_page( 'Project Log', 'Log Plugin', 'manage_options', 'Project-Log', array($this,'customize' ));
	}

	public function customize() {
		require_once('Table_setup.php');
	}
}