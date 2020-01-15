<?php

/*
 * This Class Contains all
 * the details related to 
 * the Post
 */


class Post {

	public $old_post_data;
	public $new_post_data;
	public $inserter;

  function __construct($inserter) {
		$this->inserter=$inserter;
		$this->activate_post_hooks();
		$this->set_up_tags();
  }

	public function activate_post_hooks() {
		add_action('save_post', array($this, 'post_inserted'), 10, 3);
		add_action('pre_post_update', array($this, 'save_post_data'), 10, 2);
	}
	
	public function post_modified_array() {
    $keys= array("post_title", "post_status", "post_author","post_content");
    return $keys;
  }

	public function save_post_data($user_id) {	
		$this->old_post_data = get_post($user_id);
	}
	
	public function set_up_tags() {
		$key = $this->post_modified_array();
    $this->inserter->setup_tags($key);
  }

	public function post_inserted($post_id, $post, $update) {
		$new_post_data = get_post($post_id);
		if($update)	{
			if($this->old_post_data->post_status == 'auto-draft' && $new_post_data->post_status == 'publish')	{
				$this->inserter->created(array( "post_title",
																			  "post_status",
																			  "post_author" 
																		                  ),
																"Post Created",
																 array( "post",
																			  $post_id )
																);
			}
			else {
				$key = array("post_author", "post_title", "post_content", "post_status");
				$this->inserter->modification_check($this->old_post_data, get_post($post_id), $key, "", $new_post_data->post_title);
			}
		}
	}
}
		





