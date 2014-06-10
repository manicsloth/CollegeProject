<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
$id = $_POST['location'];

$nordic_db=db_connect();
$query = $nordic_db->prepare("select `location`, `meet`, `directions`, `details`, `dogs`, `wtime`, `distance`, `imageurl`, `url` from `walks` where `id` = ?");
$query->execute(array($id));
echo "<div id='content'>";
	$data=$query->fetch(PDO::FETCH_ASSOC);
          echo "<div class='routes'>";
          	echo "<table border=1>";
          		echo "<tr>";
          			echo "<td>Location:</td>";
          			echo "<td>" . $data['location'] . "</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Meeting Point:</td>";
          			echo "<td>$data[meet]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Directions:</td>";
          			echo "<td>$data[directions]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>About:</td>";
          			echo "<td>$data[details]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Dogs:</td>";
          			echo "<td>$dogs</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Walk Time:</td>";
          			echo "<td>$data[wtime]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Distance:</td>";
          			echo "<td>$data[distance]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Map:</td>";
          			echo "<td><a href='$data[url]'>Map</a></td>";
          		echo "</tr>";
          	echo "</table>";
          echo "</div>";
    
      
     echo "<h1>Are you sure you want to remove the selected walk from the database? This action is not reversible.</h1>";
     echo "<p>To go back and select another walk to delete <a href='remove.php'>click here.</a></p>";
     echo "<p>To add a walk instead <a href='add.php'>click here.</a></p>";
     echo "<p>To delete the selected walk click submit below:</p>";

     echo "<form action='removeconfirmed.php' method='post'>";
         echo "<input type='hidden' name='location' value='$id'>";
         echo "<input type='submit' value='submit'>";
     echo "</form>";
echo "</div>";
?>