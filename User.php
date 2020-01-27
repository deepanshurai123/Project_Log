<?php

require_once('Data_Utill.php');

class User extends Data_Utill  
{
	
	public $db;
	public $logger;
	public $user_properties = array( "user_pass", "user_email", "user_firstname", "user_lastname", "display_name", "roles", "password" );

	function __construct( $logger ) {
		$this->logger = $logger;
		$this->property_to_event( $this->user_properties );
	}
	
	public static function init( $logger ) {
		$user = new User( $logger );
		$user->activate_user_hooks();
	}
	
	public function activate_user_hooks() {
	 	add_action( 'user_register', array( $this, 'user_created' ) );
		add_action( 'profile_update', array( $this, 'user_modified' ), 10, 2 );   
		add_action( 'delete_user', array( $this , 'user_deleted' ) );
	}

	public function user_deleted($user_id) {
		$user_data= get_userdata($user_id);
		$this->logger->log( array( "user_login" => $user_data->user_login ), "User Deleted" );
	}		

	public function user_created( $user_id ) {
		$user_data = get_userdata( $user_id );
		$new_user_details = $this->get_data( array( "user_login", "roles" ), $user_data );
		$this->logger->log( $new_user_details, "User Created" );
	}

	public function user_modified( $user_id, $user_old_data ) {
		$new_user_data = get_userdata( $user_id ); 
		$modified_details = $this->check_modified_values( $user_old_data, $new_user_data, $this->user_properties, "User", $user_old_data->user_login );
		foreach($modified_details as $modified_tag => $modified_detail ) {
			 $this->logger->log( $modified_detail, $modified_tag);
		}
	}
}