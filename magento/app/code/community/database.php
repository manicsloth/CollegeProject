<?php 
// db connection for admin tables
$config = array(
	'host'		=> 'localhost',
	'username'	=> 'manic_sloth',
	'password'	=> '123fistandantilus!',
	'dbname'	=> 'manic_nordic'
);
//connecting to the database by supplying required parameters
$db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
 
//Setting the error mode of our db object, which is very important for debugging.
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//dm connection for member tables
$config = array(
	'host'		=> 'localhost',
	'username'	=> 'manic_sloth',
	'password'	=> '123fistandantilus!',
	'dbname'	=> 'manic_members'
);
//connecting to the database by supplying required parameters
$db_members = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
 
//Setting the error mode of our db object, which is very important for debugging.
$db_members->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
