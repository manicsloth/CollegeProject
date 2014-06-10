<?php
require 'core/init.php';
$users->logged_out_protect();
$title="Booking";
require"header.php";
require "bookingclasses.php";
echo "Your health and safety form has not been reviewed and accepted, please allow x time for this to happen or email on <a href='mailto:someone@example.com?Subject=H&S%20Form%20Approval'>Walk Kernow</a>";
?>