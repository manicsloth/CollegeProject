<?php 
	require 'core/init.php';
	$users->logged_out_protect();
	$title="Account Information";
	
	//check for pole renting toggle in GET data
	if(isset($_GET['set_poles'])){
		switch ($_GET['set_poles']) {
			case 'y':
				$users->set_pole_renting("y");
				header('Location: account_info.php');
				exit;
				break;
			
			case 'n':
				$users->set_pole_renting("n");
				header('Location: account_info.php');
				exit;
				break;
		}
	}	
	
	//check for updated info in post, if exists then validate it.
	if(isset($_POST['submit'])){
		if(empty($_POST['email']) || empty($_POST['fname']) || empty($_POST['sname']) || empty($_POST['gender']) || empty($_POST['dob']) || empty($_POST['address']) || empty($_POST['town']) || empty($_POST['postcode']) || empty($_POST['contact_number']) || empty($_POST['contact_number'])){
			$errors[] = 'All fields marked with * are required.';
		}
		else{//validating user's input. 
			//EMAIL
			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
				$errors[] = 'Please enter a valid email address';
			}
			else if ($_POST['email'] !== $users->get_email()){//check if user has changed email. if so check it hasnt been taken
				if ($users->email_exists($_POST['email']) === true) {
					$errors[] = 'That email is already in use.';
				}
			}
			//FNAME
			if (strlen($_POST['fname']) > 30 || strlen($_POST['fname']) < 3){
				$errors[]= "First Name must be between 3 and 30 characters"; 
			}
			if(!ctype_alpha($_POST['fname'])){
				$errors[]= "First Name contains characters that are not letters";
			} 
			//SNAME
			if (strlen($_POST['sname']) > 30 || strlen($_POST['sname']) < 3){
				$errors[]= "Surname must be between 3 and 30 characters"; 
			}
			if(!ctype_alpha($_POST['sname'])){
				$errors[]= "Surname contains characters that are not letters";
			} 
			//DOB
			//check if dob is left blank (optional) and default to 01/01/0001
			if(empty($_POST['dob'])){
				$_POST['dob'] = "01/01/0001";
			}
			//explode date and format for parsing and storing in database
			$date_original = $_POST['dob'];
			$date = explode("/", $_POST['dob']);
			if (!checkdate($date['1'], $date['0'], $date['2'])){
				$errors[]="Please enter a valid date.";
			}
			if (strtotime("{$date['2']}-{$date['1']}-{$date['0']}") > time()) {
				$errors[]= "Please enter a date that is not in the future";
			}
			//CONTACT_NUMBER
			if (strlen($_POST['contact_number']) > 30 || strlen($_POST['contact_number']) < 5){
				$errors[]="Please enter a valid contact phone number between 5 and 30 numbers";
			}
			if (preg_match('/[^0-9]/', $_POST['contact_number'])){
				$errors[]= "Please enter only numbers in your contact phone number.";
			}
			//ALT CONTACT_NUMBER
			if (strlen($_POST['alt_contact_number']) > 30 || strlen($_POST['alt_contact_number']) < 5){
				$errors[]="Please enter a valid alternate contact phone number between 5 and 30 numbers";
			}
			if (preg_match('/[^0-9]/', $_POST['alt_contact_number'])){
				$errors[]= "Please enter only numbers in your alternate contact phone number.";
			}
		}
		if(empty($errors) === true){//if no errors, enter into database.
			//format date for database (yyyy-mm-dd)
			$_POST['dob'] = $date['2'] . "-" . $date['1'] . "-" . $date['0'];
			$reg = $users->update_account_info($_POST, $users->get_id());
			if ($reg == "yes"){
				//throw user back to member home page and display success message
				$_SESSION['notification'] = "We have successfully update your account information.";
				header('Location: member_home.php');
			}
			else{
				//throw user back to member home page and display error
				$_SESSION['notification'] = "Sorry there was an error changing your account information. Please try again later or contact us for support.";
				header('Location: member_home.php');
			}
			exit();
		}
	}
	require"header.php";
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript">
	$(function() {
	$.datepicker.setDefaults({
		changeYear: true,
		changeMonth: true,
		yearRange: "c-150:c",
		dateFormat: 'dd/mm/yy'
		});
	$( "#dob" ).datepicker();
	});	
</script>
	<div id="content">
		<h1>Your Account</h1>
		<p>Here you can see and modify the information we have stored about your account at Walk Kernow.</p>
		<a href="password_change.php"><button>Change Your Password</button></a>
		<a href="parq.php"><button>Update Physical Activity Readiness Questionnaire</button></a>
		<br />
		<div id="pole_renting">

		<p id='pole_renting'>At Walk Kernow we offer our customer the opportunity to rent walk poles from us to use on our events. You can select this option when you book onto a Nordic Walk. Alternatively use the button below to let us know if you would like us to automatically do this for you each time you book a Nordic Walk.</p>
		<?php
			switch ($users->get_pole_renting()) {
				case 'y':
					echo "<a href='account_info.php?set_poles=n'><button id='pole_renting'>Disable Automatic Pole Renting</button></a>";
					break;
				
				case 'n':
					echo "<a href='account_info.php?set_poles=y'><button id='pole_renting'>Enable Automatic Pole Renting</button></a>";
					break;
			}
		?>
		</div>
		<br />
		<br />
		<hr>
		<h3 id="update_form">
			Update your personal information.
		</h3>
		<br />
		<form action="account_info.php#update_form" method="post">
		<label for="email">
			Email (This is used to log in) *:
		</label>
		<input type="text" name="email" maxlength="400" value="<?php if(isset($_POST['email'])){echo htmlentities($_POST['email']);}else{echo htmlentities($users->get_email( ));} ?>" >
			<br />
			<label for="fname">
				First Name *:
			</label>
			<input type="text" name="fname" maxlength="30" value="<?php if(isset($_POST['fname'])){echo htmlentities($_POST['fname']);}else{echo htmlentities($users->get_fname());} ?>" >
			<br />
			<label for="sname">
				Surname *:
			</label>
			<input type="text" name="sname" maxlength="30" value="<?php if(isset($_POST['sname'])){echo htmlentities($_POST['sname']);}else{echo htmlentities($users->get_sname());} ?>" >
			<br />
			<label for="gender">
				Gender *:
			</label>
			<input type="radio" name="gender" value="m" <?php if(isset($_POST['gender']) && $_POST['gender'] == "m" || $users->get_gender() =="m"){echo("checked");}?>>Male
			<input type="radio" name="gender" value="f" <?php if(isset($_POST['gender']) && $_POST['gender'] == "f"  || $users->get_gender() =="f"){  echo("checked");}?>>Female
			<input type="radio" name="gender" value="na" <?php if(isset($_POST['gender']) && $_POST['gender'] == "na"  || $users->get_gender() =="na"){  echo("checked");}?>>N/a
			<br />
			<label for="dob">
				Date of Birth (Optional)<noscript>(Please format: dd/mm/yyyy)</noscript>:
			</label>
			<input type="text" name="dob" id="dob" maxlength="15" value="<?php if(isset($_POST['dob'])){echo htmlentities($_POST['dob']);}else{$dob=$users->get_dob();$dob_ex = (explode('-',$dob));$dob2 = $dob_ex[2] . '/' . $dob_ex[1] . '/' . $dob_ex[0];echo htmlentities($dob2);} ?>">
			<br />
			<label for="address">
				Address *:
			</label>
			<input type="text" name="address" maxlength="300" value="<?php if(isset($_POST['address'])){echo htmlentities($_POST['address']);}else{echo htmlentities($users->get_address());} ?>">
			<br />
			<label for="town">
				Town *:
			</label>
			<input type="text" name="town" maxlength="150" value="<?php if(isset($_POST['town'])){echo htmlentities($_POST['town']);}else{echo htmlentities($users->get_town());} ?>">
			<br />
			<label for="postcode">
				Postcode *:
			</label>
			<input type="text" name="postcode" maxlength="15" value="<?php if(isset($_POST['postcode'])){echo htmlentities($_POST['postcode']);}else{echo htmlentities($users->get_postcode());} ?>">
			<br />
			<label for="contact_number">
				Contact Number *:
			</label>
			<input type="text" name="contact_number" maxlength="30" value="<?php if(isset($_POST['contact_number'])){echo htmlentities($_POST['contact_number']);}else{echo htmlentities($users->get_contact_number());} ?>">
			<br />
			<label for="contact_number">
				Alternate Contact Number (e.g. a Mobile number) *:
			</label>
			<input type="text" name="alt_contact_number" maxlength="30" value="<?php if(isset($_POST['alt_contact_number'])){echo htmlentities($_POST['alt_contact_number']);}else{echo htmlentities($users->get_alt_contact_number());} ?>">
			<br />
			<input type="submit" name="submit" />
		</form>
		<?php
		//display any errors
		if(empty($errors) === false){
			echo '<p class="errors">' . implode('<br />', $errors) . '</p>';
		}
		//clear errors
		$errors = "";
		?>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>