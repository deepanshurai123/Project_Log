<?php

class Database {

	function __construct()	{
		$this->setup();
	}
		
	public function setup() {	
		
		$this->create_table( "Actions", array( 
			"P_id mediumint(9) NOT NULL AUTO_INCREMENT",
			"Time datetime",
			"Tag varchar(55)",
			"PRIMARY KEY  (P_id)" ) );
		
		$this->create_table( "Data", array( 
			"id mediumint(9) NOT NULL AUTO_INCREMENT",
			"Keey varchar(59)",
			"Vaalue varchar(55)",
			"P_id mediumint(9)",
			"PRIMARY KEY  (id)",
			"FOREIGN KEY  (P_id) REFERENCES wp_Actions (P_id)" ) );
	}

	public function create_table( $table_n, $table_struct ) {
		global $wpdb;
		$table_name = $wpdb->prefix.$table_n;
		$charset_collate = $wpdb->get_charset_collate();
		$table_structure = implode( " , ", $table_struct);         
		$sql = "CREATE TABLE IF NOT EXISTS $table_name ( $table_structure ) $charset_collate; ";		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public function insert_event_meta( $event_details, $last_id ) {
		global $wpdb;
		foreach( $event_details as $key => $value ) {
			$wpdb->insert( 'wp_Data', array(  
				'id'     =>  '', 
				'Keey'   =>  $key, 
				'Vaalue' =>  $value, 
				'P_id'   =>  $last_id ) );																	
		}
	}

	public function insert_event( $event_type ) {	
		global $wpdb;
		$wpdb->insert( 'wp_Actions', array( 
			'P_id' =>  '',
			'Time' =>  date('Y-m-d H:i:s'), 
			'Tag'  =>  $event_type ) );
		$lastid = $wpdb->insert_id;
		return $lastid;
	}

}