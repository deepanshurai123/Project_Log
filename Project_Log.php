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
require_once('Menu.php');
require_once('Plugin.php');
require_once('Settings.php');
$db = new DataBase;
$inserter = new Inserter($db);
$user = new User($inserter);
$post = new Post($inserter);
$comment = new Comment($inserter);
$attachment = new Attachments($inserter);
$menus = new Menus($inserter);
$Tabledata = new Table_Data($inserter);
$view = new View($Tabledata, $inserter);
$plugin = new Plugins($inserter);
$settings = new Settings($inserter);
add_action('admin_menu',array($view, admin_menu_setup));
 