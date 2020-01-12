<?php


class Inserter {


	 public $db;


	 public function __construct(Database $db) {

                $this->db=$db;

        }

	 public function created($details_array,$tag_type,$decider_array)
	{
		$details_array = is_array($decider_array)? $this->make_array($details_array,$decider_array) :$details_array;
		$last_user_id = $this->set_initials($tag_type);
		
		foreach($details_array as $key=>$value) {
			$this->db->insert_tag_meta(array($key=>$value),$last_user_id);

		}
	}

	 public function make_array($details_array,$decider_array) {

		 $function = "get_".$decider_array[0];
		 $user_info = call_user_func($function,$decider_array[1]);
		 $setup_array=array();
		 foreach($details_array as $detail) {
      $value=is_array($user_info->$detail)?implode(' ',$user_info->$detail):$user_info->$detail;
      $setup_array += [$detail=>$value];
		 
		 }
		 return $setup_array;
	 }

	 public function set_initials($tag_type) {

		 $last_user_id = $this->db->insert_tag($tag_type);
		 

		$Changer_UserName = wp_get_current_user()->user_login;
		$this->db->insert_tag_meta(array("User_Changer"=>$Changer_UserName),$last_user_id);
		return $last_user_id;
	}

	public function modification_check($old_details,$new_details,$array_to_check,$tag_type,$victim) {

		foreach($array_to_check as $checker)
		{
			$old_value=is_array($old_details->$checker)?implode(' ',$old_details->$checker):$old_details->$checker;
			$new_value=is_array($new_details->$checker)?implode(' ',$new_details->$checker):$new_details->$checker;


			if($old_value!=$new_value)
			{

				$last_user_id = $this->set_initials("Modified_".$tag_type."_".$checker);
				if($check!="post_title") {
					
					$this->db->insert_tag_meta(array("Target".$tag_type=>$victim),$last_user_id);
				}

				
				 if($check=="pass" or  $check=="post_content")
					 continue;
				 $this->db->insert_tag_meta(array("From"=>$old_value,"To"=>$new_value),$last_user_id);
			}

		}

	}

}