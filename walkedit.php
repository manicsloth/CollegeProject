<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
echo "<div id='content'>";
	echo "<p><a href='add.php'>Add a walk to the database.</a></p>";
	echo "<p><a href='remove.php'>Remove a walk from the database.</a></p>";
echo "</div>";
?>