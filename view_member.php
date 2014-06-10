<?php
	require 'core/init.php';
	$title="View Member";
	

	//kick user to login page if not logged in.
	$admin->logged_out_protect();
	//check if currently logged in admin has the correct permission for this page
	$perm_check = $admin->permission_check( 'V');
	if($perm_check == "no"){
		echo"<h2>Sorry, you do not have sufficient permissions to view this page.</h2> ";
		echo"<br /><button  onClick='history.go(-1);return true;'> Go Back </button>";
		exit;
	}

 	//check for get data with member id, if missing then redirect to member list.
	if(!isset($_GET['id']) || empty($_GET['id'])){
		header("Location:members.php");
		exit;
	}

	//check for new acc status in GET if yes then check for perms and  run appropriate function
	if(isset($_GET['new_acc_status'])){
		$perm_check = $admin->permission_check( 'A');
		if($perm_check == "yes"){
			$member->mod_acc_status($_GET['id'],$_GET['new_acc_status']);
			header("Location:view_member.php?id=$_GET[id]"); //reload page with clean get data
			exit;
		}
	}
	//check for new  hs status in GET if yes then check for perms and  run appropriate function
	if(isset($_GET['new_hs_status'])){
		$perm_check = $admin->permission_check( 'H');
		if($perm_check == "yes"){
			$member->mod_hs_status($_GET['id'],$_GET['new_hs_status'],$admin->get_username());
			header("Location:view_member.php?id=$_GET[id]"); //reload page with clean get data
			exit;
		}
	}
	//check for credit adjustment in get data and run appropriate functions
	if(isset($_GET['adjust_credit'])){
		$perm_check = $admin->permission_check( 'C');
		if($perm_check == "yes"){
			switch ($_GET['adjust_credit']) {
				case 'minus':
					$member->adjust_credit($_GET['id'], 'minus');
					break;
				case 'plus':
					$member->adjust_credit($_GET['id'], 'plus');
					break;

				default:
					break;
			}
			header("Location:view_member.php?id=$_GET[id]"); //reload page with clean get data
			exit;
		}
	}
	//check for delete in GET data and run appropriate function
	if(isset($_GET['delete']) && $_GET['delete'] == "true"){
		$member->delete_member($_GET['id']);
		header("Location:members.php");
		exit;
	}

	//LOAD USER DATA
	$member_data= $member->get_member_data($_GET['id']); //run function to get member data from DB
	require"header.php";
?>

<div id="content">

	<h1>
		<?php echo $member_data['fname'] . " " . $member_data['sname']; ?>
	</h1>
	<a href="members.php#<?php echo $member_data['id'] ; ?>"><button id="return_button">Return to Member List</button></a>

	<!-- echo data into table -->
	<table id="member_data">
		<tr>
			<th>
				Account Status
			</th>
			<td>
				<?php 	
					//Output the accounts status, if the admin has permission also show controls.
					switch ($member_data['acc_status']){
						//check the account status and show different message / button depending
					    case -1: //if account is disabled. offer button to enable
					    	echo "<span class='emp_red'>Account has been disabled.</span>";
					    		$perm_check = $admin->permission_check( 'A');
							if($perm_check == "yes"){
						    		echo" <br/> <a href='view_member.php?id=$member_data[id]&new_acc_status=enable'><button id='enable_acc'> Enable  </button></a>";
						  	  }
					    break;
					    case 0: //if account is still awaiting verification via email. offer button to manually verify.
					    	echo "Account awaiting verification via email.";
					    	$perm_check = $admin->permission_check( 'A');
						if($perm_check == "yes"){
					    		echo "<br/> <a href='view_member.php?id=$member_data[id]&new_acc_status=verify'><button id='enable_acc'>Verify  </button></a>";
					    	}
					    break;
					    case 1: // if account is active and in good standing. offer button to disable.

					    	echo "<span class='emp_green'>Member's account is active.</span>";
					   	$perm_check = $admin->permission_check( 'A');
						if($perm_check == "yes"){
					    		echo "<br/> <a href='view_member.php?id=$member_data[id]&new_acc_status=disable'><button id='disable_acc'> Disable  </button></a>";
					    	}
					    break;
					}
				?>
			</td>
		</tr>
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
				Name
			</th>
			<td>
				<?php echo htmlentities($member_data['fname']) . " " . htmlentities($member_data['sname']); ?>
			</td>
		</tr>
		<tr>
			<th>
				Gender
			</th>
			<td>
				<?php 
					switch ($member_data['gender']) {
						case 'm':
							echo "Male";
							break;
						
						case 'f':
							echo "Female";
							break;
						case 'na':
							echo "N/a";
							break;
					}
				?>
			</td>
		</tr>		
		<tr>
			<th>
				DOB
			</th>
			<td>
				<?php 
					$dob = htmlentities($member_data['dob']);
					$dob_ex = (explode("-",$dob)); //explode date from db
					$dob2 = "$dob_ex[2]/$dob_ex[1]/$dob_ex[0]";
					if($dob2=="01/01/0001"){
						echo "Age not specified";
					}
					else{
						echo $dob2;
					}
				?>
			</td>
		</tr>
		<tr>
			<th>
				Address
			</th>
			<td>
				<?php 	echo htmlentities($member_data['address']) . "<br>" . htmlentities($member_data['town']) . "<br>" . htmlentities($member_data['postcode']); ?>
			</td>
		</tr>
		<tr>
			<th>
				Email
			</th>
			<td>
				<?php 	echo htmlentities($member_data['email']); ?>
			</td>
		</tr>
				<tr>
			<th>
				Contact Number
			</th>
			<td>
				<?php 	echo htmlentities($member_data['contact_number']); ?>
			</td>
		</tr>
		<tr>
			<th>
				Health and Safety Form
			</th>
			<td>
				<?php  //check status of health and safety form (PARQ), output link to view it if applicable. If admin has correct permission show control buttons.
					if($member_data['hs_status'] == "0"){
						echo "Health and saftey form has not been submitted yet";
						$perm_check = $admin->permission_check( 'H');
						if($perm_check == "yes"){
							echo "<br /><a href='view_member.php?id=$member_data[id]&new_hs_status=verify'><button id='verify_hs'>Manual Verify</button></a>";
						}
					}
					if($member_data['hs_status'] == "1"){
						echo "<a href='admin_parq.php'>Submitted and awaiting verification - Click to open</a>";
						$perm_check = $admin->permission_check( 'H');
						if($perm_check == "yes"){
							echo "<br /><a href='view_member.php?id=$member_data[id]&new_hs_status=verify'><button id='verify_hs'>Verify</button></a>";
							echo "<br /><a href='view_member.php?id=$member_data[id]&new_hs_status=revoke'><button id='revoke_hs' class='emp_red'>Deny</button></a>";
						}
					}
					if($member_data['hs_status'] == "2"){
						echo "<span class='emp_green'>Completed and Verified by <strong>" . htmlentities($member_data['hs_admin']) . "</strong>- </span><br /><a href='admin_parq.php'>Click to open</a>";
						$perm_check = $admin->permission_check( 'H');
						if($perm_check == "yes"){
							echo "<br /><a href='view_member.php?id=$member_data[id]&new_hs_status=revoke'><button id='revoke_hs' class='emp_red'>Revoke</button></a>";
						}
					}
					if($member_data['hs_status'] == "-1"){
						echo "<span class='emp_red'>HS Denied by " . htmlentities($member_data['hs_admin']) . " - </span><a href='admin_parq.php'>Click to open</a>";
						$perm_check = $admin->permission_check( 'H');
						if($perm_check == "yes"){
							echo "<br /><a href='view_member.php?id=$member_data[id]&new_hs_status=verify'><button id='verify_hs' class='emp_green'>Verify</button></a>";
						}
					}
					//box to allow admin to input notes regarding hs form. apon submitting form sends notes as well as user id to script file for processing.
					$perm_check = $admin->permission_check( 'H');
					if($perm_check == "yes"){	
						echo "<form id='hs_notes' action='core/scripts/hs_notes_script.php' method='post'>
							<br /><label for='hs_notes'>Admin Notes</label>
							<br /><textarea name='hs_notes'>" . htmlentities($member_data['hs_notes']) . "</textarea>
							<input type='text' name='hs_id' value='$member_data[id]' hidden/>
							<input type='submit' value='Save' />
						</form>";
					}
					?>
			</td>
		</tr>
			<tr>
			<th>
				Credits
			</th>
			<td>
				<?php //shows users credits, if admin has correct permission will show plus and minus buttons 
				$perm_check = $admin->permission_check( 'C');
				if($perm_check == "yes"){
					echo "<a href='view_member.php?id=$member_data[id]&adjust_credit=minus#credit'><button id='minus_credit'><img src='images/icons/minus.png'/ alt='minus'></button></a>";
				}
				echo "<span id='credit'>$member_data[credits]</span>";
				$perm_check = $admin->permission_check( 'C');
				if($perm_check == "yes"){
					echo "<a href='view_member.php?id=$member_data[id]&adjust_credit=plus#credit'><button id='plus_credit'><img src='images/icons/plus.png'/ alt='plus'></button></a>";
				}
				?>
			</td>
		</tr>
	</table>
	<?php 
		//If admin has correct permission, add a button to allow them to go to the page to modify members data and delete the members account & data
		$perm_check = $admin->permission_check( 'M');
		if($perm_check == "yes"){
			echo "<a href='mod_member.php?id=$member_data[id]'><button id='mod_member'>Modify Member Data</button></a>";
			?>
			<script>
				function confirmDelete (username, admin){
					// javascript function to show confirmation dialog before deleting a user account.
					var x = confirm('Are you sure you want to delete this member account and all connected data? This is permanent and cannot be undone.');
					if( x ==true){
						window.location ="view_member.php?id="+username+"&&delete=true";
					}
					else{
						return false;
					}
				}
			</script>
			<button id="delete_member" onclick="confirmDelete('<?php echo $member_data['id']; ?>',  '<?php echo htmlentities($admin->get_username());?>')">Delete Member Account</button>
			<?php

		}
	?>	
</div>
</body>
</html>