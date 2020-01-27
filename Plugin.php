<?php

class Plugins {

	public $logger;
	public $installed_plugins = array();	
	public $old_active_plugin = array();	
	
	public function __construct( $logger ) {
 		$this->logger = $logger;
	}

	public static function init( $logger ) { 
		$plugin = new Plugins( $logger );      
		$plugin->activate_plugin_hooks();
  }
		
	public function activate_plugin_hooks() {	
		add_action( 'admin_init', array( $this, 'admin_mode' ) );
		add_action( 'shutdown', array( $this, 'check_for_plugin' ) );
	}

	public function admin_mode() {		
		$this->installed_plugins = get_plugins();
		$this->old_active_plugin = get_option( 'active_plugins' );
	} 

	public function plugin_logger( $plug_add , $tag ) {
		$plug_details = get_plugins();
		$plugin       = $plug_details[ $plug_add ];
		$this->logger->log( array( 'Name' => $plugin[ 'Name' ] ) , $tag );
	}

	public function check_for_plugin() {
		$post_array  = filter_input_array( INPUT_POST );
		$get_array   = filter_input_array( INPUT_GET );
		$action = '';
    if ( isset( $get_array[ 'action' ] ) && '-1' != $get_array[ 'action' ] ) {
      $action = $get_array[ 'action' ];		 
		} 
		elseif ( isset( $post_array[ 'action' ] ) && '-1' != $post_array[ 'action' ] ) {
      $action = $post_array[ 'action' ];
		}
		if( in_array( $action, array( 'install-plugin', 'upload-plugin' ) ) ) {
			$plugin = array_values( array_diff( array_keys( get_plugins() ), array_keys( $this->installed_plugins ) ) );
			$this->plugin_logger( $plugin[0], "Plugin Installed" );
		}
		if (  in_array( $action, array( 'activate', 'activate-selected' ) ) ) {
			$plugin  = array_values( array_diff(  get_option( 'active_plugins' ),  $this->old_active_plugin ) );
			$this->plugin_logger( $plugin[0], "Plugin Activated" );
		}

		if (  in_array( $action, array( 'deactivate', 'deactivate-selected' ) ) ) {
			$plugin  = array_values( array_diff( $this->old_active_plugin, get_option( 'active_plugins' ) ) );
			$this->plugin_logger( $plugin[0], "Plugin Deactivated" );
		}

		if ( in_array( $action, array( 'delete-plugin' ) ) ) {	
		  	$plugin_file = WP_PLUGIN_DIR.'/'.$post_array[ 'plugin' ];
        $plugin_name = basename( $plugin_file, '.php' );
        $plugin_name = str_replace( array( '_', '-', '  ' ), ' ', $plugin_name );
				$plugin_name = ucwords( $plugin_name );
	      $this->logger->log( array( 'Name' => $plugin_name ) , "Plugin Deleted", NULL );
		}			
	}
}
	 