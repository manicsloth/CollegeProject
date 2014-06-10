<?php
require 'core/init.php';
$admin->logged_out_protect();
$title="Add Walks";
require"header.php";
require "bookingclasses.php";
$insert = new dates;
$insert->insertDates($_POST['wdate'], $_POST['time'], $_POST['id'], $_POST['walkerlimit'], $_POST['polesavailable']);
$_SESSION['admin_notification'] = "Your date has been added to the system. For multiple event upload please use the csv upload facility.";
header( 'Location:/admin_home.php');



?>