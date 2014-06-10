<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
list($id, $location) = explode(",", $_POST['location']);
echo "<div id='content'>";
	echo "<h1>Please check the details to be saved. This walk will become live on the list instantly.</h1>";
 	echo "<table border=1>";
 		echo "<tr>";
 			echo "<td>Walk Location:</td>";
 			echo "<td>$location</td>";
 		echo "</tr>";
 		echo "<tr>";
 			echo "<td>Walk Date:</td>";
 			echo "<td>$_POST[wdate]</td>";
 		echo "</tr>";
 		echo "<tr>";
 			echo "<td>Walk Time:</td>";
 			echo "<td>$_POST[time]</td>";
 		echo "</tr>";
 		echo "<tr>";
 			echo "<td>Maximum Walkers:</td>";
 			echo "<td>$_POST[walkerlimit]</td>";
 		echo "</tr>";
 		echo "<tr>";
 			echo "<td>Poles Available:</td>";
 			echo "<td>$_POST[polesavailable]</td>";
 		echo "</tr>";
 	echo "</table>";
 	// Form to pass data through to next page
 	echo "<form action='dateconfirmed.php' method='post'>";
      	echo "<input type='hidden' name='id' value=$id>";
      	echo "<input type='hidden' name='location' value=$location>";
      	echo "<input type='hidden' name='wdate' value=$_POST[wdate]>";
      	echo "<input type='hidden' name='time' value=$_POST[time]>";
      	echo "<input type='hidden' name='walkerlimit' value=$_POST[walkerlimit]>";
      	echo "<input type='hidden' name='polesavailable' value=$_POST[polesavailable]>";
      	echo "<input type='submit'>";
    echo "</form>";
echo "</div>";




?>