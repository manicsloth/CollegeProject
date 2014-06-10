<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
echo "<div id='content'>";
	echo "<h1>Remove a walk from the database.</h1>";
	echo "<p>Warning: This cannot be undone, all changes are permenant.</p>";
	echo "<form action='removeconfirm.php' method='post'>";
		echo "<select name='location' id='location'>";
		$nordic_db=db_connect();
		$query = $nordic_db->prepare("select `id` , `location` from `walks`");
		$query->execute();
		while($data= $query ->fetch(PDO::FETCH_ASSOC)) {
			echo "<option value='$data[id]'>$data[location]</option>";
		}
		echo "</select>";
		echo "<input type='submit'>";
	echo "</form>";
echo "</div>";
?>
