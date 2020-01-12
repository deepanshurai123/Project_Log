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

$db = new DataBase;
$inserter = new Inserter($db);
$user = new User($db,$inserter);
$post = new Post($db,$inserter);
$comment = new Comment($db,$inserter);
$attachment = new Attachments($db,$inserter);
