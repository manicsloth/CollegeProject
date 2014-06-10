<?php 
	require 'core/init.php';
	$users->logged_in_protect();



	if(isset($_POST['email'])){
		if($users->email_exists($_POST['email']) === true){
			$users->password_reset_email($_POST['email']);
			header("Location:password_reset_success.php?password_email=true");
			exit;
		}
		else{
			$error="Sorry that email does not exist in our database.";
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
		<form action="confirm_password_reset.php" method="post">
			<p>
				Please enter the email that is registered to your account below.
			</p>
			<label for="email">
				Email: 
			</label>
			<input type="text" maxlength="400" name="email" />
			<br />
			<input type="submit" value="Submit"/>
		</form>
	</fieldset>

