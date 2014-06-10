<?php
require 'core/init.php';
$users->logged_out_protect();
$title="Booking Confirm";
require "header.php";
require "bookingclasses.php";

$lame=$users->get_ws_status();
echo $lame;
?>