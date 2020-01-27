<?php

class Attachment{

  public $logger;

  function __construct( $logger ) {
    $this->logger = $logger;
	}

	public static function init( $logger ) {
    $attachment = new Attachment( $logger );
    $attachment->activate_attachment_hooks();
  }

  public function activate_attachment_hooks() {
    add_action( 'add_attachment', array( $this, 'attachment_adder' ) );
    add_action( 'delete_attachment', array( $this, 'attachment_deletor' ) );
  }

	public function attachment_adder( $attachment_id ) {
    $details_array= $this->get_required_detail( $attachment_id );
    $this->logger->log( $details_array, "Attachment Added" );
  }
	
	public function attachment_deletor( $attachment_id ) {
    $details_array= $this->get_required_detail( $attachment_id );
    $this->logger->log( $details_array, "Attachment Deleted" );
  }

  public function get_required_detail( $attachment_id ) {
		$file = get_attached_file( $attachment_id );
		$detail_array = array(
			'FileName' => basename( $file ),
			'FilePath' => dirname( $file )
		);
    return $detail_array;
    }
}


