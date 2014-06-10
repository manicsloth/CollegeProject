<?php
require '../init.php'; 
$users->logged_out_protect();
if(isset($_POST['submit'])) {

	//check that the sending IP / user agent match what was stored.
	if($_SESSION['user_ip'] != $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']){
		$errors[]="There was a problem dealing with your request. Please try again";
	}
	else{
	 	//if any of the fields are empty, display error.
		if(empty($_POST['q1'])  || empty($_POST['q2']) || empty($_POST['q3']) || empty($_POST['q4']) || empty($_POST['q5']) || empty($_POST['q6']) || empty($_POST['q7']) || empty($_POST['q8']) || empty($_POST['q9']) || empty($_POST['q10']) || empty($_POST['q11']) || empty($_POST['q12']) || empty($_POST['emerg_name']) || empty($_POST['emerg_prim_number']) || empty($_POST['emerg_alt_number'])){
			$errors['0'] = 'All fields are required.';
		}
		//check inputs that only appear under certain circumstance
		//q13
		if($_POST['q1'] == "yes" || $_POST['q2'] == "yes" || $_POST['q3'] == "yes" || $_POST['q4'] == "yes" || $_POST['q5'] == "yes" || $_POST['q6'] == "yes" || $_POST['q7'] == "yes"){
			if(empty($_POST['q13'])){
				$errors['0'] = 'All fields are required.';
			}
		}
		//q6.1
		if($_POST['q6'] == "yes"){
			if(empty($_POST['q6_1'])){
				$errors['0'] = 'All fields are required.';
			}
		}
		
		if($_POST['tc'] == "no" || empty($_POST['tc'])){
				$errors[] = 'You must accept the terms and conditions to continue';
		}
	}
		if(empty($errors) === true){
	//validate input if no errors so far
		
		//emerg_prim_number & emerg_alt_number
		if (strlen($_POST['emerg_prim_number']) > 30 || strlen($_POST['emerg_prim_number']) < 5 || strlen($_POST['emerg_alt_number']) > 30 || strlen($_POST['emerg_alt_number']) < 5){
			$errors[]="Please enter valid emergency contact phone numbers between 5 and 30 numbers";
		}
		if (preg_match('/[^0-9]/', $_POST['emerg_prim_number']) || preg_match('/[^0-9]/', $_POST['emerg_alt_number'])){
			$errors[]= "Please enter only numbers in your emergency contact phone numbers.";
		}
	
	}
	if(empty($errors) === true){//if no errors, enter into database.
		$submit = $users->hs_form_submit($_POST, $users->get_id());
		if ($submit == "yes"){
			//throw user back to hs form page and display success message
			header('Location: ../../parq.php?success=true');
		}
		else{
			//throw user back to hs form page and display error message
			header('Location: ../../parq.php?success=false');
		}
		exit();
	}
	else{//if there are errors, return to hs form to display them.
		$_SESSION['member_hs_errors'] = $errors;
		//store post data in session variables to be loaded back into the form
		foreach ($_POST as $key => $value) {
			$_SESSION["member_hs_$key"] = $value;
		}
		header('Location: ../../parq.php');
	}
}
?>