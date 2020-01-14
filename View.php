<?php
class View {
	
	public $details;
	public $Tabledata;

	function __construct($Tabledata){
		$this->Tabledata = $Tabledata;
	}

	public function add_view_hooks(){
		add_action('admin_menu', array($this,'admin_menu_setup'));
	}

	public function admin_menu_setup() {
	
    add_menu_page( 'Project Log', 'Log Plugin', 'manage_options', 'Project-Log', array($this,'customize' ));
	}

	public function customize() {
		require_once('Table_setup.php');
	}
}