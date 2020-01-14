<?php	


class View {
	
	public $details;

	function __construct(){
		$this->add_view_hooks();
	}

	public function add_view_hooks(){
		add_action('admin_menu', array($this,'admin_menu_setup'));
	}

	public function admin_menu_setup() {
	
    add_menu_page( 'Project Log', 'Log Plugin', 'manage_options', 'Project-Log', array($this,'customize' ));
	}

	public function customize() {
		global $wpdb;
		$values = $wpdb->get_results("SELECT * FROM wp_Actions1 order by P_id desc");
		$this->create_ui_table($values);	
	}

	public function create_ui_table($values) {
?>
	
		<table style="width:100%">
		   <tr>
        <th>Event</th>
        <th>Admin</th>
        <th>Date</th>
        <th>Event_Details</th>
			</tr>

<?php 
	
		foreach($values as $detail) {
			$event_detail = $this->get($detail->P_id,$detail->Tag);
//	$valued = $wpdb->get_results("SELECT * FROM Data1 where P_id=".$detail."from wp_Data1"); -->
	?>
			  <tr>
        	<td><?php echo $detail->Tag; ?></td>
      	 	<td><?php echo "HELLO"  ?></td> 
        	<td><?php echo $detail->Time; ?></td>
        	<td><?php echo $event_detail; ?></td>
     		</tr>		
	<?php
		}
	?>
		</table>
	<?php
	}

	public function get($value,$tag_type) {

		global $wpdb;
	
		$values = $wpdb->get_results("SELECT * FROM wp_Data1 where P_id=".$value);
		$make_up=array();
		foreach($values as $valued)
		{
			$make_up[] =$valued->vVaalue;
		}
		$string;


		switch($tag_type) {

			case "User_Created":
							$string=$make_up[1]." was created with the role of".$make_up[2];
							break;
			case "Post_Created":
					 		$string="A post Title ".$make_up[1]." was ".$make_up[2]." by ".$make_up[3];
					 		break;
			case "Modified__post_title":
					 		$string="The Title of the Post id ".$make_up[1]." was changed from ".$make_up[2]." to ".$make_up[3];
					 		break;
			case "spam":
			case "approved":
			case "unapproved": 
			case "Un_Spammed":  
			case "Un_Trashed" :
			case "trash" :
           		$string="The Comment on the Post titled ".$make_up[2]." by ".$make_up[1]." was marked as ".$tag_type;
					 		break;
			/*case "approved":
           $string="The Comment on the Post titled ".$make_up[2]." by ".$make_up[1]." was Approved ";
					 break;
			case "unapproved":
            $string="The Comment on the Post titled ".$make_up[2]." by ".$make_up[1]." was  unapproved ";
						break;
			case "Un_Spammed":
						$string="The Comment on the Post titled ".$make_up[2]." by ".$make_up[1]." was  Unspammed ";            
						break;
			case "Un_Trashed":
						 $string="The Comment on the Post titled ".$make_up[2]." by ".$make_up[1]." was Unspammed ";
						 break;
			case "trash":
             $string="The Comment on the Post titled ".$make_up[2]." by ".$make_up[1]." was Trashed ";
             break;*/
			case "Modified__post_content":
					$string="The post Titled ".$make_up[1]." Content was Modified ";
					break;
			case "Modified_User_roles":
           $string="The Role of the User ".$make_up[1]." was changed from ".$make_up[2]." to ".$make_up[3];
					 break;
			case "Modified_User_user_email":
					$string="The Email of the User ".$make_up[1]." was changed from ".$make_up[2]." to ".$make_up[3];
					break;
			case "Modified_User_display_name":
					 $string="The Display Name of the User ".$make_up[1]." was changed from ".$make_up[2]." to ".$make_up[3];
					 break;
			case "Modified_User_user_pass":
					$string="The Password of the User ".$make_up[1]." was changed ";
					break;

			case "Comment_to_a_Post":
					$string=$make_up[1]." Commented on a Post Titled ".$make_up[2];
					break;
			case "Comment_to_a_reply":
          $string=$make_up[1]." Replied on a Comment on a Post Titled ".$make_up[2];
					break;
		}
		return $string;

	
	}
}