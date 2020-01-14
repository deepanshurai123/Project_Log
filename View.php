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
					 $string="The Title of the Post by ".$make_up[1]." was ".$make_up[2]." by ".$make_up[3];
           break;


			/*case "Post Created":
				$string= A post Title "" was "" by ____;`:w
*/
		}
		return $string;

	
	}
}