<?php
//This file is included  in all other files in root directory. It loads other necessary files and starts the users session
//starting the users session, load required files
session_start();
require 'database.php';
require 'users.php';
require 'general.php';
require 'bcrypt.php'; 

$users 	= new Users($db_members); //create new instance of users class
$general 	= new General(); //create instance of general class


require 'admin.php';
require 'member.php';
require 'adminControl.php';
$admin 	= new Admin($db); //create new instance of users class


//if user is already logged in (has session variable with serialized object data) then load it into $users.
if(isset($_SESSION['users_ser']) && !empty($_SESSION['users_ser'])){
 	$users = $general->unserializeUser();
}
//check for admin logged in, and load their object into $admin. also create some admin only classes
elseif(isset($_SESSION['admin_ser']) && !empty($_SESSION['admin_ser'])){
 	$admin = $general->unserializeAdmin();
 	$adminControl	= new AdminControl($db); //create instance of admincontrol class.
 	$member	= new Member($db_members); //create instance of member class
}


$bcrypt 	= new Bcrypt(); //create instance of bcrypt class

$errors 	= array();
?>