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

	public function get_display_message($value,$tag_type,&$admin) {
		global $wpdb;
    $values = $wpdb->get_results("SELECT * FROM wp_Data1 where P_id=".$value);
		$make_up=array();
    foreach($values as $valued) {
      $make_up[] =$valued->vVaalue;
    }
    $admin = $make_up[0];
		$string;
		switch($tag_type) {
      case "User Created":
              $string= "<b>".$make_up[1]."</b>"." was created with the role of"."<b>".$make_up[2]."</b>";
              break;
      case "Post Created":
              $string="A post Title "."<b>".$make_up[1]."</b>"." was "."<b>".$make_up[2]."</b>"." by "."<b>".$make_up[3]."</b>";
              break;
      case "post_title":
              $string="The Title of the Post id ".$make_up[1]." was changed from "."<b>".$make_up[2]."</b>"." to "."<b>".$make_up[3]."</b>";
              break;
      case "Comment spam":
      case "Comment approved":
      case "Comment unapproved":
      case "Comment Unspammed":
      case "Comment Untrashed" :
			case "Comment trash" :
			    $string="The Comment on the Post titled "."<b>".$make_up[2]."</b>"." by ".$make_up[1]." was marked as "."<b>".strstr($tag_type," ")."</b>";
              break;
      case "post_content":
          $string="The post Titled "."<b>".$make_up[1]."</b>"." Content was Modified ";
          break;
      case "roles":
           $string="The Role of the User "."<b>".$make_up[1]."</b>"." was changed from "."<b>".$make_up[2]."</b>"." to "."<b>".$make_up[3]."</b>";
           break;
      case "user_email":
          $string="The Email of the User "."<b>".$make_up[1]."</b>"." was changed from "."<b>".$make_up[2]."</b>"." to "."<b>".$make_up[3]."</b>";
          break;
      case "display_name":
           $string="The Display Name of the User "."<b>".$make_up[1]."</b>"." was changed from "."<b>".$make_up[2]."</b>"." to "."<b>".$make_up[3]."</b>";
           break;
      case "user_pass":
          $string="The Password of the User "."<b>".$make_up[1]."</b>"." was changed ";
          break;

      case "Commented on a Post":
      case "Replied on a Comment":
          $string="<b>".$make_up[1]."</b>"." ".$tag_type." on a Post Titled "."<b>".$make_up[2]."</b>";
					break;
			case "Attachment Added":
				$string= " The File Named "."<b>".$make_up[1]."</b>"." was added to the path "."<b>".$make_up[2]."</b>";
					break;
			case "Attachment Deleted":
				$string= " The File Named "."<b>".$make_up[1]."</b>"." was deleted from the path "."<b>".$make_up[2]."</b>";
				break;
			case "Post Deleted":
        $string= " The Post Titled "."<b>".$make_up[1]."</b>"." by "."<b>".$make_up[2]."</b>"." was Deleted ";
          break;


    }
    return $string;
  }

}

		