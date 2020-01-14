<?php
/*
Plugin Name: Project Log
Plugin URI: NA
Description: This Plugin Log The Changes of a WordPress Website
Version: 1.0
Author: Deepanshu
Author URI: NA
 */

require_once('DataBase.php');
require_once('User.php');
require_once('Post.php');
require_once('Comment.php');
require_once('Inserter.php');
require_once('Attachments.php');
require_once('Table_Data.php');
require_once('View.php');
/* add_action('admin_menu', 'admin_menu_setup');
   function admin_menu_setup() {

    add_menu_page( 'Project Log', 'Log Plugin', 'manage_options', 'Project-Log', 'custom' );
  }

function custom() {
	echo "HELLO";
  }
*/

$db = new DataBase;
$inserter = new Inserter($db);
$user = new User($db,$inserter);
$post = new Post($db,$inserter);
$comment = new Comment($db,$inserter);
$attachment = new Attachments($db,$inserter);
$Tabledata = new Table_Data();
$view = new View($Tabledata);
add_action('admin_menu',array($view,admin_menu_setup));