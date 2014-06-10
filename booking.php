<?
require 'core/init.php';
$users->logged_out_protect();
$title="Booking";
require"header.php";
require "bookingclasses.php";

// Retrieve all upcoming walk data and create an object for each walk, objects named walk1, walk2, walk3 etc
$walks = new walk;
$walks = $walks->retrieveData();
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
print_r($walkIndex);
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

$datum = new dates;
echo '<div id="content">';
	echo "<form id='booking' action='bookingconfirm.php' method='post'>";
		echo '<table border=1>';
			echo "<thead>";
				echo "<td>Date</td>";
				echo "<td>Time</td>";
				echo "<td>Location</td>";
				echo "<td>Meeting Point</td>";
				echo "<td>Booking</td>";
			echo "</thead>";
			$r=1;
			while ( $r <= $counter ){
				$datey = ${'date' . $r}->spitArray();
				$w = array_search($datey['id'], $walkIndex);
				echo '<tr>';
					echo "<td>$datey[wdate]</td>";
					echo "<td>$datey[time]</td>";
					echo "<td>",${'walk' . $r}->spitLocation(),"</td>";
					echo "<td>",${'walk' . $r}->spitmeet(),"</td>";
					echo "<td><input type='checkbox' name='walk[]' value=",$datey['unqid'],">Book","</td>";
				echo '</tr>';
				$r++;
			}
			$walks=$walk1->spitJson($walks);
			$dates1=$datum->spitJson($dates1);
			echo "<tfoot>";
				echo "<input type='hidden' value='$walks' name='walkobjects'>";
				echo "<input type='hidden' name='dates' value='$dates1' >";
				echo "<td colspan='5'>Click here to submit bookings: <input type='submit' value='Book Now'></td>";
			echo "</tfoot>";
		echo "</table>";
		//pass objects to next page
		
	echo "</form>";
echo "</div>";

?>