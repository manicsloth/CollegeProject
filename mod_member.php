<?php
	require 'core/init.php';

	//kick user to login page if not logged in.
	$admin->logged_out_protect();
	//check permissions
	$perm_check = $admin->permission_check( 'M');
	if($perm_check == "no"){
		echo"<h2>Sorry, you do not have sufficient permissions to view this page.</h2> ";
		echo"<br /><button  onClick='history.go(-1);return true;'> Go Back </button>";
		exit;
	}
	//load current member data from database
	$member_data= $member->get_member_data($_GET['id']); 	

	if(isset($_POST['email'])){//if update form has been posted to this page, then run function to update
		//VALIDATE DATA
		if(empty($_POST['email']) || empty($_POST['fname']) || empty($_POST['sname']) || empty($_POST['gender']) || empty($_POST['address']) || empty($_POST['town']) || empty($_POST['postcode']) || empty($_POST['contact_number'])){
			$errors[] = 'All fields are required.';
		}
		else{//validating user's input. 

			//EMAIL
			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
				$errors[] = 'Please enter a valid email address';
			}
			else if ($_POST['email'] !== $member_data['email']){//check if user has changed email. if so check it hasn't been taken
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
				$errors[]= "Please enter only numbers in contact phone number.";
			}
		}
		if(empty($errors)){//if no errors, enter into database.
			//format date for database (yyyy-mm-dd)
			$_POST['dob'] = $date['2'] . "-" . $date['1'] . "-" . $date['0'];
			$member->update_member_data($_POST, $_GET['id']);
			header("Location:view_member.php?id=$_GET[id]");
			exit();

		}
	}
	//check for get data with member id. if it is missing then redirect to member list.
	if(!isset($_GET['id']) || empty($_GET['id'])){
		header("Location:members.php");
		exit;
	}
	//check for delete in get, if present run function to delete member account.
	if(isset($_GET['delete'])){
		$member->delete_member($_GET['id']);
		header("Location:members.php");
		exit;
	}

	$title="Modify Member";
	require"header.php";
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
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

<h1>
	Modify Member Data
</h1>
<a href="view_member.php?id=<?php echo $member_data['id']; ?>"><button id="return_button">Return to Member Page</button></a>
					
<form id="mod_member_form" action="mod_member.php?id=<?php echo $member_data['id']; ?>" method="post" >
	<table id="member_data">
		<tr>
			<th>
				ID
			</th>
			<td>
				<?php echo $member_data['id']; ?>
			</td>
		</tr>
		<tr>
			<th>
				First Name
			</th>
			<td>
				<input type="text" name="fname" id="fname" maxlength="25" value="<?php  if(isset($_POST['fname'])){echo htmlentities($_POST['fname']);}else{echo htmlentities($member_data['fname']);} ?>" />
			</td>
		</tr>
		<tr>
			<th>
				Surname
			</th>
			<td>
				<input type="text" name="sname" id="sname" maxlength="25" value="<?php  if(isset($_POST['sname'])){echo htmlentities($_POST['sname']);}else{echo htmlentities($member_data['sname']);} ?>" />
			</td>
		</tr>
		<tr>
			<th>
				Gender:
			</th>
			<td>
			<input type="radio" name="gender" value="m" <?php if(isset($_POST['gender']) && $_POST['gender'] == "m" || $member_data['gender'] =="m"){echo("checked");}?>>Male
			
			<input type="radio" name="gender" value="f" <?php if(isset($_POST['gender']) && $_POST['gender'] == "f"  || $member_data['gender'] =="f"){  echo("checked");}?>>Female
			
			<input type="radio" name="gender" value="na" <?php if(isset($_POST['gender']) && $_POST['gender'] == "na"  || $member_data['gender'] =="na"){  echo("checked");}?>>N/a
			
		</tr>		
		<tr>
			<th>
				Date of Birth (Optional)<noscript><br />(Please format: dd/mm/yyyy)</noscript>
			</th>
			<td>
				<input type="text" name="dob" id="dob" value="<?php  if(isset($_POST['dob'])){echo htmlentities($_POST['dob']);}else{$dob_ex = (explode('-',$member_data['dob']));$dob2 = $dob_ex[2] . '/' . $dob_ex[1] . '/' . $dob_ex[0];echo htmlentities($dob2);} ?>" />
			</td>
		</tr>
		<tr>
			<th>
				Street Address
			</th>
			<td>
				<textarea name="address" id="address" maxlength="300" ><?php  if(isset($_POST['address'])){echo htmlentities($_POST['address']);}else{echo htmlentities($member_data['address']);} ?></textarea>
			</td>
		</tr>
		<tr>
			<th>
				Town
			</th>
			<td>
				<input type="text" name="town" id="town" maxlength="30" value="<?php  if(isset($_POST['town'])){echo htmlentities($_POST['town']);}else{echo htmlentities($member_data['town']);} ?>" />
			</td>
		</tr>
		<tr>
			<th>
				Postcode
			</th>
			<td>
				<input type="text" name="postcode" id="postcode" maxlength="10" value="<?php  if(isset($_POST['postcode'])){echo htmlentities($_POST['postcode']);}else{echo htmlentities($member_data['postcode']);} ?>" />
			</td>
		</tr>
		<tr>
			<th>
				Contact Number
			</th>
			<td>
				<input type="text" name="contact_number" id="contact_number" maxlength="25" value="<?php  if(isset($_POST['contact_number'])){echo htmlentities($_POST['contact_number']);}else{echo htmlentities($member_data['contact_number']);} ?>" />
			</td>
		</tr>
		<tr>
			<th>
				Email
			</th>
			<td>
				<input type="text" name="email" id="email" maxlength="350" value="<?php if(isset($_POST['email'])){echo htmlentities($_POST['email']);}else{echo htmlentities($member_data['email']);} ?>" />
			</td>
		</tr>
		<tr>
			<th>
				Credits
			</th>
			<td>
				<input type="text" name="credits" id="credits" maxlength="11" value="<?php  if(isset($_POST['credits'])){echo htmlentities($_POST['credits']);}else{echo htmlentities($member_data['credits']);} ?>" />
			</td>
		</tr>
	</table>
	<input type="submit" value="Submit" />
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