<?php 
	require 'core/init.php';
	$users->logged_in_protect();
	$title="Register";
	if(isset($_GET['success'])){
		switch ($_GET['success']) {
			case 'true':
				echo 'Thank you for registering. Please check your email for instructions on how to activate your account.<a href="index.php"> Return.</a>';
				exit;
				break;
			
			case 'false':
				echo 'Sorry there was an error registering your account. Please try again later or contact us for support. <a href="index.php"> Return.</a>';
				exit;
				break;
		}
	}
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
	<h1>Register</h1>
	<br />
	<br />
	<form method="post" action="core/scripts/register_script.php" autocomplete="off">
		<label for="email">
			Email (This is also used to log in!) *:
		</label>
		<input type="text" name="email" maxlength="400" value="<?php if(isset($_SESSION['member_reg_email'])) echo htmlentities($_SESSION['member_reg_email']); ?>" >
		<br />
		<label for="password">
			Password *:
		</label>
		<input type="password" name="password" maxlength="25" />
		<br />
		<label for="question">
			Security Question (This will be used to confirm your identity should you need to reset your password) *:
		</label>
		<br />
		<select name="question" id="question">
			<option id="q0" value="0" 
			<?php if(isset($_SESSION['member_reg_question']) && $_SESSION['member_reg_question'] == "0") { echo("selected"); } ?>>
				-Please select one-
			</option>
			<option id="q1" value="1" <?php if(isset($_SESSION['member_reg_question']) && $_SESSION['member_reg_question'] == "1") { echo("selected"); } ?> >
				What is your mother's maiden name?
			</option>
		
			<option id="q2" value="2" <?php if(isset($_SESSION['member_reg_question']) && $_SESSION['member_reg_question'] == "2") { echo("selected"); } ?> >
				What is your first pet's name?
			</option>
			<option id="q3" value="3" <?php if(isset($_SESSION['member_reg_question']) && $_SESSION['member_reg_question'] == "3") { echo("selected"); } ?> >
				The name of a memorable place?
			</option>
			<option id="q4" value="4" <?php if(isset($_SESSION['member_reg_question']) && $_SESSION['member_reg_question'] == "4") { echo("selected"); } ?> >
				What is your favorite colour? 
			</option>
			<option id="q5" value="5" <?php if(isset($_SESSION['member_reg_question']) && $_SESSION['member_reg_question'] == "5") { echo("selected"); } ?> >
				What is the name of your hometown?
			</option>
		</select>
		<label for="answer">
			Answer: 
		</label>
		<input type="text" name="answer" maxlength="40" value="<?php if(isset($_SESSION['member_reg_answer'])) echo htmlentities($_SESSION['member_reg_answer']); ?>" />
		<br />
		<h2>
			Personal Information
		</h2>
		<label for="fname">
			First Name *:
		</label>
		<input type="text" name="fname" maxlength="30" value="<?php if(isset($_SESSION['member_reg_fname'])) echo htmlentities($_SESSION['member_reg_fname']); ?>" >
		<br />
		<label for="sname">
			Surname *:
		</label>
		<input type="text" name="sname" maxlength="30" value="<?php if(isset($_SESSION['member_reg_sname'])) echo htmlentities($_SESSION['member_reg_sname']); ?>" >
		<br />
		<label for="gender">
			Gender *:
		</label>
		<input type="radio" name="gender" value="m" <?php if(isset($_SESSION['member_reg_gender']) && $_SESSION['member_reg_gender'] == "m"){  echo("checked");}?>>Male
		<input type="radio" name="gender" value="f" <?php if(isset($_SESSION['member_reg_gender']) && $_SESSION['member_reg_gender'] == "f"){  echo("checked");}?>>Female
		<input type="radio" name="gender" value="na" <?php if(isset($_SESSION['member_reg_gender']) && $_SESSION['member_reg_gender'] == "na"){  echo("checked");}?>>N/a
		<br />
		<label for="dob">
			Date of Birth (Optional)<noscript>(Please format: dd/mm/yyyy)</noscript>:
		</label>
		<input type="text" name="dob" id="dob" maxlength="15" value="<?php if(isset($_SESSION['member_reg_dob'])) echo htmlentities($_SESSION['member_reg_dob']); ?>">
		<br />
		<label for="address">
			Address *:
		</label>
		<input type="text" name="address" maxlength="300" value="<?php if(isset($_SESSION['member_reg_address'])) echo htmlentities($_SESSION['member_reg_address']); ?>">
		<br />
		<label for="town">
			Town *:
		</label>
		<input type="text" name="town" maxlength="150" value="<?php if(isset($_SESSION['member_reg_town'])) echo htmlentities($_SESSION['member_reg_town']); ?>">
		<br />
		<label for="postcode">
			Postcode *:
		</label>
		<input type="text" name="postcode" maxlength="15" value="<?php if(isset($_SESSION['member_reg_postcode'])) echo htmlentities($_SESSION['member_reg_postcode']); ?>">
		<br />
		<label for="contact_number">
			Contact Number *:
		</label>
		<input type="text" name="contact_number" maxlength="30" value="<?php if(isset($_SESSION['member_reg_contact_number'])) echo htmlentities($_SESSION['member_reg_contact_number']); ?>">
		<br />
		<label for="contact_number">
			Alternate Number (e.g. Mobile) *:
		</label>
		<input type="text" name="alt_contact_number" maxlength="30" value="<?php if(isset($_SESSION['member_reg_alt_contact_number'])) echo htmlentities($_SESSION['member_reg_alt_contact_number']); ?>">
		<br />
		<hr />
		<label for ="refer">
			If a friend referred you please enter the email they signed up with below and they may be eligible for a discount.
		</label>
		<br />
		<input type="text" name="refer" maxlength="400" value="<?php if(isset($_SESSION['member_reg_refer'])){ echo htmlentities($_SESSION['member_reg_refer']);} elseif(isset($_GET['refer'])){ echo htmlentities($_GET['refer']);}?>"/>
		<br/>
		<label for="cards[]">
			Do you have membership cards for either of the bellow? If so we can offer you a 10% discount off a Beginners Workshop
		</label>
		<br />
		<input type="checkbox" name="cards[]" value="nat_trust" <?php if(isset($_SESSION['member_reg_cards']) && strpos($_SESSION['member_reg_cards'] , 'nat_trust')  !== false){echo "checked";}?> /> National Trust Membership Card
		<br>
		<input type="checkbox" name="cards[]" value="mussel" <?php if(isset($_SESSION['member_reg_cards']) && strpos($_SESSION['member_reg_cards'] , 'mussel')  !== false){echo "checked";}?> /> Mussel Card
		<br />
		<input type="submit" name="submit" />
		</form>
		<?php 
		// if there are errors, they would be displayed here.
		if(empty($_SESSION['member_reg_errors']) === false){
			echo '<p class="errors">' . implode('<br />', $_SESSION['member_reg_errors']) . '</p>';
		}
		//clear data stored in session variables
		unset($_SESSION['member_reg_errors']);
		unset($_SESSION['member_reg_email']);
		unset($_SESSION['member_reg_fname']);
		unset($_SESSION['member_reg_sname']);
		unset($_SESSION['member_reg_gender']);
		unset($_SESSION['member_reg_dob']);;
		unset($_SESSION['member_reg_address']);
		unset($_SESSION['member_reg_town']);
		unset($_SESSION['member_reg_postcode']);
		unset($_SESSION['member_reg_contact_number']);
		unset($_SESSION['member_reg_alt_contact_number']);
		unset($_SESSION['member_reg_refer']);
		unset($_SESSION['member_reg_cards']);
		?>
 	</div>
</body>
</html>