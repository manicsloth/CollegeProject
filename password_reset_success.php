<?php 
	//success page for password reset

	if(isset($_GET['password_email'])){
		echo "We have emailed you a link to reset your password. Please allow up to 10 minutes for it to arrive. If you still have not received it make sure to check you junk mail box just in case or try again. If you still cannot reset your password please contact us for support.";
		exit;
	}
	elseif(isset($_GET['password_success'])){
		echo "We have successfully update your password. Click below to go to the login page <br /><a href='login.php'><button> Login </button></a>";
		exit;
	}
	else{
		header("Location:index.php");
		exit;
	}
?>