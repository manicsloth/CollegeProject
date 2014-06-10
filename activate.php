<?php 
	require 'core/init.php';
	$users->logged_in_protect();

	//If success message in get, display success message to user.
	if (isset($_GET['success']) === true && empty ($_GET['success']) === true) {
		echo "Thank you, we've activated your account. You can now log in. Click <a href='login.php'>here to goto the login page</a>";
	} 
	//Otherwise if account activation data is found, run script file.            
	else if (isset ($_GET['email'], $_GET['email_code']) === true) {

		$_SESSION['activate_email']	=trim($_GET['email']);
		$_SESSION['activate_email_code']=trim($_GET['email_code']);
		header('Location: core/scripts/activate_script.php');
		exit();
	} else {
		header('Location: index.php');
		exit();
	}
?>
