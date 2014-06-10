<?php
require '../init.php';
$admin->logged_out_protect();


//check permissions
$perm_check = $admin->permission_check( 'H');
if($perm_check == "no"){
	header("Location:../../home.php");
	exit;
}


//error if order no is not sent in post data
if(!ISSET($_POST['hs_id'])){
	echo "Error - No id number received. {hs_notes_script}";
	exit;
}

//extract data from post and get
$id = $_POST['hs_id'];
$notes = $_POST['hs_notes'];

//run function to insert into db

$member->mod_hs_notes($id, $notes);
exit;
?>