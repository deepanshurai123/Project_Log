<?php

/* Contains the Class
 * DataBase,which
 * will create the
 * required table
 * and will insert
 * the Values in 
 * the DataBase
 */

class Database {

	/*
	 * This is the Constructor
	 * which will initialize 
	 * our DataBase Object*/

	function __construct()	{
		$this->setup();
	}
		
	/*
	 * This function will
         * setup the Required
         * DataBase,will create
				 * the Required Tables
		*/



	public function setup() {
		
		$this->create_table("Actions1", array("P_id mediumint(9) NOT NULL AUTO_INCREMENT",
                                              	     "Time datetime",
                                                     "Tag varchar(55)",
               	                                     "PRIMARY KEY  (P_id)"));
		
		$this->create_table("Data1",array("iddd mediumint(9) NOT NULL AUTO_INCREMENT",
                                                  "kkaey varchar(59)",
                                                  "vVaalue varchar(55)",
                                                  "P_id mediumint(9)",
                                                  "PRIMARY KEY  (iddd)",
                                                  "FOREIGN KEY  (P_id) REFERENCES wp_Actions1 (P_id)"
                                           ));
	}

	/*
	 * This Function will create 
	 * the Table with the given Table Name 
	 * and Given Table Structure
	 * */


	public function create_table($table_n, $table_struct) {
		global $wpdb;
		$table_name = $wpdb->prefix .$table_n;
		$charset_collate = $wpdb->get_charset_collate();
		$table_structure = implode(" , ",$table_struct);         
		$sql = "CREATE TABLE $table_name ($table_structure)
		$charset_collate;";		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	/* This Function will contain 
	 * an Argument,
	 *  that  will
	 * be the array of Values that will be
	 * inserted to the table*/

	public function insert_tag_meta($array_values, $last_id) {
		global $wpdb;
		foreach($array_values as $key => $value) {
			$wpdb->insert('wp_Data1',array( 'iddd' => '' , 
																    	'kkaey' => $key , 
																			'vVaalue' => $value, 
																			'P_id' => $last_id
																		)
									);																	
		}
	}

	public function insert_tag($tag_type) {	
		global $wpdb;
		$wpdb->insert('wp_Actions1',array('P_id' => '','Time' => date('Y-m-d H:i:s'), 'Tag' => $tag_type ));
		$lastid = $wpdb->insert_id;
		return $lastid;
	}
}