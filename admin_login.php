<?php
	require 'core/init.php';
	$admin->logged_in_protect(); //ensure user is not already logged in.
	//admin login front page. login process handled by login_script.php
	$title="Admin Login";
	require"header.php";
?>
<div id="content">
<fieldset id="loginform">
	<legend>
		Admin Login
	</legend>
		<form method="post" action="core/scripts/admin_login_script.php">
		<label for="name"> 
			Username:
		</label>
		<br />
		<input type="text" name="username" id="username" maxlength="400" value="<?php if(isset($_SESSION['admin_login_username'])) echo htmlentities($_SESSION['admin_login_username']); ?>">
		<br />
		<label>
			Password:
		</label>
		<br />
		<input type="password" name="password" id="password" size="34"< />
		<br />
		<input type = "checkbox" name="remeber" id="remeber">
		<label for="remeber">
			Remember me
		</label>
		<br />
		<a href="admin_confirm_password_reset.php">Forgot your username/password?</a>
		<hr />
		<?php 
		if(empty($_SESSION['admin_login_errors']) === false){
			echo '<p class="emp_red">', $_SESSION['admin_login_errors'] . '</p>';
			$_SESSION['admin_login_errors'] = "";
		}
		?>
		<input type="submit" value="Login" id="submit" />
	</form>
</fieldset>

<p id="warning">
	This page is for administration use only, if you do not belong here please
	<a href="http://www.walkkernow.co.uk/">
		click this here
	</a>
	to return to the Walk Kernow website.
	
</p>
</div>

</body>
</html>