<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";

// Convert POSt data to standard variables
$location = $_POST['location'];
$meet = $_POST['meet'];
$directions = $_POST['directions'];
$details = $_POST['details'];
$dogs = $_POST['dogs'];
$wtime = $_POST['wtime'];
$distance = $_POST['distance'];
$url = $_POST['url'];
$imageurl = $_POST['imageurl'];
// grab largest id on file already
$nordic_db=db_connect();
$query = $nordic_db->prepare("select `id` from `walks` order by `id`");
$query->execute();
$data = $query ->fetch(PDO::FETCH_ASSOC);
$id = ($data['id']['1']);
$id = $id + 1;
updateWalks($id, $location, $meet, $directions, $details, $dogs, $wtime, $distance, $imageurl, $url );
$_SESSION['admin_notification'] = "Your walk has been added to the system.";
header( 'Location:/admin_home.php');



?>
