<?php


class Inserter {


	public $db;
	public $tag_map=array();
	
	public function __construct(Database $db) {
                $this->db=$db;
	}
	
	public function setup_tags($array) {
		foreach($array as $token) {
			$str = str_replace("_", " ", $token);
			$str = ucwords($str)." Modified ";	
			$this->tag_map += [$token => $str];
		}
	}	
	
	public function created($details_array, $tag_type, $decider) {
		$details_array = !(is_null($decider)) ? $this->make_array($details_array, $decider) : $details_array;
		$last_user_id = $this->set_initials($tag_type);
		foreach($details_array as $key => $value) {
			$this->db->insert_tag_meta(array(
																				$key => $value
																		), 
																$last_user_id
														);
		}
	}
	
	public function make_array($details_array, $user_info) {
		$setup_array=array();
		foreach($details_array as $detail) {
			 $value=is_array($user_info->$detail) ? implode(' ', $user_info->$detail) : $user_info->$detail;
			 if($detail == "post_author")
				 $value = get_userdata($value)->user_login;
       $setup_array += [$detail => $value];
		 }
		 return $setup_array;
	}
	
	public function set_initials($tag_type) {
		$last_user_id = $this->db->insert_tag($tag_type); 
		$Changer_UserName = wp_get_current_user()->user_login;
		$this->db->insert_tag_meta(array(
																			"User_Changer" => $Changer_UserName
																	), 
															$last_user_id
													);
		return $last_user_id;
	}
	
	public function modification_check($old_details, $new_details, $array_to_check, $tag_type, $victim) {
		foreach($array_to_check as $checker) {
			$old_value=is_array($old_details->$checker)?implode(' ', $old_details->$checker) : $old_details->$checker;
			$new_value=is_array($new_details->$checker)?implode(' ', $new_details->$checker) : $new_details->$checker;
			if($old_value!=$new_value) {
				$last_user_id = $this->set_initials($checker);
				if($checker != "post_title") {
					$this->db->insert_tag_meta(array(
																						"Target".$tag_type => $victim
																				), 
																		$last_user_id
																	);
				}
				if($checker == "post_title" ) {
					$this->db->insert_tag_meta(array(
																						"Target"."post_id" => $new_details->ID
																				),
																		$last_user_id
																	);
				}
				if($checker == "pass" or  $checker == "post_content")
					 continue;
				$this->db->insert_tag_meta(array(
																					"From" => $old_value, 
																					"To"   => $new_value
																			), 
																	$last_user_id
																);
															}
														}
	}

}
