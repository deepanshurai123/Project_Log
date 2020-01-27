<?php


class Logger {

	public $db;
	
	public function __construct( $db ) {
		$this->db=$db;
	}
	
	public function log( $details_array, $tag_type ) {	
		$last_user_id = $this->log_event( $tag_type );
		foreach( $details_array as $key => $value ) {
			$this->db->insert_event_meta( array (	$key => $value ), $last_user_id );
		}
	}
	
	public function log_event( $tag_type ) {
		$last_user_id = $this->db->insert_event( $tag_type ); 
		$Changer_UserName = wp_get_current_user()->user_login;
		$this->db->insert_event_meta( array( "User_Changer" => $Changer_UserName ), $last_user_id );
		return $last_user_id;
	}
	
}
