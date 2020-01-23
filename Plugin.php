<?php

class Plugins {

	public $inserter;
	public $old_plugin=array();	
  public $old_active_plugin=array();	
	
	public function __construct($inserter) {
 		$this->inserter=$inserter;
		$this->activate_plugin_hooks();
	}
		
	public function activate_plugin_hooks() {	
		add_action( 'admin_init', array( $this, 'admin_mode' ) );
		add_action( 'shutdown', array( $this, 'check_for_plugin' ) );
	}

	public function admin_mode() {		
		$this->old_plugin = get_plugins();
		$this->old_active_plugin = get_option('active_plugins');

	}

	public function db_helper($plug_add ,$tag) {
		$plug_details = get_plugins();
		$plugin       = $plug_details[$plug_add];
		$this->inserter->created(array( 'Name' => $plugin['Name'] ) , $tag, NULL );
	}
	public function check_for_plugin() {
		$post_array  = filter_input_array( INPUT_POST );
		$get_array   = filter_input_array( INPUT_GET );

		$script_name = isset( $_SERVER['SCRIPT_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) : false;
		  $action = '';
    if ( isset( $get_array['action'] ) && '-1' != $get_array['action'] ) {
      $action = $get_array['action'];

    } elseif ( isset( $post_array['action'] ) && '-1' != $post_array['action'] ) {
      $action = $post_array['action'];
		}

		if ( ! empty( $script_name ) ) {
			$actype = basename( $script_name, '.php' );
		}
		$is_plugins = 'plugins' === $actype;

		if(in_array( $action, array( 'install-plugin', 'upload-plugin' ) ) ) {
			$plugin       = array_values( array_diff( array_keys( get_plugins() ), array_keys( $this->old_plugin ) ) );
			$this->db_helper($plugin[0]  , "Plugin Installed");
		}

		if ( $is_plugins && in_array( $action, array( 'activate', 'activate-selected' ) ) ) {
			$plugin  = array_values( array_diff(  get_option('active_plugins') ,  $this->old_active_plugin  ) );
			$this->db_helper($plugin[0]  , "Plugin Activated");
		}

		if ( $is_plugins && in_array( $action, array( 'deactivate', 'deactivate-selected' ) ) ) {
			$plugin  = array_values( array_diff( $this->old_active_plugin, $get_option('active_plugins') ) );
			$this->db_helper($plugin[0]  , "Plugin Deactivated");
		}

		if ( in_array( $action, array( 'delete-plugin' ) ) ) {	
		  	$plugin_file = WP_PLUGIN_DIR . '/' . $post_array['plugin'];
        $plugin_name = basename( $plugin_file, '.php' );
        $plugin_name = str_replace( array( '_', '-', '  ' ), ' ', $plugin_name );
				$plugin_name = ucwords( $plugin_name );
				$this->inserter->created(array( 'Name' => $plugin_name ) , "Plugin Deleted", NULL );
		}			
	}
}
	 
