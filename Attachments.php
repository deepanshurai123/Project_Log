<?php

class Attachments{


  public $db;
  public $inserter;


  function __construct(Database $db,Inserter $inserter) {

    $this->db=$db;
    $this->inserter=$inserter;
    $this->activate_attachment_hooks();
  }




  public function activate_attachment_hooks() {

    add_action('add_attachment',array($this,'attachment_adder'));
    add_action('delete_attachment',array($this,'attachment_deletor'));

  }


	public function attachment_adder($attachment_id) {

		//die();

    $details_array= $this->get_required_detail($attachment_id);
    $this->inserter->created($details_array,"attachment_added","");

  }
  public function attachment_deletor($attachment_id) {

    $details_array= $this->get_required_detail($attachment_id);
    $this->inserter->created($details_array,"attachment_deleted","");

  }

  public function get_required_detail($attachment_id) {

		$file = get_attached_file($attachment_id);
    $detail_array = array('FileName'=>basename($file),
                          'FilePath'=>dirname($file)
                          );
    return $detail_array;



    }
}






