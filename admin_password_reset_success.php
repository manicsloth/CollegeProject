<?php 

	//success page for password reset



	if(isset($_GET['password_email'])){

		echo "We have emailed you a link to reset your password. Please allow up to 10 minutes for it to arrive. If you still have not received it make sure to check you junk mail box just in case or try again. <a href='admin_login.php'> Return</a>";

		exit;

	}

	elseif(isset($_GET['password_success'])){

		echo "We have successfully update your password. Click below to go to the login page <br /><a href='admin_login.php'><button> Login </button></a>";

		exit;

	}

	elseif(isset($_GET['password_change_success'])){

		echo "Your password has been changed. Click <a href='admin_home.php'> here to return </a>";

		exit;

	}

	else{

		header("Location:index.php");

		exit;

	}

?>