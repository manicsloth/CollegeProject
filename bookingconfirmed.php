<?php
require 'core/init.php';
$users->logged_out_protect();
$title="Booking Confirmation";
require"header.php";

require "bookingclasses.php";

$walks = json_decode($_POST["walkobjects"], true);
json_decode($_POST["dates"]);


// Retrieve all upcoming walk data and create an object for each walk, objects named walk1, walk2, walk3 etc

$count = $walks[0];
$r=1;
while ( $r <= $count ){
	${'walk' . $r} = new walk;
	${'walk' . $r} = ${'walk' . $r}->spitObjects($walks,$r);
	$r++;
}
$id=$users->get_id();
// Index of all id's against walk object no (walk1, walk2 etc)
$count++;
$walkIndex = $walks[$count];
$count--;
// Retreive all dates from today onwards and assign to objects date1, date2 etc
$dates1 = new dates;
$dates1 = $dates1->retrieveData();
$counter = $dates1[0];
$r=1;
while ( $r <= $counter ){
	${'date' . $r} = new dates;
	${'date' . $r} = ${'date' . $r}->spitObjects($dates1,$r);
	$r++;
}
$walk=array();
$walk = $_POST["walk"];
$bookingInsert = new bookingInsert;
$bookingstring = $bookingInsert->update($id ,$walk);
$tempor = $users->get_bookings();
$bookingstring = $tempor . $bookingstring;
$users->set_bookings($bookingstring);
$users->credit_control($id,"remove",$counter);
$_SESSION['notification'] = "Your selected walks have been succesfully booked you can see details of all walks you have booked with us below. Thankyou.";
header( 'Location: /member_home.php');
?>