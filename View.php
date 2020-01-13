<?php


class View {
	
	public $details;

	function __construct(){
		$this->add_view_hooks();
	}

	public function add_view_hooks(){
		add_action('admin_menu', array($this,'admin_menu_setup'));
	}

	public function admin_menu_setup() {
	
    add_menu_page( 'Project Log', 'Log Plugin', 'manage_options', 'Project-Log', 'customize' );
	}

	public function customize() {
	}

	

	

	
}
