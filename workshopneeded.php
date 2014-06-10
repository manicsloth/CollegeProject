<?php
require 'core/init.php';
$users->logged_out_protect();
$title="Booking";
require"header.php";
require "bookingclasses.php";
echo "You need to book a workshop first.";
?>