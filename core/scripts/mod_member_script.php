<?php

require 'core/init.php';
//check user is logged in
$admin->logged_out_protect();
//check permissions
$perm_check = $admin->permission_check( 'M');
if($perm_check == "no"){
	echo"<h2>Sorry, you do not have sufficient permissions to view this page.</h2> ";
	echo"<br /><button  onClick='history.go(-1);return true;'> Go Back </button>";
	exit;
}

//check for the existence of 'delete' in GET data, if this exists then  delete the member account otherwise proceed to modify the data.
if(isset($_GET['delete']) && $_GET['delete'] == "true"){
	//run query to delete the member
	$nordic_db=db_connect(); //connect to db
	$query = $nordic_db->prepare("DELETE FROM members WHERE id=?");
	$query->bindParam(1, $_GET['id']);
	$query->execute();
	//close connection to db
	$nordic_db = null;
	// return user to admins page and exit to make sure that the rest of this script does not run
	header("Location:members.php");
	exit;
}


//check for POST data with member data if missing then redirect to member list.
if(!isset($_POST['id']) || !isset($_POST['fname']) || !isset($_POST['sname']) || !isset($_POST['dob']) || !isset($_POST['address']) || !isset($_POST['town']) || !isset($_POST['postcode']) || !isset($_POST['contact_number']) || !isset($_POST['emerg_number']) || !isset($_POST['email']) || !isset($_POST['credits'])){
	echo "There has been an error. Missing POST data. <br /><emp><a href='admin_home.php'> Please click below to return.</a><emp>";
	exit;
}


$nordic_db=db_connect(); //connect to db

//query to update database with new data
$query = $nordic_db->prepare("UPDATE members set fname=?, sname=?, dob=?, address=?, town=?, postcode=?, contact_number=?, emerg_number=?, email=?, credits=? WHERE id=?"); 
//parameters for query
$query->bindParam(1, $_POST['fname']); 
$query->bindParam(2, $_POST['sname']); 
$query->bindParam(3, $_POST['dob']); 
$query->bindParam(4, $_POST['address']); 
$query->bindParam(5, $_POST['town']);
$query->bindParam(6, $_POST['postcode']);
$query->bindParam(7, $_POST['contact_number']);
$query->bindParam(8, $_POST['emerg_number']);
$query->bindParam(9, $_POST['email']); 
$query->bindParam(10, $_POST['credits']);
$query->bindParam(11, $_POST['id']);
//execute the query
$query->execute();
//return user to the members page.
header("Location:view_member.php?id=$_POST[id]");

?>;