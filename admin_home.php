<?php 
	require 'core/init.php';
	$admin->logged_out_protect();

	$title="Admin Home";
	require"header.php";

?>
<div id="content">
		<h1>
			<?php 
				echo "Welcome, " . ucfirst(htmlentities($admin->get_username()));
			?>
		</h1>
		<a href="admin_password_change.php">
			<button id="password_change">
				Change Password
			</button>
		</a>
	<?php
		//NOTIFICATION BOX
		if(isset($_SESSION['admin_notification']) && !empty($_SESSION['admin_notification'])){
			echo "<p id='notification'>" . $_SESSION['admin_notification'] . "</p>";
			unset($_SESSION['admin_notification']);
		}
	?>
	<h2>
		Please click below to begin.
	</h2>
	<div id="menu_buttons">
		<a href="members.php"><button> Members </button></a>
		<a href="admins.php"><button> Admin Users </button></a>
		<a href="#"><button> Place Holder </button></a>
		<a href="#"><button> Place Holder </button></a>
	</div>
</div>
</body>
</html>