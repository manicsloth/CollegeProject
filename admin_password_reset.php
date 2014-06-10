<?php 
	require 'core/init.php';
	$admin->logged_in_protect();

	//check for get data with password reset details. if missing kick to index.
	if(!isset($_GET['email']) || !isset($_GET['code'])){
		header('Location:index.php');
	}
	//check for POST data with submitted form, run function to update user password
	if(isset($_POST['password'])){
		extract($_POST);
		//check that email is registered and check that the code is correct. Error if not valid and stop page load.
		extract($_GET);
		if ($admin->email_exists($email) === false || $admin->password_reset_validate($email, $code) === false) {
			echo 'Sorry, something went wrong and we couldn\'t recover your password. <a href="confirm_password_reset.php">Please try again</a>';
			exit;
		}
		//make sure both fields are complete
		elseif(empty($password) || empty($password2)){
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
			$error = "Your new passwords do not match.";
		}
		
		//if no issues then run function to update users password.
		else{
			$admin->password_reset_update($_GET['email'], $password);
			header("Location:admin_password_reset_success.php?password_success=true");
			exit;
		}
	}
	$title="Admin Password Reset";
	require"header.php";
?>
<div id="content">
	<div id="heading" >
			Admin Password Reset
		</h1>
	<?php 
		if(isset($error) && !empty($error)){
			echo "<span class='emp_red'> $error </span>";
		}
	?>
	<fieldset id="reset_password">
		<form action="admin_password_reset.php?<?php echo "email=$_GET[email]&&code=$_GET[code]";?>" method="post" autocomplete="off">
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
			<input type="submit" value="Submit"/>
		</form>
	</fieldset>
</body>
</html>