<?php
require 'core/init.php';
$title="CSV Upload";
require"header.php";
require "bookingclasses.php";

echo "<div id='content'>";
	echo "<table name='locations' id ='locations'>";
		echo "<tr>";
			$nordic_db=db_connect();
			$query = $nordic_db->prepare("select `id` , `location` from `walks`");
			$query->execute();
			while($data= $query ->fetch(PDO::FETCH_ASSOC)) {
				$value = $data['id'] . "," . $data['location'];
				echo "<td>$value</td>";
			}
		echo "</tr>";
	echo "</table>";
echo "</div>";



?>