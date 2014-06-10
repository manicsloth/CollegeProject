<?php
require 'functions.php';
//$date = date("Y-m-d");
$date = $_GET['date'];
$nordic_db=db_connect();
$query = $nordic_db->prepare("select `telephone`, `location`, `wtime` from `bookings` where `wdate` = ? ");
$query->execute(array($date));
while($data= $query ->fetch(PDO::FETCH_ASSOC)) {
	$output[] = $data;
}
print(json_encode($output));

?>