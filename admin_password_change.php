<?php 
	require 'core/init.php';
	$admin->logged_out_protect();

	//check for POST data with submitted form, run function to update user password
	if(isset($_POST['password'])){
		$username = $admin->get_username();
		extract($_POST);
		//make sure all fields are complete
		if(empty($password) || empty($password2) || empty($password_old)){
			$error="You must fill out all three password fields.";
		}
		//check that the old password supplied is correct
		elseif($admin->login_credentials($username, $password_old) === false){
			$error = 'Your (old) password was incorrect.';
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
		//if no issues then run function to update admin users password.
		else{
			$admin->password_change($username, $password);
			header("Location:admin_password_reset_success.php?password_change_success=true");
			exit;
		}
	}
	$title="Admin Password Reset";
	require"header.php";
?>
<div id="content">
	<h1>
		Admin Password Change
	</h1>
	<?php 
		if(isset($error) && !empty($error)){
			echo "<span class='emp_red'> $error </span>";
		}
	?>
	<fieldset id="reset_password">
		<form action="admin_password_change.php" method="post" autocomplete="off">
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
		<a href="home.php"><button>Cancel</button></a>
	</fieldset>
</div>

