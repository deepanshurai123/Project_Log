<?php

class Plugins {

	 public $inserter;

	 public function __construct(Inserter $insereter) {
		 $this->inserter=$inserter;
		 $this->activate_plugin_hooks();
	 }
	
	 public function activate_plugin_hooks() {
	 		add_action( 'shutdown', array( $this, 'check_for_plugin' ) );
	 }

	 public function check_for_plugin() {
		 die("HERE");
	 }


	 

