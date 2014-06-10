<?php
require 'core/init.php';
$users->logged_out_protect();
$title="Booking";
require"header.php";
require "bookingclasses.php";
echo "Health and Safety form has not been received, please submit this for apporval first. If you do not have the form yet <a =href'hsform.php'>click here</a>";
?>