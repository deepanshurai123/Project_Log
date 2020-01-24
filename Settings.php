<?php 
	

class Settings {
	
	public $inserter;
		
	public function __construct($inserter) {
		$this->inserter=$inserter;
		$this->activate_setting_hooks();
	}

	public function activate_setting_hooks() {
		  add_action( 'admin_init', array( $this, 'admin_mode' ) );
	}

	
	public function check_for_change($tag,$post_array) {	
		$old = get_option( $tag);
    $new = isset( $post_array[$tag] ) ? $post_array[$tag] : '';
		if( $old !=$new  ) {
				$this->inserter->created(array( 'Name' => $old, 'val' => $new ) , $tag, NULL );
		}
	}

	public function check_for_trim($tag,$post_array) {	 		   				
		$old = get_option( $tag );
		$new = trim( $post_array[$tag] );
		if ( $old != $new ) {
				$this->inserter->created(array( 'Name' => $old, 'val' => $new ) , $tag, NULL );	
		}
	}	

	public function admin_mode() {
		$post_array   = filter_input_array( INPUT_POST );
    $get_array    = filter_input_array( INPUT_GET );
		$server_array = filter_input_array( INPUT_SERVER );
		
/*		$actype = '';
    if ( ! empty( $server_array['SCRIPT_NAME'] ) ) {
      $actype = basename( $server_array['SCRIPT_NAME'], '.php' );
    }

    $is_option_page      = 'options' === $actype;
    $is_network_settings = 'settings' === $actype;
		$is_permalink_page   = 'options-permalink' === $actype;*/

		$option_page_array =array ('siteurl','home' );
		$settings_trim =array ('default_role','permalink_structure','admin_email');
			
		foreach($option_page_array as $option) {
			if(!empty($post_array[$option] )) {
				$this->check_for_change($option,$post_array);	
			}	
		}

		foreach($settings_trim as $option) {
			if(!empty($post_array[$option])) {
				 $this->check_for_trim($option,$post_array);
			}
		}	
	
	}
}
