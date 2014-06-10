<?php
	require 'core/init.php';
	//kick user to login page if not logged in.
	$admin->logged_out_protect();
	//check permissions
	$perm_check = $admin->permission_check( 'Y');
	if($perm_check == "no"){
		echo"<h2>Sorry, you do not have sufficient permissions to view this page.</h2> ";
		echo"<br /><button  onClick='history.go(-1);return true;'> Go Back </button>";
		exit;
	}
	if(isset($_GET['admin']) || !empty($_GET['admin'])){
		//check for get data with admin username if present the load admins data 
		//load current data for admin to echo into form.
		$username = $_GET['admin'];
		$data= $adminControl->get_admin_data($username); //run function to get admin data from DB
		$data['perms'] = $data['permissions'];
	}
	else{ //if no get then return to admin list .
		header("Location:admins.php");
		exit;
	}
	if(!empty($_POST)){//if mod admin form has been posted to this page, then run function to update
		//validation done in function
		$data= $adminControl->modify_admin($_POST);
	}
	if(isset($_GET['delete']) && $_GET['delete'] == "true"){ //if delete is in GET data then run method to delete.
		$adminControl->delete_admin($_GET['admin']);
		header("Location:admins.php");
		exit;
	}
	$title="Modify Admin";
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
		Modify Admin
	</h1>
	<?php 
		//display errors
		if(isset($data['errors']) && !empty($data['errors'])){
			echo "<div class='errors'>";
			echo $data['errors'];
			echo "</div>";
		}
	?>
	<form id="mod_admin" action="mod_admin.php?admin=<?php echo $data['username'];?>" method="post" >		
		<label for="username">
			Username: 
		</label>
		<input type="text" name="username" value="<?php echo htmlentities($data['username']); ?>" hidden />
		<span id="username"> <?php echo $data['username']; ?> </span>
		<br />
		<label for="password">
			Password: 
		</label>
		<input type="password" name="password" id="password" maxlength="25" value = ""/>
		<input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Show password. Leave empty to not change.
		<br />
		<label for="email">
			Email: 
		</label>
		<input type="text" name="email" id="email" maxlength="250" value="<?php echo htmlentities($data['email']); ?>" />
		<br />
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
	<input type="submit" value="Submit" />
</form>

<script>
	function confirmDelete (username, admin){
	// function to show confirm box when deleteing account. accepts username of account to delete, and username of admin initiating the delete
		if(username == admin){//make sure account is not deleteing itself.
			alert('Error. You cannot delete the same account you are currently logged in as');
			return false;
		}

		var x = confirm('Are you sure you want to delete this admin account? This is permanent and cannot be undone.');
		if( x ==true){
			window.location = "mod_admin.php?admin="+username+"&&delete=true";
		}
		else{
			return false;
		}
	}
</script>
<button onclick="confirmDelete('<?php echo htmlentities($data['username']);?>','<?php echo htmlentities($admin->get_username()); ?>')">Delete Admin Account</button>
</div>