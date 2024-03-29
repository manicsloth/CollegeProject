<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
// convert POST data to standard variables
$location = $_POST['location'];
$meet = $_POST['meet'];
$directions = $_POST['directions'];
$details = $_POST['details'];
$dogs = $_POST['dogs'];
$wtime = $_POST['wtime'];
$distance = $_POST['distance'];
$url = $_POST['url'];
$imageurl = $_POST['imageurl'];
// print details to screen for checking
echo "<div id='content'>";
     echo "<h1>Please check the details to be saved. This walk will become live on the list instantly.</h1>";
     echo "<div class='routes'>";
     	echo "<table border=1>";
     		echo "<tr>";
     			echo "<td>Location:</td>";
     			echo "<td>$location</td>";
     		echo "</tr>";
     		echo "<tr>";
     			echo "<td>Meeting Point:</td>";
     			echo "<td>$meet</td>";
     		echo "</tr>";
     		echo "<tr>";
     			echo "<td>Directions:</td>";
     			echo "<td>$directions</td>";
     		echo "</tr>";
     		echo "<tr>";
     			echo "<td>About:</td>";
     			echo "<td>$details</td>";
     		echo "</tr>";
     		echo "<tr>";
     			echo "<td>Dogs:</td>";
     			echo "<td>$dogs</td>";
     		echo "</tr>";
     		echo "<tr>";
     			echo "<td>Walk Time:</td>";
     			echo "<td>$wtime</td>";
     		echo "</tr>";
     		echo "<tr>";
     			echo "<td>Distance:</td>";
     			echo "<td>$distance</td>";
     		echo "</tr>";
     		echo "<tr>";
     			echo "<td>Image Url:</td>";
     			echo "<td>$imageurl</td>";
     		echo "</tr>";
     		echo "<tr>";
     			echo "<td>Url:</td>";
     			echo "<td>$url</td>";
     		echo "</tr>";
     	echo "</table>";
     	// Form to pass data through to next page
     	echo "<form action='addconfirmed.php' method='post'>";
          	echo "<input type='hidden' name='location' value=$location>";
          	echo "<input type='hidden' name='meet' value=$meet>";
          	echo "<input type='hidden' name='directions' value=$directions>";
          	echo "<input type='hidden' name='details' value=$details>";
          	echo "<input type='hidden' name='dogs' value=$dogs>";
          	echo "<input type='hidden' name='wtime' value=$wtime>";
          	echo "<input type='hidden' name='distance' value=$distance>";
          	echo "<input type='hidden' name='imageurl' value=$imageurl>";
          	echo "<input type='hidden' name='url' value=$url>";
          	echo "<input type='submit'>";
        echo "</form>";
     echo "</div>";
echo "</div>";
?>