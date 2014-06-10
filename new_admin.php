<?php
	require 'core/init.php';


	//kick user to login page if not logged in.
	$admin->logged_out_protect();
	//check if currently logged in admin has the correct permission for this page
	$perm_check = $admin->permission_check( 'Y');
	if($perm_check == "no"){
		echo"<h2>Sorry, you do not have sufficient permissions to view this page.</h2> ";
		echo"<br /><button  onClick='history.go(-1);return true;'> Go Back </button>";
		exit;
	}

	if(!empty($_POST)){//if new admin form has been posted to this page, then run function to create new admin user
		//Input validation done in function
		$data = $_POST;
		$data= $adminControl->new_admin($data);
		if($data =="yes"){
			header('Location:admins.php');
			exit;
		}
	}
	$title="New Admin";
	require"header.php";
?>
<script>
function showMe (box) {
//function to show and hide list of permission when selecting the "all" checkbox
    var chboxs = document.getElementsByName("perms[0]");
    var vis = "block";
    for(var i=0;i<chboxs.length;i++) { 
        if(chboxs[i].checked){
         vis = "none";
            break;
        }
    }
    document.getElementById(box).style.display = vis;

}
</script>
<div id="content">
	<h1>
		New Admin
	</h1>
	<?php
		//display errors
		if(isset($data['errors'])){
			echo "<div class='errors'>";
			echo $data['errors'];
			echo "</div>";
		}
	?>
	<form id="new_admin" method="post" action="new_admin.php" autocomplete="off">
		<!-- PHP used to echo data back into form from session variable upon return to this page after error in new_admin_script.php -->
		<label for="username">
			Username: 
		</label>
		<input type="text" id="username" name="username" <?php if(isset($data['username'])){echo "value='" . htmlentities($data['username']) . "'" ; }?>/>
		<br />
		<label for="password">
			Password: 
		</label>
		<input type="password" id="password" name="password" />
		<input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Show password
		</br>
		<label for="email">
			Email:
		</label>
		<input type="text" id="email" name="email" <?php if(isset($data['email'])){echo "value='" . htmlentities($data['email']) . "'" ; }?> />
		<fieldset id="permissions">
			<legend>
				Permissions
			</legend>
			<input type="checkbox" id="all_perms" name="perms[0]" value="Z" onclick="showMe('permission_2')" <?php if(isset($data['perms'])){ if(strpos($data['perms'], 'Z')  !== false){ echo "checked";}} ?>>All [Z] - <span class="emp_red">(This will allow to user to access everything below, including this admin account control pannel)</span><br>
			<br />
			<div id="permission_2">
				<input type="checkbox" name="perms[]" value="Y" <?php if(isset($data['perms'])){ if(strpos($data['perms'], 'Y')  !== false){ echo "checked";}} ?>>Admin Control Panel [Y]<br>
				<fieldset>
					<legend>
						Members
					</legend>
					<input type="checkbox" name="perms[]" class="members" value="L" <?php if(isset($data['perms'])){ if(strpos($data['perms'], 'L')  !== false){ echo "checked";}} ?> >View Member List [L]<br>

					<input type="checkbox" name="perms[]" class="members" value="V" <?php if(isset($data['perms'])){ if(strpos($data['perms'], 'V')  !== false){ echo "checked";}} ?> >See Detailed User Data [V]<br>

					<input type="checkbox" name="perms[]" class="members" value="A" <?php if(isset($data['perms'])){ if(strpos($data['perms'], 'A')  !== false){ echo "checked";}} ?> >Account Status (Enable, Disable, Verify) [A]<br>

					<input type="checkbox" name="perms[]" class="members" value="H" <?php if(isset($data['perms'])){ if(strpos($data['perms'], 'H')  !== false){ echo "checked";}} ?> >Health &amp; Saftey Form (Accept, Decline, Revoke) [H]<br>

					<input type="checkbox" name="perms[]" class="members" value="C" <?php if(isset($data['perms'])){ if(strpos($data['perms'], 'C')  !== false){ echo "checked";}} ?> >Credits (Increase, Decrease) [C]<br>

					<input type="checkbox" name="perms[]" class="members" value="M" <?php if(isset($data['perms'])){ if(strpos($data['perms'], 'M')  !== false){ echo "checked";}} ?> >Modify Member Data (Including Name, Email, Address etc) [M]<br>

				</fieldset>
			</div>
			
		</fieldset>
		<input type="submit" />
	</form>
	<a href="admins.php"><button>Cancel</button></a>
</body>
</html>
	