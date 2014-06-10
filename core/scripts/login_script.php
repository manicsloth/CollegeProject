<?php

require '../init.php';

if (!empty($_POST)) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
 	$errors = "";
 	if(isset($_SESSION['failed_logins']) && $_SESSION['failed_logins'] > "4"){
		//recaptcha if failed to login 5 or more time
		require_once('../recaptchalib.php');
		$privatekey = "6LcPY_MSAAAAAPkyEFADP2lbFxfOE22lOZzqGzSG";
		$resp = recaptcha_check_answer ($privatekey,
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		$errors = "The reCAPTCHA wasn't entered correctly.";
		goto errors;
		} 
	}
 	//check data, if errors are found output them back to user 



	if (empty($email) === true || empty($password) === true) {

		$errors = 'Please enter both your email and password.';

		goto errors;

	} 



	//check if email and pass are correct

	$login = $users->login_credentials($email, $password);

	if ($login === false) { //if email or pass incorrect

		$errors = 'The email address or password supplied is invalid';

		goto errors;

	}



	//check account status

	if ($users->account_status($email) == 0) {

		$errors= "You need to activate your account. Please check your email inbox or spam folder. <br /><a href='login.php?email=$email&&resend=true'>Send activation link again.</a>";

		goto errors;

	}

	 if ($users->account_status($email) == -1) {

		$errors = 'This account has been disabled. Please contact us for assistance.';

	}



	errors:

	if(!empty($errors)){// if there are errors, return to login page. transfer errors in session var

		$_SESSION['member_login_errors'] = $errors;
 
		$_SESSION['member_login_email'] = $email;
		//increment failed login counter
		$_SESSION['failed_logins']++;
		header('Location: ../../login.php');

		exit();

	}

	else{ //if no problems with supplied data

		// email/password is correct

		session_regenerate_id(true);
		unset($_SESION['failed_logins']);
		$users->login($login);

		$_SESSION['users_ser'] = serialize($users);

		header('Location: ../../member_home.php');

		exit();

	}

}

else{

	header('Location: ../../login.php');

	exit();

	}

?>

