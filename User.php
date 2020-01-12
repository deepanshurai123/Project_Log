<?php

/*This class contains
 * all the actions Performed
 * against Users
 */

class User {


	/*
	 * This will manage the
	 * database object
	 */
	
	public $db;
	public $inserter;


	function __construct(Database $db,Inserter $inserter) {

		$this->db=$db;
		$this->inserter=$inserter;
		$this->activate_user_hooks();
	}

	/*
	 * This Function will activate 
	 * all the Hooks Required For
	 * the User 
	 */

	public function activate_user_hooks() {

		/*1.User Created -*/ add_action('user_register',array($this,'user_created'));
		/*2.User Meta Details -*/  add_action('profile_update',array($this,'user_modified'),10,2);
	}

	/*
	 * This Function will
	 * manage the New 
	 * Users that
	 * are being Created
	 */

	public function user_created($user_id) {
		$this->inserter->created(array("user_login",
																	"roles"),
														"User_Created",
														array("userdata",
																	$user_id)
														);
	}

	public function user_modified_array()
	{
		$keys= array("user_pass","user_email","user_firstname","user_lastname","display_name","roles");
		return $keys;
	}
	
	public function user_modified($user_id,$user_old_data) {

		$key = $this->user_modified_array();
		$this->inserter->modification_check($user_old_data,get_userdata($user_id),$key,"User",$user_old_data->user_login);


	}



}