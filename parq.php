<?php 

	require 'core/init.php';
	$users->logged_out_protect();
	$title="Physical Activity Readiness Questionnaire";
	if(isset($_GET['success'])){
		switch ($_GET['success']) {
			case 'true':
				echo 'Thank you for submitting your Physical Activity Readiness Questionnaire. It will be reviewed by an Instructor as soon as possible and you will receive an email shortly.<a href="member_home.php"> Return.</a>';
				exit;
				break;
			case 'false':
				echo 'Sorry there was an error submitting your Physical Activity Readiness Questionnaire. Please try again later or contact us for support. <a href="member_home.php"> Return.</a>';
				exit;
				break;
		}
	}	

require"header.php";

//Store users Ip/User agent for security measures.
$_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];


?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
function q6Check() {
	if (document.getElementById('q6yes').checked) {
	document.getElementById('q6_1').style.display = 'table-row';
	}
	else document.getElementById('q6_1').style.display = 'none';	
	}

$(q6Check);
</script>
<script>
function q13Check() {
var count = document.getElementsByClassName('radioListyes');
document.getElementById('q13').style.display = 'none';	
	for (var i=0;i<count.length; i++) {
		if(document.getElementsByClassName("radioListyes")[i].checked){
			document.getElementById('q13').style.display = 'table-row';
		}
	}
}
$(q13Check);
</script>
<div id="content">	
	<h1>
		Walk Kernow Physical Activity Readiness Questionnaire
	</h1>
	<?php
		$change_test = $users->get_hs("q1");
		if(!empty($change_test)){
			echo '<p class="emp_red">Please note that if you change your Physical Activity Readiness Questionnaire form an Instructor will need review it again before you can join our walks.</p>';
		}
	?>
	<p>* Required</p>
	<?php
		// if there are errors, they would be displayed here.
		if(empty($_SESSION['member_hs_errors']) === false){
			echo '<p class="errors">' . implode('<br />', $_SESSION['member_hs_errors'] ) . '</p>';
		}
	?>

	<form method="post" action="core/scripts/parq_script.php" onload="javascript:q6Check();q13Check();">
	<h3>
		Indicate yes or no to each of the questions below. If you indicate "yes" you may need your Doctor's consent before you participate in Nordic Walking. *
	</h3>
	<table>
		<tr>
			<td>
				<th>
					YES
				</th>
			</td>
			<td>
				<th>
					NO
				</th>
			</td>
		</tr>

		<tr>
			<td>
				Has a doctor ever said that you have a heart condition and recommended only medically supervised activity? *
			</td>
			<td>
				<input type="radio" name="q1" class="radioListyes" onclick="javascript:q13Check();" value="yes" <?php if(isset($_SESSION['member_hs_q1']) && $_SESSION['member_hs_q1'] == "yes"){ echo 'checked';}elseif($users->get_hs('q1') == "yes" && $_SESSION['member_hs_q1'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q1" onclick="javascript:q13Check();" value="no" <?php if(isset($_SESSION['member_hs_q1']) && $_SESSION['member_hs_q1'] == "no"){ echo 'checked';}elseif($users->get_hs('q1') == "no" && $_SESSION['member_hs_q1'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
		<tr>
			<td>
				Do you have chest pain brought on by physical activity? *
			</td>
			<td>
				<input type="radio" name="q2" class="radioListyes" onclick="javascript:q13Check();" value="yes" <?php if(isset($_SESSION['member_hs_q2']) && $_SESSION['member_hs_q2'] == "yes"){ echo 'checked';}elseif($users->get_hs('q2') == "yes" && $_SESSION['member_hs_q2'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q2" onclick="javascript:q13Check();" value="no" <?php if(isset($_SESSION['member_hs_q2']) && $_SESSION['member_hs_q2'] == "no"){ echo 'checked';}elseif($users->get_hs('q2') == "no" && $_SESSION['member_hs_q2'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
		<tr>
			<td>
				Have you developed chest pain in the past month? *
			</td>
			<td>
				<input type="radio" name="q3" class="radioListyes" onclick="javascript:q13Check();" value="yes" <?php if(isset($_SESSION['member_hs_q3']) && $_SESSION['member_hs_q3'] == "yes"){ echo 'checked';}elseif($users->get_hs('q3') == "yes" && $_SESSION['member_hs_q3'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q3" onclick="javascript:q13Check();" value="no" <?php if(isset($_SESSION['member_hs_q3']) && $_SESSION['member_hs_q3'] == "no"){ echo 'checked';}elseif($users->get_hs('q3') == "no" && $_SESSION['member_hs_q3'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
		<tr>
			<td>
				Do you lose consciousness or fall over as a result of dizziness? *
			</td>
			<td>
				<input type="radio" name="q4" class="radioListyes" onclick="javascript:q13Check();" value="yes" <?php if(isset($_SESSION['member_hs_q4']) && $_SESSION['member_hs_q4'] == "yes"){ echo 'checked';}elseif($users->get_hs('q4') == "yes" && $_SESSION['member_hs_q4'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q4" onclick="javascript:q13Check();" value="no" <?php if(isset($_SESSION['member_hs_q4']) && $_SESSION['member_hs_q4'] == "no"){ echo 'checked';}elseif($users->get_hs('q4') == "no" && $_SESSION['member_hs_q4'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
		<tr>
			<td>
				Do you have a bone or joint problem that could be aggravated by physical activity? *
			</td>
			<td>
				<input type="radio" name="q5" class="radioListyes" onclick="javascript:q13Check();" value="yes" <?php if(isset($_SESSION['member_hs_q5']) && $_SESSION['member_hs_q5'] == "yes"){ echo 'checked';}elseif($users->get_hs('q5') == "yes" && $_SESSION['member_hs_q5'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q5" onclick="javascript:q13Check();" value="no" <?php if(isset($_SESSION['member_hs_q5']) && $_SESSION['member_hs_q5'] == "no"){ echo 'checked';}elseif($users->get_hs('q5') == "no" && $_SESSION['member_hs_q5'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
		<tr>
			<td>
				Has a doctor ever recommended medication for your blood pressure or a heart condition? *
			</td>
			<td>
				<input type="radio" id="q6yes" name="q6" class="radioListyes" onclick="javascript:q6Check();q13Check();" value="yes" <?php if(isset($_SESSION['member_hs_q6']) && $_SESSION['member_hs_q6'] == "yes"){ echo 'checked';}elseif($users->get_hs('q6') == "yes" && $_SESSION['member_hs_q6'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q6" onclick="javascript:q6Check();q13Check();" value="no" <?php if(isset($_SESSION['member_hs_q6']) && $_SESSION['member_hs_q6'] == "no"){ echo 'checked';}elseif($users->get_hs('q6') == "no" && $_SESSION['member_hs_q6'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
		<tr id="q6_1">
			<td>
				<strong>If you answered yes to the previous question, is the issue is under control and monitored? *</strong>
			</td>
			<td>
				<input type="radio"  name="q6_1" value="yes" <?php if(isset($_SESSION['member_hs_q6_1']) && $_SESSION['member_hs_q6_1'] == "yes"){ echo 'checked';}elseif($users->get_hs('q6_1') == "yes" && $_SESSION['member_hs_q6_1'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q6_1" value="no" <?php if(isset($_SESSION['member_hs_q6_1']) && $_SESSION['member_hs_q6_1'] == "no"){ echo 'checked';}elseif($users->get_hs('q6_1') == "no" && $_SESSION['member_hs_q6_1'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
		<tr>
			<td>
				Are you aware through your own experience or from a doctor's advice or any other reason why you should not exercise without medical supervision? *
			</td>
			<td>
				<input type="radio" name="q7" class="radioListyes" onclick="javascript:q13Check();" value="yes" <?php if(isset($_SESSION['member_hs_q7']) && $_SESSION['member_hs_q7'] == "yes"){ echo 'checked';}elseif($users->get_hs('q7') == "yes" && $_SESSION['member_hs_q7'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q7" onclick="javascript:q13Check();" value="no" <?php if(isset($_SESSION['member_hs_q7']) && $_SESSION['member_hs_q7'] == "no"){ echo 'checked';}elseif($users->get_hs('q7') == "no" && $_SESSION['member_hs_q7'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
		<tr id="q13">
			<td>
				<strong>If you answered 'Yes' to one of the above question, we are required to ask if a Doctor or medical professional has agreed that you are able to Nordic Walk without medical supervision? *</strong>
			</td>
			<td>
				<input type="radio"  name="q13" value="yes" <?php if(isset($_SESSION['member_hs_q13']) && $_SESSION['member_hs_q13'] == "yes"){ echo 'checked';}elseif($users->get_hs('q13') == "yes" && $_SESSION['member_hs_q13'] !== "no"){ echo 'checked';}?>>
			</td>
			<td>
				<input type="radio" name="q13" value="no" <?php if(isset($_SESSION['member_hs_q13']) && $_SESSION['member_hs_q13'] == "no"){ echo 'checked';}elseif($users->get_hs('q13') == "no" && $_SESSION['member_hs_q13'] !== "yes"){ echo 'checked';}?>>
			</td>
		</tr>
	</table>

	<hr />
	<h3>
		Please outline below any other relevant information that might affect your ability to exercise.
	</h3>
	<label for="q8">
		Pre-existing medical conditions *
	</label>
	<br />
	<textarea name="q8"><?php if(isset($_SESSION['member_hs_q8'])){echo htmlentities($_SESSION['member_hs_q8']);}else{echo htmlentities($users->get_hs('q8'));}?></textarea>
	<br />

	<label for="q9">
		Current Medication *
	</label>
	<br />
	<textarea name="q9"><?php if(isset($_SESSION['member_hs_q9'])){echo htmlentities($_SESSION['member_hs_q9']);}else{echo htmlentities($users->get_hs('q9'));}?></textarea>
	<br />

	<label for="q10">
		Known Allergies *
	</label>
	<br />
	<textarea name="q10"><?php if(isset($_SESSION['member_hs_q10'])){echo htmlentities($_SESSION['member_hs_q10']);}else{echo htmlentities($users->get_hs('q10'));}?></textarea>
	
	<h3>
		I realise that my body's reaction to exercise is not totally predictable. Should I develop a condition that affects my ability to exercise, I will inform my instructor immediately and stop exercising if necessary. I take full responsibility for monitoring my own physical condition at all times.
	</h3>
	<label for ="q11">
		I am happy for photographs to be taken of me and used for advertising by Walk Kernow. *
		<br />
		These will be group photographs
	</label>
	<br />
	<input type="radio" name="q11" value="yes" <?php if(isset($_SESSION['member_hs_q11']) && $_SESSION['member_hs_q11'] == "yes"){ echo 'checked';}elseif($users->get_hs('q11') == "yes" && $_SESSION['member_hs_q11'] !== "no"){ echo 'checked';}?>> YES
	<br />
	<input type="radio" name="q11" value="no" <?php if(isset($_SESSION['member_hs_q11']) && $_SESSION['member_hs_q11'] == "no"){ echo 'checked';}elseif($users->get_hs('q11') == "no" && $_SESSION['member_hs_q11'] !== "yes"){ echo 'checked';}?>> NO
	
	<h3>
		Emergency Contact Information
	</h3>
	<p>
		In case of emergency, please contact the person below: *
		<br />
		Please include name, phone number and mobile phone number of emergency contact
	</p>
	<label for="emerg_name">
		Name of emergency contact:
	</label>
	<br />
	<input type="text" name="emerg_name" value="<?php if(isset($_SESSION['member_hs_emerg_name'])){echo htmlentities($_SESSION['member_hs_emerg_name']);}else{echo htmlentities($users->get_hs('emerg_name'));}?>"/>
	<br />

	<label for="emerg_prim_number">
		Primary emergency contact number:
	</label>
	<br />
	<input type="text" name="emerg_prim_number" value="<?php if(isset($_SESSION['member_hs_emerg_prim_number'])){echo htmlentities($_SESSION['member_hs_emerg_prim_number']);}else{echo htmlentities($users->get_hs('emerg_prim_number'));}?>" />
	<br />

	<label for="emerg_alt_number">
		Alternative emergency contact number(e.g. their mobile):
	</label>
	<br />
	<input type="text" name="emerg_alt_number" value="<?php if(isset($_SESSION['member_hs_emerg_alt_number'])){echo htmlentities($_SESSION['member_hs_emerg_alt_number']);}else{echo htmlentities($users->get_hs('emerg_alt_number'));}?>"/>
	<br />

	<h3>
		Terms and Conditions
	</h3>
	<p>
		YOU MUST ALWAYS BRING WATER TO OUR NORDIC WALKS (inc. a rucksack or waist pack to carry it) All Nordic Walks need to be booked to secure your place and or poles. We operate a &quot;No Bad Weather - Just Bad Clothing&quot; policy for our Nordic Walks so be prepared to walk in all weathers. We only cancel a walk due to sever weather warnings or lightening. We have a 24 hours notice of cancellation policy. Any booking canceled with the required 24 hours notice can be transferred to another Walk. All bookings canceled with less than the required 24 hours notice will not be eligible for any refund or transfer. We require you to stay with the group when Nordic Walking.If you wish to leave at any time please notify your Instructor or Walk Leader and they will get you to sign a "Leaving Walk Form" DOGS - Nordic Walkies Please note the whole responsibility for the dog lies with the individual dog owner, who must ensure that their dog remains under close control. Only the dog owner knows the level of obedience the dog has. Dogs should be kept on a lead in car parks, on roads, near livestock or sensitive wildlife, and wherever the law or other official regulations require it. Always remember poo bags / Dog ID both are required by law. Our civil liability insurance does not extend to incidences caused by dogs. We (Walk Kernow) cannot be held liable for any loss, injury, illness or death.  It is recommended that owners will have their own full insurance cover. Walk Kernow Instructors or Walk Leaders have the right to ask you to leave the walk at any time due to your dogs actions...aggression will not be tolerated!

	</p>
	<input type="radio" name="tc" value="yes" /> I agree
	<br />
	<input type="radio" name="tc" value="no" /> I do not agree.
	<br />
	<label for="q12">
		What is your current activity level *
		<br />
		Please indicate what exercise you currently do
	</label>
	<br />
	<textarea name="q12"><?php if(isset($_SESSION['member_hs_q12'])){echo htmlentities($_SESSION['member_hs_q12']);}else{echo htmlentities($users->get_hs('q12'));}?></textarea>
	<br />
		<input type="submit" name="submit">

	</form>
	<?php
	//clear session vars and errors
	unset($_SESSION['member_hs_q1']);
	unset($_SESSION['member_hs_q2']);
	unset($_SESSION['member_hs_q3']);
	unset($_SESSION['member_hs_q4']);
	unset($_SESSION['member_hs_q5']);
	unset($_SESSION['member_hs_q6']);
	unset($_SESSION['member_hs_q6_1']);
	unset($_SESSION['member_hs_q7']);
	unset($_SESSION['member_hs_q8']);
	unset($_SESSION['member_hs_q9']);
	unset($_SESSION['member_hs_q10']);
	unset($_SESSION['member_hs_q10']);
	unset($_SESSION['member_hs_q12']);
	unset($_SESSION['member_hs_q13']);
	unset($_SESSION['member_hs_emerg_name']);
	unset($_SESSION['member_hs_emerg_prim_number']);
	unset($_SESSION['member_hs_emerg_alt_number']);
	unset($_SESSION['member_hs_tc']);
	unset($_SESSION['member_hs_errors']);
	?>

