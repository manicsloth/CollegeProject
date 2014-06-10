<?php
	require 'core/init.php';
	$users->logged_in_protect(); //ensure user is not already logged in.
	//check for resend email command in get data
	if(isset($_GET['resend']) && isset($_GET['email'])){
		//check that email is valid, and then check that the account has not already been activated
		if($users->email_exists($_GET['email']) === false || $users->account_status($_GET['email']) != 0){
			echo "Sorry there was an error processing your request. Have you already activated your account? <a href='index.php'>Return</a>";
			exit;
		}
		else{
		//send user their code again.
			$users->resend_activation($_GET['email']);
			echo "We have sent you your activation link again. Please allow for up to 10 minutes for it to arrive";
			exit;
		}

	}
	$title="User Login";
	require"header.php";

?>	
	<div id="content">
		<h1>Login</h1>
		<p>
		Don't have an account? <a href="register.php">Register now</a>.
		<form method="post" action="core/scripts/login_script.php">
			<label for="email">
				Email:
			</label>
			<input type="text" name="email" maxlength="400" value="<?php if(isset($_SESSION['member_login_email'])) echo htmlentities($_SESSION['member_login_email']); ?>">
			<br />
			<label for="password">
				Password:
			</label>
			<input type="password" name="password" maxlength="25" />
			<br />
			<?php
				if(isset($_SESSION['failed_logins']) && $_SESSION['failed_logins'] > 4){
				//recaptcha
				  require_once('core/recaptchalib.php');
				  $publickey = "6LcPY_MSAAAAAF_jb5nsWTnMkyB-kJPuGkdl3X5l "; 
				  echo recaptcha_get_html($publickey);
				}
			  ?>
			<input type="submit" name="submit" value="Login"/>
		</form>
		<a href="confirm_password_reset.php">Forgot your username/password?</a>
	</p>
		<?php 
		if(empty($_SESSION['member_login_errors']) === false){
			echo '<p class="emp_red">', $_SESSION['member_login_errors'] . '</p>';
			$_SESSION['member_login_errors'] = "";
		}
		?>
	</div>
</body>
</html>