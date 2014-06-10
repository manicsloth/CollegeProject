<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
echo "<div id='content'>";
     echo "<form action='walkeditor.php' method='post'>";
     walkFetch();
     echo "</form>";
echo "</div>";



?>