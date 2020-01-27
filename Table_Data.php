<?php

class Table_Data  {
	
	public function get_events() {		
		global $wpdb;
		$total_events = $wpdb->get_results( "SELECT * FROM wp_Actions order by P_id desc" );
		return $total_events;
	}

	public function get_display_message( $event_id, $event_type, &$admin ) {
		global $wpdb;
		global $event_map;
		$event_data = $wpdb->get_results( "SELECT * FROM wp_Data where P_id=". $event_id );
		$event_details=array();
		foreach($event_data  as $data ) {
      $event_details[] = $data->Vaalue;
    }
		$admin = $event_details[0];
		$string;
		
		switch( $event_type ) {
		
		case "User Created" :			
			$string = "<b>" . $event_details[1] . "</b>". " was created with the role of" . "<b>" . $event_details[2] . "</b>";
			break;
		case "Post Created" :	
			$string = "A post Title " . "<b>" . $event_details[1] . "</b>" . " was " . "<b>" .$event_details[2]. "</b>" . " by " . "<b>" .$event_details[3]
				. "</b>";
			break;
		case "post_title" :
			$string = "The Title of the Post id " . $event_details[1] . " was changed from " . "<b>" . $event_details[2] . "</b>" . " to " . "<b>" .
				$event_details[3];
			break;
		case "Comment spam" :
		case "Comment approved" :
		case "Comment unapproved" :
		case "Comment Unspammed" :
		case "Comment Untrashed" :
		case "Comment trash" :
		case "Comment delete" :
			$string = "The Comment on the Post titled " . "<b>" . $event_details[2] . "</b>" . " by " . $event_details[1]. " was marked as " . "<b>" . 
				strstr( $event_type, " " ) . "</b>";
			break;
		case "post_content" :
			$string="The post Titled "."<b>".$event_details[1]."</b>"." Content was Modified ";
			break;
		case "roles" :
			$string = "The Role of the User " . "<b>" .$event_details[1]. "</b>" . " was changed from " . "<b>" .$event_details[2]. "</b>" . " to " .
			 	"<b>" . $event_details[3];
			break;
		case "user_email" :
			$string = "The Email of the User " . "<b>" . $event_details[1] . "</b>" . " was changed from " . "<b>" . $event_details[2] . "</b>" . " to ".
				"<b>" . $event_details[3] . "</b>";
			break;
		case "display_name" :
			$string = "The Display Name of the User " . "<b>" . $event_details[1] . "</b>" . " was changed from " . "<b>" . $event_details[2] . "</b>".
				" to " . "<b>" . $event_details[3] . "</b>";
			break;
		case "user_pass" :
			$string = "The Password of the User " . "<b>" . $event_details[1] . "</b>" . " was changed ";
			break;
		case "Commented on a Post" :
		case "Replied on a Comment" :
			$string = "<b>" . $event_details[1] . "</b>" . " " . $event_type . " on a Post Titled " . "<b>" . $event_details[2] . "</b>";
			break;
		case "Attachment Added" :
			$string = " The File Named " . "<b>" . $event_details[1] . "</b>" . " was added to the path " . "<b>" . $event_details[2] . "</b>";
			break;
		case "Attachment Deleted" :
			$string = " The File Named " . "<b>" . $event_details[1] . "</b>" . " was deleted from the path " . "<b>" . $event_details[2] . "</b>";
			break;
		case "Post Deleted" :
			$string = " The Post Titled " . "<b>" . $event_details[1] . "</b>" . " by " . "<b>" . $event_details[2] . "</b>" . " was Deleted ";
			break;
		case "User Deleted" :
			$string = "<b>" . $event_details[1] . "</b>" . " was deleted from the Database ";
			break;
		case "Post Trashed" :
			$string = " The Post Titled " . "<b>" . $event_details[1] . "</b>" . " by " . "<b>" . $event_details[2] . "</b>" . " was Trashed ";
			break;
		case "Post Untrashed" :
			$string = " The Post Titled " . "<b>" . $event_details[1] . "</b>" ." by " . "<b>" . $event_details[2] . "</b>" . " was Untrashed ";
			break;
		case "Category Changed" :
			$string = "The Category of the Post Titled " . "<b>" . $event_details[1] . "</b>" . " was changed from " . "<b>" . $event_details[2] . 
				"</b>" . " to " . "<b>" .$event_details[3];
			break;
		case "Tags Added" : 
			$string = "Added Tags " . "<b>" . $event_details[2] . "</b>" . " to the Post Titled " . "<b>" . $event_details[1];
			break;
		case "Tags Removed" :
			$string = "Deleted Tags " . "<b>" . $event_details[2] . "</b>" . " of the Post Titled " . "<b>" . $event_details[1] . "</b>";
			break;
		case "Post Opened" :
			$string = "Opened The Post Titled " . "<b>" . $event_details[1] . "</b>";
			break;
		case "Post Viewed" :
			$string = "Viewed The Post Titled " . "<b>" . $event_details[1] . "</b>";
			break;
		case "Plugin Installed" :
			$string = "Installed the Plugin " . "<b>" .$event_details[1]. "</b>";
			break;
		case "Plugin Activated" :
			$string = "Activated the Plugin " . "<b>" .$event_details[1]. "</b>";
			break;
		case "Plugin Deactivated" :
			$string = "Deactivated the Plugin " . "<b>" .$event_details[1]. "</b>";
			break;
		case "Plugin Deleted" :
			$string = "Deleted the Plugin " . "<b>" . $event_details[1] . "</b>";
			break;
		case "Menu Created" :
			$string = "Created a Menu " . "<b>" . $event_details[1]. "</b>";
			break;
		case "Menu Item Added" : 
			$string = " Added a item " . "<b>" . $event_details[2] . "</b>" . " of type " . "<b>" . $event_details[1] . "</b>" . " to the menu "."<b>" 
				. $event_details[3];
			 break;
		case "Menu Updated" :
			$string = " Updated a Menu Name from " . "<b>" . $event_details[1] . "</b>" . " to " . "<b>" . $event_details[2] . "</b>";
			break;
		case "siteurl" :
			$string = " Updated siteurl from " . "<b>" . $event_details[1]. "</b>" . " to " . "<b>" . $event_details[2] . "</b>";
			break;
		case "home" :
			$string = " Updated Home Link from " . "<b>" . $event_details[1] . "</b>" . " to " . "<b>" . $event_details[2]. "</b>";
			break;
		case "default_role" :
		case "permalink_structure" :
		case "admin_email" :
			$string =  $event_map[ $event_type ] . " From " . "<b>" . $event_details[1] . "</b>" . " to " . "<b>" . $event_details[2];
			break;
		}
		return $string;
	}


}

	