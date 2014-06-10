<?php 

	require 'core/init.php';

	$users->logged_in_protect();



	//check for get data with password reset details. if missing kick to index.
	if(!isset($_GET['email']) || !isset($_GET['code'])){
		header('Location:index.php');
	}

	//check for POST data with submitted form, run function to update user password
	if(isset($_POST['password'])){
		extract($_GET);
		//check that email is registered and check that the code is correct. Error if not valid and stop page load.
		if ($users->email_exists($email) === false || $users->password_reset_validate($email, $code) === false) {
			echo 'Sorry, something went wrong and we couldn\'t recover your password. <a href="confirm_password_reset.php">Please try again</a>';
			exit;
		}

		extract($_POST);
		$error= "";
		//make sure both fields are complete
		if(empty($password) || empty($password2)){
			$error="You must fill out both password fields.";
		}

		elseif (strlen($password) <6){
			$error = 'Your password must be at least 6 characters';
		} 

		else if (strlen($password) >25){
			$error= 'Your password cannot be more than 25 characters long';
		}

		//make sure passwords match each other
		elseif ($password != $password2) {
			$error = "Your passwords do not match.";
		}

		//check security question answer is correct
		elseif($users->security_question($_GET['email'], "check", $answer) == FALSE){
			$error = "You answer to the security question is incorrect.";
		}

		//if no issues then run function to update users password.
		elseif(empty($error)){
			$users->password_reset_update($email, $password);
			header("Location:password_reset_success.php?password_success=true");
			exit;

		}

	}
	$title="Password Reset";
	require"header.php";

?>

<div id="content">

	<h1>

		Password Reset

	</h1>



	<?php 

	if(isset($error) && !empty($error)){

		echo "<span class='emp_red'> $error </span>";

	}

	?>

	<fieldset id="reset_password">

		<form action="password_reset.php?<?php echo "email=$_GET[email]&&code=$_GET[code]";?>" method="post" autocomplete="off">

			<p>

				Please enter your new password below.

			</p>

			<label for="password">

				Password: 

			</label>

			<br />

			<input type="password" maxlength="25" name="password" />

			<br />

			<label for="password2">

				Please confirm the password entered above:

			</label>

			<br />

			<input type="password" maxlength="25" name="password2" />

			<br />

			<label for="answer">

				Security Question:

			</label>

			<br />

			<?php 

			//get the security question id and parse it into human readable question

				$question_id = $users->security_question($_GET['email'], "get", "null");

				switch ($question_id) {

					case '1':

						echo "What is your mother's maiden name?";	

						break;

					case '2':

						echo "What is your first pet's name?";	

						break;

					case '3':

						echo "The name of a memorable place?";	

						break;

					case '4':

						echo "What is your favorite colour? ";	

						break;

					case '5':

						echo "What is the name of your hometown?";	

						break;				

				}



			?>

			<input type="text" maxlength="40" name="answer" />

			<input type="submit" value="Submit"/>

		</form>

	</fieldset>

	