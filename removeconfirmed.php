<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
$id=$_POST['location'];
$nordic_db=db_connect();
$query = $nordic_db->prepare("select `id` , `location` , `details` from `walks` where `id` = ?");
$query->execute(array($id));
$data = $query->fetch(PDO::FETCH_ASSOC);
echo "<div id='content'>";
	echo "The Following walk has been deleted from the database:<br>";
	echo "Id = $data[id]<br>";
	echo "Location: $data[location]<br>";
	echo "About: $data[details]";
echo "</div>";
$query = $nordic_db->prepare("delete from `walks` where `id` = ?");
$query->execute(array($id));
?>