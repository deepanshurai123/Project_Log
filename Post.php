<?php

/*
 * This Class Contains all
 * the details related to 
 * the Post
 */


class Post {

	public $old_post_data;
	public $new_post_data;
	public $old_cat;
	public $new_cat;
	public $new_tags;
	public $old_tags;
	public $inserter;

  function __construct($inserter) {
		$this->inserter=$inserter;
		$this->activate_post_hooks();
		$this->set_up_tags();
  }

	public function activate_post_hooks() {
		add_action('save_post', array($this, 'post_inserted'), 10, 3);
		add_action('pre_post_update', array($this, 'save_post_data'), 10, 2);
		add_action('delete_post', array($this, 'deleted_post'));
		add_action('wp_trash_post', array($this, 'trashed_post'));
		add_action('untrash_post', array($this, 'untrashed_post'));
		add_action('set_object_terms', array( $this, 'terms_changed' ), 10, 4 );
		add_action( 'admin_action_edit', array( $this, 'post_edit_detector' ), 10 );
		add_action( 'wp_head', array( $this, 'view' ), 10 );
	}
	
	public function view() {
		$post = get_queried_object();
		$post_data = get_post($post_id);
    $this->inserter->created(array(
                                    "post_title",
                                    "post_status",
                                    "post_author"
                                ),
                                "Post Viewed",
                                $post_data
                          );

	}


	public function post_edit_detector() {

		$post_id = isset( $_GET['post'] ) ? (int) sanitize_text_field( wp_unslash( $_GET['post'] ) ) : false;
		$post_data = get_post($post_id);
		$this->inserter->created(array(
                                        "post_title",
                                        "post_status",
                                        "post_author"
                                    ),
                                "Post Opened",
                                $post_data
                            );	
	}

	public function terms_changed( $post_id, $terms, $tt_ids, $taxonomy ) {
	
		$post = get_post( $post_id );	
		if('auto-draft'== $post->post_status)
			return;
		if ('post_tag' == $taxonomy ) {
			$this->check_tags_change( $this->get_post_tags( $post ), $post );
		} else {
      $this->check_categories_change( $this->old_cats, $this->get_post_categories( $post ), $post );
    }
	}

	public function check_categories_change($old_cats,$new_cats,$post) {
		$old_cats = implode( ', ', (array) $old_cats );
		$new_cats = implode( ', ', (array) $new_cats );
		$old_cats = empty($old_cats) ? "NO CATEGORY" : $old_cats; 
		$new_cats = empty($new_cats) ? "NO CATEGORY" : $new_cats;
		if($old_cats != $new_cats) {
			    $this->inserter->created( array(
                                     "post_title"      =>  $post->post_title ,
																		 "Old_Categories"  =>  $old_cats , 
																		 "New_Categories"  =>  $new_cats
																	 ),
                            "Category Changed",
                            NULL);	
		}
	}	

	public function check_tags_change($new_tag,$post) {
		$added_tags = array_diff(  $new_tag,  $this->old_tags );
		$removed_tags = array_diff(  $this->old_tags,  $new_tag );
		
    $old_tags     = implode( ', ', (array) $this->old_tags );
    $new_tags     = implode( ', ', (array) $new_tags );
    $added_tags   = implode( ', ', $added_tags );
		$removed_tags = implode( ', ', $removed_tags );

		if($this->old_tags!=$new_tags && !empty($added_tags)) {
			$this->inserter->created( array(
                                     "post_title" =>  $post->post_title,
                                     "Tags"       =>  $added_tags ),
                            "Tags Added",
														NULL);
		}

		if($this->old_tags!=$new_tags && !empty($removed_tags)) {
			 $this->inserter->created( array(
                                     "post_title" => $post->post_title,
                                     "Tags"       => $removed_tags ),
                            "Tags Removed",
                           NULL);
		}
	}

	public function trashed_post($post_id) {
		$post_data=get_post($post_id);
		if($post_data->post_status != 'publish')
			return;
		$this->inserter->created(array( 
																		"post_title",
                                    "post_author"
                                ),
                            "Post Trashed",
                            $post_data
													);
	}

	public function untrashed_post($post_id) {
	 $post_data=get_post($post_id);
	 $this->inserter->created(array( 
																		"post_title",
                                    "post_author"
															 ),
                            "Post Untrashed",
                            $post_data
												);
  }
	
	public function post_modified_array() {
		$keys= array(
									"post_title", 
									"post_author",
									"post_content"
							);
    return $keys;
  }

	public function save_post_data($user_id) {	
		$this->old_post_data = get_post($user_id);
		$this->old_cats  = $this->get_post_categories($this->old_post_data);
		$this->old_tags  = $this->get_post_tags($this->old_post_data);
	}
	
	public function set_up_tags() {
		$key = $this->post_modified_array();
    $this->inserter->setup_tags($key);
  }

	public function post_inserted($post_id, $post, $update) {
		$new_post_data = get_post($post_id);
		if($update)	{
			if($this->old_post_data->post_status == 'auto-draft' && $new_post_data->post_status == 'publish')	{
				$this->inserter->created(array( 
																				"post_title",
																			  "post_status",
																			  "post_author" 
																		),
																"Post Created",
																$new_post_data
														);
			} else {
					$key = array(
											"post_author", 
											"post_title", 
											"post_content"
									);
				$this->inserter->modification_check($this->old_post_data, get_post($post_id), $key, "", $new_post_data->post_title);
			}
		}
	}

	public function deleted_post($post_id) {
		$post_data=get_post($post_id);
		if($post_data->post_status!='trash')
			return;
		$this->inserter->created(array( 
																		"post_title",
																		"post_author"
																),
														"Post Deleted",
														$post_data
													);
	}

	public function get_post_tags($post) {
		return ! isset( $post->ID ) ? array() : wp_get_post_tags( $post->ID, array( 
																																									'fields' => 'names' 
																																						) 
																																					);
	}

	public function get_post_categories( $post ) {
    return ! isset( $post->ID ) ? array() : wp_get_post_categories( $post->ID, array( 'fields' => 'names' ) );
  }
		
}