<?php

class Data_Utill {

	public function get_data( $details , $data ) {
		$information = array();
		foreach( $details as $detail ) { 
			$value = is_array( $data->$detail ) ? implode( ' ', $data->$detail ) : $data->$detail;
			 if($detail == "post_author") {
				 $value = get_userdata( $value )->user_login;
			 }
			$information += [ $detail => $value ];
		}
		return $information;
	}

	public function property_to_event( $properties ) {
		global $event_map;
    foreach( $properties as $property ) {
			$str = str_replace("_", " ", $property);
      $str = ucwords($str)." Modified ";
			$event_map += [ $property => $str ];	
		}
  }

	public function check_modified_values($old_details, $new_details, $details_to_check, $event_type, $victim) {
		$modified_detail = array();
		foreach( $details_to_check as $detail ) {			
			$old_value = is_array( $old_details->$detail ) ? implode( ' ', $old_details->$detail ) : $old_details->$detail;			
			$new_value = is_array( $new_details->$detail ) ? implode( ' ', $new_details->$detail ) : $new_details->$detail;	
			if( $old_value != $new_value ) {
				if( $detail == "pass" or  $detail == "post_content" ) { 
					$modified_detail += [ $detail => array( "Target" . $event_type => $victim ) ];
				}
				else if( $detail == "post_title" ) {
					$modified_detail += [ $detail => array(
						"Target"."post_id" => $new_details->ID,
						"From"             => $old_value,
						"To"               => $new_value ) ];
				}
				else {
					$modified_detail += [ $detail => array(
						"Target".$event_type => $victim,
						"From"               => $old_value,
						"To"                 => $new_value ) ];				
				}
			}
		}	
		return $modified_detail;
	}

}
	