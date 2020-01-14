<?php

/*
 * This Class Contains all
 * the details related to 
 * the Post
 */


class Post {

	public $db;

	public $old_post_data;
	public $new_post_data;
	public $inserter;

	public function __construct(Database $db,Inserter $inserter) {

								$this->inserter=$inserter;
                $this->db=$db;
                $this->activate_post_hooks();
        }

	public function activate_post_hooks() {

		add_action('save_post',array($this,'post_inserted'),10,3);
		add_action('pre_post_update',array($this,'save_post_data'),10,2);
	}

	public function save_post_data($user_id) {
		
		$this->old_post_data=get_post($user_id);
	}

	public function post_inserted($post_id,$post,$update) {
		$new_post_data=get_post($post_id);
		if($update)	{
			
			if($this->old_post_data->post_status=='auto-draft' && $new_post_data->post_status=='publish')	{
				$this->inserter->created(array("post_title",
																				"post_status","post_author"),
																"Post_Created",
																array("post",
																			$post_id)
															);
			}
			
			else {
					$key = array("post_author","post_title","post_content","post_status");
					$this->inserter->modification_check($this->old_post_data,get_post($post_id),$key,"",$new_post_data->post_title);
			}
		}

	}

}
		







