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
//		echo "HELLO";
		
		global $wpdb;
		$values = $wpdb->get_results("SELECT * FROM wp_Actions1 order by P_id desc");	
		?>
			<table>
			<tr>
			<th><Event</th>
 				<th>Admin</th>
 				<th>Date</th>
				<th>Event_Details</th>
			</tr>
			</table><?php
			foreach($values as $detail) { 
				$event_detail = $this->get($detail->P_id,$detail->Tag);
		$valued = $wpdb->get_results("SELECT * FROM Data1 where P_id.Data1=".$valued."from wp_Data1");
		?>
			<tr>
				<td><center><?php echo $detail->Tag; ?></center></td>
 				<td><center><?php echo $valued->user_id; ?></center></td>
 				<td><center><?php echo $detail->Time; ?></center></td>
 				<td><center><?php echo $event_detail; ?></center></td>
		 </tr>
<?php

			 

			}?>

	</table>
<?php 
			
	}

	public function get($value,$tag_type) {

		global $wpdb;
	
		$values = $wpdb->get_results("SELECT * FROM wp_Data1 where P_id=".$value);
		$string;
		//echo $values;

		switch($tag_type) {

			case "User_Created":
						$string=$values->user_login." was created with the role of".$values->roles;
						break;
			/*case "Post Created":
				$string= A post Title "" was "" by ____;`:w
*/
		}
		return $string;

	
	}
}