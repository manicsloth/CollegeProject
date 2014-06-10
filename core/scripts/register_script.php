<?php 
require '../init.php';
$users->logged_in_protect();
// if form is submitted deal with it, otherwise return to registration page
if (isset($_POST['submit'])) {


	//format membership cards checkbox option into single string, separate by pipe. Done here to avoid output errors if they have left other fields empty.
	if(!empty($_POST['cards'])){
		$cards = "";
		foreach ($_POST['cards'] as $x) {
			$cards.= "$x|";
		}
		$_POST['cards'] = $cards;
	}

 	//if any of the fields are empty, display error.
	if(empty($_POST['email'])  || empty($_POST['password']) || empty($_POST['question']) || empty($_POST['answer']) || empty($_POST['fname']) || empty($_POST['sname']) || empty($_POST['gender']) || empty($_POST['address']) || empty($_POST['town']) || empty($_POST['postcode']) || empty($_POST['contact_number']) || empty($_POST['alt_contact_number'])){
		$errors[] = 'All fields marked with a * are required.';
	}
	else{//validating user's input. Uses functions from User class
	        	//PASSWORD
		if (strlen($_POST['password']) <6){
			$errors[] = 'Your password must be at least 6 characters';
		} 
		else if (strlen($_POST['password']) >25){
			$errors[] = 'Your password cannot be more than 25 characters long';
		}
		//SECURITY QUESTION & ANSWER
		if($_POST['question'] == "q0"){
			$errors[] = 'You need to select a security question and provide an answer.';
		}



		//EMAIL
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'Please enter a valid email address';
		}
		else if ($users->email_exists($_POST['email']) === true) {
			$errors[] = 'That email is already in use.';
		}

		//FNAME
		if (strlen($_POST['fname']) > 30 || strlen($_POST['fname']) < 3){
			$errors[]= "First Name must be between 3 and 30 characters"; 
		}
		if(!ctype_alpha($_POST['fname'])){
			$errors[]= "First Name contains characters that are not letters";
		} 

		//SNAME
		if (strlen($_POST['sname']) > 30 || strlen($_POST['sname']) < 3){
			$errors[]= "Surname must be between 3 and 30 characters"; 
		}
		if(!ctype_alpha($_POST['sname'])){
			$errors[]= "Surname contains characters that are not letters";
		} 

		//DOB
		//check if dob is left blank (optional) and default to 01/01/0001
		if(empty($_POST['dob'])){
			$_POST['dob'] = "01/01/0001";
		}
		//explode date and format for parsing and storing in database
		$date_original = $_POST['dob'];
		$date = explode("/", $_POST['dob']);
		if (!checkdate($date['1'], $date['0'], $date['2'])){
			$errors[]="Please enter a valid date.";
		}
		if (strtotime("{$date['2']}-{$date['1']}-{$date['0']}") > time()) {
			$errors[]= "Please enter a date that is not in the future";
		}

		//CONTACT_NUMBER
		if (strlen($_POST['contact_number']) > 30 || strlen($_POST['contact_number']) < 5){
			$errors[]="Please enter a valid contact phone number between 5 and 30 numbers";
		}
		if (preg_match('/[^0-9]/', $_POST['contact_number'])){
			$errors[]= "Please enter only numbers in your contact phone number.";
		}
		//ALT CONTACT_NUMBER
		if (strlen($_POST['alt_contact_number']) > 30 || strlen($_POST['alt_contact_number']) < 5){
			$errors[]="Please enter a valid alternate contact phone number between 5 and 30 numbers";
		}
		if (preg_match('/[^0-9]/', $_POST['alt_contact_number'])){
			$errors[]= "Please enter only numbers in your alternate contact phone number.";
		}
		//CARDS
		if(empty($_POST['cards'])){
			$_POST['cards'] = "na";
		}

	}
	if(empty($errors) === true){//if no errors, enter into database.
		$email  = ($_POST['email']);
		$password = $_POST['password'];
		//format date for database (yyyy-mm-dd)
		$_POST['dob'] = $date['2'] . "-" . $date['1'] . "-" . $date['0'];
		$reg = $users->register($_POST);
		
		if ($reg == "yes"){
			//throw user back to register page and display success message
			header('Location: ../../register.php?success=true');
		}
		else{
			//throw user back to register page and display error message
			header('Location: ../../register.php?success=false');
		}
		exit();
	}
	else{//if there are errors, return to registration form to display them.
		$_SESSION['member_reg_errors'] = $errors;

		//store post data in session variables to be loaded back into the form
		$_SESSION['member_reg_email'] = $_POST['email'];
		$_SESSION['member_reg_question'] = $_POST['question'];
		$_SESSION['member_reg_answer'] = $_POST['answer'];
		$_SESSION['member_reg_fname'] = $_POST['fname'];
		$_SESSION['member_reg_sname'] = $_POST['sname'];
		$_SESSION['member_reg_gender'] = $_POST['gender'];
		$_SESSION['member_reg_dob'] = $_POST['dob'];
		$_SESSION['member_reg_address'] = $_POST['address'];
		$_SESSION['member_reg_town'] = $_POST['town'];
		$_SESSION['member_reg_postcode'] = $_POST['postcode'];
		$_SESSION['member_reg_contact_number'] = $_POST['contact_number'];
		$_SESSION['member_reg_alt_contact_number'] = $_POST['alt_contact_number'];
		$_SESSION['member_reg_refer'] = $_POST['refer'];
		$_SESSION['member_reg_cards'] = $_POST['cards'];
		header('Location: ../../register.php');
	}
}
else{//if no post data, send user back to registration from.
	header('Location: ../../register.php');
}

?>
