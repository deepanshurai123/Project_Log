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

	function __construct( $inserter) {
		$this->inserter=$inserter;
		$this->activate_user_hooks();
		$this->set_up_tags();
	}

	/*
	 * This Function will activate 
	 * all the Hooks Required For
	 * the User 
	 */

	public function activate_user_hooks() {

		/*1.User Created -*/ add_action('user_register', array($this, 'user_created'));
		/*2.User Meta Details -*/  add_action('profile_update', array($this, 'user_modified'), 10, 2);
															add_action('delete_user',array($this , 'user_deleted'));
	}

	/*
	 * This Function will
	 * manage the New 
	 * Users that
	 * are being Created
	 */

	public function user_deleted($user_id) {
		$user_data= get_userdata($user_id);
		$this->inserter->created(array("user_login"
                                  ),
                            "User Deleted",
                            $user_data
                            );
	}		

	public function user_created($user_id) {
		$user_data= get_userdata($user_id);
		$this->inserter->created(array("user_login",
																	 "roles"
																 ),
														"User Created",
														$user_data
														);
	}

	public function user_modified_array() {
		$keys= array("user_pass","user_email","user_firstname","user_lastname","display_name","roles");
		return $keys;
	}

	public function set_up_tags() {
		$key = $this->user_modified_array();
		$this->inserter->setup_tags($key);
	}

	public function user_modified($user_id, $user_old_data) {
		$key = $this->user_modified_array();
		$this->inserter->modification_check($user_old_data, get_userdata($user_id), $key, "User", $user_old_data->user_login);
	}
}