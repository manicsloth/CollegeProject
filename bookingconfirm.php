<?php
require 'core/init.php';
$users->logged_out_protect();
$title="Booking Confirm";
require "header.php";
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
$selectionCount = count($walk);
// check health and safety completion and workshop completion, redirect if booking not allowed.
// status 3    $id = 2321;
$id = $users->get_id();

$status = $users->get_hs_status();
if ( $status == 1 ) {
	$_SESSION['notification'] = "Your Health and Safety form is awaiting approval, this should take no more than 7 days please use the contact section if your form has not been approved within this timescale. Thankyou.";
	header( 'Location: /member_home.php' ) ;
}else {
	if ($status == 0 ) {
		$_SESSION['notification'] = "We require a health and safety questionaire to be filled in and approved prior to booking a walk, please use button on this page to initiate this process. Thankyou.";
		header( 'Location: /member_home.php' ) ;
	}else {
		$wsstatus = $users->get_ws_status();
		if ( $wsstatus == 0 ) {
			$_SESSION['notification'] = "Before booking a walk you must first complete a workshop or contact us to discuss your previous experience so that we can exempt you. Please use the contact section if you have been walking with another club and do not wish to take a workshop, otherwise please book a workshop. Thankyou.";
			header( 'Location: /member_home.php');
		}else {
			if ( $status == 1 ) {
				$_SESSION['notification'] = "You are currently booked onto a workshop, you may book your first walk once this is completed. We look forward to seeing you at the workshop. Details of your booking can be seen below. Thankyou.";
				header( 'Location: /member_home.php');
			}
		}
	}
}

$booked = count($walk);
$creditBalance = $users->get_credits();
$newcreditbalance = ($creditBalance-$booked);
// Check that member has sufficient credit. If not store the bookings in a table for retrieval after purchasing credits.
if ($newcreditbalance < 0 ){
	$ids = implode(":" , $walk);
	$date = date("Y-m-d");
	$bookingTemp = new bookingTemp;
	$bookingTemp->insert($id, $booked,$ids, $date);
	header('Location: buymorecredits.php');
}else{
	echo "<div id='content'>";
		echo "<h1>Please review and confirm your selected walks.</h1>";
		echo "<h2>If you wish to make any changes please use the back button on your browser.</h2>";
		echo "<h3>You may cancel a walk for a refund of your credits any time up until 24 hours prior to the walk commencing</h3>";
		echo "<form id='booking' action='bookingconfirmed.php' method='post'>";
		echo '<table border=1>';
					echo "<thead>";
						echo "<td>Date</td>";
						echo "<td>Time</td>";
						echo "<td>Location</td>";
						echo "<td>Meeting Point</td>";
					echo "</thead>";
					
						
						
					$r=1;
					while ( $r <= $selectionCount ){
						$datey = ${'date' . $r}->spitArray();
						$r--;
						$w = array_search($walk[$r], $walkIndex);
						echo '<tr>';
							echo "<td>$datey[wdate]</td>";
							echo "<td>$datey[time]</td>";
							echo "<td>Chapel Porth</td>";
							echo "<td>National Trust Car Park at Chapel Porth</td>";
							echo "<input type='hidden' name='walk[]' value=",$datey['unqid'],">";
						echo '</tr>';
						$r++;
						$r++;
					}
					$walks=json_encode($walks);
					$dates1=json_encode($dates1);
					echo "<tfoot>";
						echo "<input type='hidden' value='$walks' name='walkobjects'>";
						echo "<input type='hidden' name='dates' value='$dates1' >";
						echo "<td colspan='4'>Click here to confirm bookings: <input type='submit' value='Confirm Booking'></td>";
					echo "</tfoot>";
				echo "</table>";
				//pass objects to next page
				
			echo "</form>";
		echo "</div>";

}





?>