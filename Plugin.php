<?php

class Plugins {

	public $inserter;
	public $old_plugin=array();	
	public $old_active_plugin=array();	
//	public $old_theme=array();
	
	public function __construct($inserter) {
 		$this->inserter=$inserter;
		$this->activate_plugin_hooks();
	}
		
	public function activate_plugin_hooks() {	
		add_action( 'admin_init', array( $this, 'admin_mode' ) );
		add_action( 'shutdown', array( $this, 'check_for_plugin' ) );
	}

	public function admin_mode() {		
		$this->old_plugin        = get_plugins();
		$this->old_active_plugin = get_option('active_plugins');
		$this->old_theme         = wp_get_themes();

	} 

	public function db_helper($plug_add ,$tag) {
		$plug_details = get_plugins();
		$plugin       = $plug_details[$plug_add];
		$this->inserter->created(array( 'Name' => $plugin['Name'] ) , $tag, NULL );
	}

	public function check_for_plugin() {

		$post_array  = filter_input_array( INPUT_POST );
		$get_array   = filter_input_array( INPUT_GET );
		$action = '';
    if ( isset( $get_array['action'] ) && '-1' != $get_array['action'] ) {
      $action = $get_array['action'];		 
		} 
		elseif ( isset( $post_array['action'] ) && '-1' != $post_array['action'] ) {
      $action = $post_array['action'];
		}
		
		if(in_array( $action, array( 'install-plugin', 'upload-plugin' ) ) ) {
			$plugin       = array_values( array_diff( array_keys( get_plugins() ), array_keys( $this->old_plugin ) ) );
			$this->db_helper($plugin[0]  , "Plugin Installed");
		}

		if (  in_array( $action, array( 'activate', 'activate-selected' ) ) ) {
			$plugin  = array_values( array_diff(  get_option('active_plugins') ,  $this->old_active_plugin  ) );
			$this->db_helper($plugin[0]  , "Plugin Activated");
		}

		if (  in_array( $action, array( 'deactivate', 'deactivate-selected' ) ) ) {
			$plugin  = array_values( array_diff( $this->old_active_plugin, get_option('active_plugins') ) );
			$this->db_helper($plugin[0]  , "Plugin Deactivated");
		}

		if ( in_array( $action, array( 'delete-plugin' ) ) ) {	
		  	$plugin_file = WP_PLUGIN_DIR . '/' . $post_array['plugin'];
        $plugin_name = basename( $plugin_file, '.php' );
        $plugin_name = str_replace( array( '_', '-', '  ' ), ' ', $plugin_name );
				$plugin_name = ucwords( $plugin_name );
				$this->inserter->created(array( 'Name' => $plugin_name ) , "Plugin Deleted", NULL );
		}

		if ( in_array( $action, array( 'install-theme', 'upload-theme' ) ) ) {
				$themes = array_diff( wp_get_themes(), $this->old_theme );
				foreach($themes as $key => $theme) {
					 $this->inserter->created(array( 'Name' => $theme->Name ) , "Theme Installed", NULL );
				}
		}

		if ( in_array( $action, array( 'delete-theme' ) ) ) {
			$themes = array_diff( array_values($this->old_theme),array_values( wp_get_themes()) );
			
			/*foreach($themes as $key => $theme) {
           
        }*/





		}
					
	}
}
	 