<?php

class Menus {

	 public $old_menu = array();		
	 public $logger;
	 public $old_menu_item = array();

	 function __construct( $logger ) {
    $this->logger = $logger;
    $this->activate_menu_hooks(); 
	 }

	 public static function init( $logger ) { 
		 $menus = new Menus( $logger );
		 $menus->activate_menu_hooks();
  }

	 public function activate_menu_hooks() {
		 add_action( 'wp_create_nav_menu', array( $this, 'menu_created' ), 10, 2 );
		 add_action( 'wp_update_nav_menu', array( $this, 'updated_menu' ), 10, 2 );
		 add_action( 'admin_init', array( $this, 'admin_setting' ) );
		 add_action( 'wp_update_nav_menu_item', array( $this, 'UpdateMenuItem' ), 10, 3 );
	 }

	 public function UpdateMenuItem( $menu_id, $menu_item_db_id, $args ) {
		  $post_array = filter_input_array( INPUT_POST );
			if ( isset( $post_array[ 'menu-item-title' ] ) && isset( $post_array[ 'menu-name' ] ) ) {
		 			if( !isset( $this->old_menu_item[ $menu_item_db_id ][ $menu_id ] ) ) {
			 			$this->old_menu_item += [ $menu_item_db_id => array( $menu_id => 1 ) ];
			 			$content_type = $post_array[ 'menu-item-object' ][ $menu_item_db_id ];
			 			$content_name = $post_array[ 'menu-item-title' ][ $menu_item_db_id ];
			 			$menu_name    = $post_array[ 'menu-name' ];
			 			$this->logger->log( array(						
							'ContentType' => 'custom' === $content_type ? 'custom link' : $content_type ,
							'ContentName' => $content_name,
							'MenuName'    => $menu_name	
						), "Menu Item Added" );
					}
			}
	 }

	 public function admin_setting() {
			$server_array = filter_input_array( INPUT_SERVER );
			$script_name = '';
			if ( ! empty( $server_array[ 'SCRIPT_NAME' ] ) ) {
      	$script_name = $server_array[ 'SCRIPT_NAME' ];
			}
			$is_nav_menu = basename( $script_name ) == 'nav-menus.php';
			if( $is_nav_menu ) {
				$menus= wp_get_nav_menus() ;
				foreach( $menus as $menu ) {
					$this->old_menu += [ $menu->term_id => array( 'name' => $menu->name ) ];
					$menu_items = wp_get_nav_menu_items( $menu->term_id );
					$menu_id = $menu->term_id;
					foreach( $menu_items as $menu_item ) {
							$this->old_menu_item += [ $menu_item->ID => array( $menu_id => 1 ) ];
					}
				}
			}
	 }
	 

	 public function menu_created( $term_id, $menu_data ) {
		  $this->logger->log( array ( "MenuTitle" => $menu_data[ 'menu-name' ] ), "Menu Created" );
	 }

	 public function updated_menu( $menu_id, $menu_data = NULL ) {	
		 if( !empty( $menu_data ) ) {
		 		$new_menu_name = $menu_data[ 'menu-name' ];
		 		if( $this->old_menu[ $menu_id ][ 'name' ] != $new_menu_name )  {
       		$this->logger->log( array ( 					
						"From"  =>  $this->old_menu[ $menu_id ][ 'name' ],
						"To"    =>  $new_menu_name
					), "Menu Updated" );
					$this->old_menu = [ $menu_id->ID => array( 'name' => $post_array[ 'menu-name' ] ) ];
			 }
		 }		
	 }
}