<?php
require 'core/init.php';
$users->logged_out_protect();
$title="Booking";
require"header.php";
require "bookingclasses.php";
$id = $users->get_id();
$book = new bookingTemp;
$attempt = $book->getAttempt($id);
$attempts = explode(":" , $attempt);
$attempts = count($attempts);
$member = new members;
$credits = $member->checkCredits($id,0);
$credits = $credits + $attempts;

echo "You do not have enough credits on your account for the walks that you are trying to book. Please buy more credits or reduce the number of walks you are trying to book this session. You currently have ",$credits," credits and you are trying to book ",$attempts," walks";
?>