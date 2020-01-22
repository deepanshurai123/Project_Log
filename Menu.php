<?php

class Menus {


	 public $old_menu= array();		
	 public $inserter;
	 public $old_menu_item= array();

	 function __construct($inserter) {
    $this->inserter = $inserter;
    $this->activate_menu_hooks();
	 }

	 public function activate_menu_hooks() {
		 add_action( 'wp_create_nav_menu', array( $this, 'menu_created' ), 10, 2 );
		add_action( 'wp_update_nav_menu', array( $this, 'updated_menu' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'admin_setting' ) );
	 }

	 public function admin_setting() {
			$server_array = filter_input_array( INPUT_SERVER );
			$script_name = '';
			
			if ( ! empty( $server_array['SCRIPT_NAME'] ) ) {
      	$script_name = $server_array['SCRIPT_NAME'];
			}
			
			$is_nav_menu = basename( $script_name ) == 'nav-menus.php';
			if($is_nav_menu) {
				$menuss= wp_get_nav_menus() ;
				foreach($menuss as $menuu)
				{
					$this->old_menu+= [ $menu->term_id => array( 'name' => $menu->name) ];
					$menu_ids=wp_get_nav_menu_items( $menu->term_id );
					$item_id=array();
					foreach($menu_ids as $menu_id) {
						$this->old_menu_item += [$menu->term_id => array( $menu_id->ID => 1 )];
					}


//					$this->old_menu_item += wp_get_nav_menu_items( $menu->term_id );
				}
	//			die(count($this->old_menu_item));

			/*	 foreach($menu as $k) {
					 echo($k->term_id);
					 echo(" ");
					 echo($k->name);
					 echo("<br>");
      }
      die();

			*/					
						

			}
	



	 }
	 

	 public function menu_created($term_id,$menu_data) {
		  $this->inserter->created( array ( "MenuTitle" => $menu_data['menu-name'] ),
                              "Menu Created", NULL
                          );
	 }

	 public function updated_menu($menu_id,$menu_data) {
		 $post_array = filter_input_array( INPUT_POST );
		// if($this->old_menu[$menu_id]['name']!= $post_array['menu-name'])
	//		 die("HE");
		 /*foreach($this->old_post as $menu_obj) {
		 		if($menu_obj->term_id == $menu_id  && $menu_obj->name  != $post_array['menu-name'])
					die("FISHYY")	;*/	
	//	 }
	//	 $new_menu_items   = array_keys( $post_array['menu-item-title'] );
  	 //$added_items = array_diff( $new_menu_items, array_keys( $old_menu_items ) );
//			 die(count($this->old_menu_item));
 


	
		/* foreach($menu_data as $key=>$value) {
        echo($key);
        echo(" => ");
        echo($value);
        echo("<br>");
      }
      die();*/
	 }

}
