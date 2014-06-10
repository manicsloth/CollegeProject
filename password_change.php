<?php 
	require 'core/init.php';
	$users->logged_out_protect();


	//check for POST data with submitted form, run function to update user password
	if(isset($_POST['password'])){
		$email = $users->get_email();
		extract($_POST);
		//make sure all fields are complete
		if(empty($password) || empty($password2) || empty($password_old)){
			$error="You must fill out all three password fields.";
		}
		elseif (strlen($password) <6){
			$error = 'Your new password must be at least 6 characters.';
		} 
		elseif (strlen($password) >25){
			$error= 'Your new password cannot be more than 25 characters long.';
		}
		//make sure passwords match each other
		elseif ($password != $password2) {
			$error = "Your new passwords do not match.";
		}
		//check that the old password supplied is correct
		elseif($users->login_credentials($email, $password_old) === false){
			$error = 'Your (old) password was incorrect.';
		}
		//if no issues then run function to update users password.
		else{
			$users->password_reset_update($email, $password);
			$_SESSION['notification'] = "Password successfully changed.";
			header("Location:member_home.php");
			exit;
		}
	}
		$title="Change Password";
	require"header.php";
?>
<div id="content">
	<h1>
		Password Change
	</h1>
	<br />
	<?php 
		if(isset($error) && !empty($error)){
			echo "<span class='emp_red'> $error </span>";
		}
	?>
	<fieldset id="reset_password">
		<form action="password_change.php" method="post" autocomplete="off">
			<label for="password_old">
				Old Password: 
			</label>
			<br />
			<input type="password" maxlength="25" name="password_old" />
			<br />
			<label for="password">
				Password: 
			</label>
			<br />
			<input type="password" maxlength="25" name="password" />
			<br />
			<label for="password2">
				Please confirm your new password:
			</label>
			<br />
			<input type="password" maxlength="25" name="password2" />
			<br />
			<input type="submit" value="Submit"/>
		</form>
		<a href="member_home.php"><button>Cancel</button></a>
	</fieldset>

