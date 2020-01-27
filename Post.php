<?php

require_once('Data_Utill.php');

class Post extends Data_Utill {

	public $old_post_data;
	public $new_post_data;
	public $old_cat;
	public $new_cat;
	public $new_tags;
	public $old_tags;
	public $logger;
	public $post_properties = array( "post_title", "post_author", "post_content" );

  function __construct( $logger ) {
		$this->logger = $logger;
		$this->property_to_event( $this->post_properties );
	}

	public static function init( $logger ) {
    $post = new Post( $logger );
		$post->activate_post_hooks();
  }

	public function activate_post_hooks() {
		add_action( 'save_post', array( $this, 'post_inserted' ), 10, 3 );
		add_action( 'pre_post_update', array( $this, 'save_post_data' ), 10, 2 );
		add_action( 'delete_post', array( $this, 'deleted_post' ));
		add_action( 'wp_trash_post', array( $this, 'trashed_post' ));
		add_action( 'untrash_post', array( $this, 'untrashed_post' ));
		add_action( 'set_object_terms', array( $this, 'terms_changed' ), 10, 4 );
		add_action( 'admin_action_edit', array( $this, 'post_edit_detector' ), 10 );
		add_action( 'wp_head', array( $this, 'view' ), 10 );
	}
	
	public function view() {
		$post = get_queried_object();
		$post_data = get_post( $post->ID );
		$post_details = $this->get_data( array( 
			"post_title",
			"post_status",	
			"post_author"
		), $post_data );
    $this->logger->log( $post_details, "Post Viewed" );
	}

	public function post_edit_detector() {
		$post_id = isset( $_GET['post'] ) ? (int) sanitize_text_field( wp_unslash( $_GET['post'] ) ) : false;
		if ( empty( $post_id ) ) {
      return;
    }
		$post_data = get_post( $post_id );
		$post_details = $this->get_data( array( 
			"post_title",
			"post_status",
			"post_author"
		), $post_data );
		$this->logger->log( $post_details, "Post Opened" );	
	}

	public function trashed_post( $post_id ) {
		$post_data = get_post( $post_id );
		if( $post_data->post_status != 'publish' )
			return;
		$post_details = $this->get_data( array(
      "post_title",
      "post_author",
    ), $post_data );
		$this->logger->log( $post_details, "Post Trashed" );
	}

	public function untrashed_post( $post_id ) {
		$post_data=get_post($post_id);
		$post_details = $this->get_data( array(
			"post_title",
      "post_author",
    ), $post_data );
    $this->logger->log( $post_details, "Post Untrashed" );
  }
	
	public function save_post_data( $user_id ) {	
		$this->old_post_data = get_post( $user_id );
		$this->old_cats  = $this->get_post_categories( $this->old_post_data );
		$this->old_tags  = $this->get_post_tags( $this->old_post_data );
	}
	
	public function post_inserted( $post_id, $post, $update ) {
		$new_post_data = get_post( $post_id );
		if( $update )	{
			if( $this->old_post_data->post_status == 'auto-draft' && $new_post_data->post_status == 'publish' )	{
				$post_details = $this->get_data( array(
					"post_title",					
					"post_author",
					"post_status"
				), $new_post_data );
				$this->logger->log( $post_details , "Post Created" );
			} else {
				$modified_details = $this->check_modified_values( $this->old_post_data, $new_post_data, 
				$this->post_properties, "", $new_post_data->post_title );
				foreach( $modified_details as $modified_tag => $modified_detail ) {
					      $this->logger->log( $modified_detail, $modified_tag );
				}
			}
		}
	}

	public function deleted_post( $post_id ) {
		$post_data = get_post( $post_id );
		if( $post_data->post_status != 'trash' )
			return;
		$post_details = $this->get_data( array(
      "post_title",
      "post_author",
    ), $post_data );
		$this->logger->log( $post_details, "Post Deleted" );
	}

	public function get_post_tags( $post ) {
		return ! isset( $post->ID ) ? array() : wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) );
	}
	
	public function get_post_categories( $post ) {
    return ! isset( $post->ID ) ? array() : wp_get_post_categories( $post->ID, array( 'fields' => 'names' ) );
	}

	public function check_tags_change( $new_tag, $post ) {
		$added_tags = array_diff(  $new_tag,  $this->old_tags );
    $removed_tags = array_diff(  $this->old_tags,  $new_tag );
    $old_tags     = implode( ', ', (array) $this->old_tags );
    $new_tags     = implode( ', ', (array) $new_tags );
    $added_tags   = implode( ', ', $added_tags );
    $removed_tags = implode( ', ', $removed_tags );
    if( $this->old_tags != $new_tags && !empty( $added_tags )) {
      $this->logger->log( array(				
				"post_title" =>  $post->post_title,
				"Tags"       =>  $added_tags  
			), "Tags Added" );
    }
    if( $this->old_tags != $new_tags && !empty( $removed_tags )) {
       $this->logger->log( array(				 
				 "post_title" => $post->post_title,
				 "Tags"       => $removed_tags 
			 ), "Tags Removed" );
    }
	}

	public function check_categories_change( $old_cats, $new_cats, $post ) {
    $old_cats = implode( ', ', (array) $old_cats );
    $new_cats = implode( ', ', (array) $new_cats );
    $old_cats = empty( $old_cats ) ? "NO CATEGORY" : $old_cats;
    $new_cats = empty( $new_cats ) ? "NO CATEGORY" : $new_cats;
    if( $old_cats != $new_cats ) {
          $this->logger->log( array(				 
						"post_title"      =>  $post->post_title,
						"Old_Categories"  =>  $old_cats,
						"New_Categories"  =>  $new_cats
					), "Category Changed" );
    }
	 }

	public function terms_changed( $post_id, $terms, $tt_ids, $taxonomy ) {
    $post = get_post( $post_id );
    if( 'auto-draft'== $post->post_status )
      return;
    if ( 'post_tag' == $taxonomy ) {
      $this->check_tags_change( $this->get_post_tags( $post ), $post );
    } else {
      $this->check_categories_change( $this->old_cats, $this->get_post_categories( $post ), $post );
    }
	 }		
}