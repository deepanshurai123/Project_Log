<?php 
	
class Settings {
	
	public $logger;
		
	public function __construct( $logger ) {
		$this->logger = $logger;
	}

	public function activate_setting_hooks() {
		add_action( 'admin_init', array( $this, 'admin_mode' ) );
	}
	
	public static function init( $logger ) {
    $settings = new Settings( $logger );
    $settings->activate_setting_hooks();
  }

	public function check_for_change( $tag, $post_array ) {	
		$old = get_option( $tag );
    $new = isset( $post_array[ $tag ] ) ? $post_array[ $tag ] : '';
		if( $old != $new  ) {
			$this->logger->log( array( 
				'From' => $old , 
				'To'   => $new 
			), $tag );
		}
	}

	public function check_for_trim( $tag, $post_array ) {	 		   				
		$old = get_option( $tag );
		$new = trim( $post_array[ $tag ] );
		if ( $old != $new ) {
			$this->logger->log(array( 
				'From' => $old , 
				'To'   => $new 
			), $tag );	
		}
	}	

	public function admin_mode() {
		$post_array   = filter_input_array( INPUT_POST );
		$option_page_array =array ( 'siteurl', 'home' );
		$settings_trim =array ( 'default_role', 'permalink_structure', 'admin_email' );
		foreach( $option_page_array as $option ) {
			if( !empty( $post_array[ $option ] )) {
				$this->check_for_change( $option, $post_array );	
			}	
		}
		foreach( $settings_trim as $option ) {
			if( !empty( $post_array[ $option ] ) ) {
				 $this->check_for_trim( $option, $post_array );
			}
		}	
	}

}