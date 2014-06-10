<?php 
	require 'core/init.php';
	$admin->logged_in_protect();

	

	if(isset($_POST['email'])){
		if($admin->email_exists($_POST['email']) === true){
			$admin->password_reset_email($_POST['email']);
			header("Location:admin_password_reset_success.php?password_email=true");
			exit;
		}
		else{
			$error="The email supplied is not registered to an admin account.";
		}
	}
	$title="Admin Password Reset";
	require"header.php";
?>
<div id="content">

		<h1>
			Admin Password Reset
		</h1>
	<?php 
	if(isset($error) && !empty($error)){
		echo "<span class='emp_red'> $error </span>";
	}
	?>
	<fieldset id="reset_password">
		<form action="admin_confirm_password_reset.php" method="post">
			<p>
				Please enter the email that is registered to your account below.
			</p>
			<label for="email">
				Email: 
			</label>
			<input type="text" maxlength="400" name="email"  size="35" value="<?php if(isset($_POST['email'])){ echo $_POST['email'];}?>"/>
			<br />
			<input type="submit" value="Submit"/>
		</form>
	</fieldset>

</body>
</html>