<?php

class Table_Data {
	
	public $inserter;
	
	function __construct($inserter) {
		$this->inserter=$inserter;
	}


	public function get_entries() {
		
		global $wpdb;
		$values = $wpdb->get_results("SELECT * FROM wp_Actions1 order by P_id desc");
		return $values;
	}

	public function get_display_message($value,$tag_type) {
		
		global $wpdb;
    $values = $wpdb->get_results("SELECT * FROM wp_Data1 where P_id=".$value);
    $make_up=array();
    foreach($values as $valued) {
      $make_up[] =$valued->vVaalue;
    }
    
		$string;
		switch($tag_type) {
      case "User Created":
              $string=$make_up[1]." was created with the role of".$make_up[2];
              break;
      case "Post Created":
              $string="A post Title ".$make_up[1]." was ".$make_up[2]." by ".$make_up[3];
              break;
      case "post_title":
              $string="The Title of the Post id ".$make_up[1]." was changed from ".$make_up[2]." to ".$make_up[3];
              break;
      case "spam":
      case "approved":
      case "unapproved":
      case "Comment UnSpammed":
      case "Comment UnTrashed" :
			case "trash" :
			        $string="The Comment on the Post titled ".$make_up[2]." by ".$make_up[1]." was marked as ".$tag_type;
              break;
      case "post_content":
          $string="The post Titled ".$make_up[1]." Content was Modified ";
          break;
      case "roles":
           $string="The Role of the User ".$make_up[1]." was changed from ".$make_up[2]." to ".$make_up[3];
           break;
      case "user_email":
          $string="The Email of the User ".$make_up[1]." was changed from ".$make_up[2]." to ".$make_up[3];
          break;
      case "display_name":
           $string="The Display Name of the User ".$make_up[1]." was changed from ".$make_up[2]." to ".$make_up[3];
           break;
      case "user_pass":
          $string="The Password of the User ".$make_up[1]." was changed ";
          break;

      case "Commented on a Post":
      case "Replied on a Comment":
          $string=$make_up[1]." ".$tag_type." on a Post Titled ".$make_up[2];
          break;
    }
    return $string;
  }

}

		