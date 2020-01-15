<?php

class Comment {
	
	public $inserter;

	function __construct($inserter) {
								$this->inserter=$inserter;
								$this->activate_comment_hooks();
	}

	public function activate_comment_hooks() {
		add_action('comment_post', array( $this, 'comment_register'), 10, 3);
		add_action('transition_comment_status', array($this, 'comment_transition_detector'), 10, 3);	
	}

	public function comment_register($comment_id, $comment_approved, $comment_data) {
		$tag = $comment_data['comment_parent'] ? "Replied on a Comment" : "Commented on a Post";	
		$post_t= $this->get_post_title($comment_data['comment_post_ID']);
		$this->inserter->created(array( "Comment_Author" => $comment_data['comment_author'],
																				"Post_Title" => $post_t),
														$tag,
														"");
	}

	public function comment_transition_detector($new_status, $old_status, $comment) {
		$new_status = $old_status == "spam" ? "Unspammed" : $new_status;
		$new_status = $old_status == "trash"? "Untrashed" : $new_status;
		$post_t= $this->get_post_title($comment->comment_post_ID);
		$this->inserter->created( array( "Comment_Author" => $comment->comment_author,
																		 "Post_Title" => $post_t),	
														"Comment ".$new_status,
														"");
	}

	public function get_post_title($post_id){
    $post = get_post($post_id);
    return $post->post_title;
  }
}