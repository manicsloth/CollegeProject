<?php
require '../init.php';
 
if (!empty($_POST)) {
 
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
 	$errors = "";

 	//check data, if errors are found output them back to user 

	if (empty($username) === true || empty($password) === true) {
		$errors = 'Sorry, but we need both your username and password.';
		goto errors;
	} 

	//check if username and pass are correct and retrieve admin user id if they are
	$id = $admin->login_credentials($username, $password);
	if ($id === false) { //if username or pass incorrect
		$errors = 'Sorry, the username address or password supplied is invalid';
		goto errors;
	}

	errors:
	if(!empty($errors)){// if there are errors, return to login page. transfer errors in session var
		$_SESSION['admin_login_errors'] = $errors;
		$_SESSION['admin_login_username'] = $username;
		header('Location: ../../admin_login.php');
		exit();
	}
	else{ //if no problems with supplied data
		// username/password is correct log admin in
		session_regenerate_id(true);
		$admin->login($id);
		$_SESSION['admin_ser'] = serialize($admin);
		header('Location: ../../admin_home.php');
		exit();
	}
}
else{
	header('Location: ../../admin_login.php');
	exit();
	}
?>
