<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
echo "<div id='content'>";
echo $data['id'] . ',' . $data['location'];
	echo "<form action='dateconfirm.php' method='post'>";
		echo "<select name='location' id='location'>";
			$nordic_db=db_connect();
			$query = $nordic_db->prepare("select `id` , `location` from `walks`");
			$query->execute();
			while($data= $query ->fetch(PDO::FETCH_ASSOC)) {
				$value = $data['id'] . "," . $data['location'];
				echo "<option value=$value>$value</option>";
			}
		echo "</select><br>";
		echo "<label for='wdate'>Walk Date</label>";
		echo "<input type='text' name='wdate'><br>";
		echo "<label for='time'>Walk Time:</label>";
		echo "<input type='text' name='time'><br>";
		echo "<label for='walkerlimit'>Maximum Walkers:</label>";
		echo "<input type='text' name='walkerlimit'><br>";
		echo "<label for='polesavailable'>Poles Available for this Walk:</label>";
		echo "<input type='text' name='polesavailable'><br>";
		echo "<input type='submit'>";
	echo "</form>";
echo "</div>";
?>