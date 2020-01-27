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
require_once('Logger.php');
require_once('Attachments.php');
require_once('View.php');
require_once('Menu.php');
require_once('Plugin.php');
require_once('Settings.php');

global $event_map; 
$event_map = array();

$db = new Database();
$logger = new Logger( $db );
$view = new View(  $logger );

User::init( $logger );
Post::init( $logger );
Comment::init( $logger );
Attachment::init( $logger );
Menus::init( $logger );
Plugins::init( $logger );
Settings::init( $logger );

add_action( 'admin_menu', array( $view, 'admin_menu_setup' ) );



