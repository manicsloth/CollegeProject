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
	//obtain admin data from db, echo into list
	$data=$adminControl->get_admin_data("all");
	$title="Admin Users";
	require"header.php";
?>

<div id="content">
	<h1>
		Admin List
	</h1>
	<a href="new_admin.php"><button> Create New Admin Account </button></a>
	<p> Click an admin to edit them </p>
<?php
	//table for outputting admin users as 'list'
	echo "<table id='member_list'>
		<tr>
			<th>Username</th>
			<th>Email</th>
			<th>Permissions</th>
		</tr>";
	//for each row in the table
	foreach($data as $data){
		echo '<tr>';
		//echo each row out with link to view that admin on click
		echo '<td  onclick="document.location =' . " 'mod_admin.php?admin=$data[username] '" . ' ;" ><a href="mod_admin.php?admin=' . htmlentities($data['username']) . '">' . $data['username'] . '</td></a>';
		echo '<td  onclick="document.location =' . " 'mod_admin.php?admin=$data[username]'" . ' ;" >' . htmlentities($data['email']) . '</td>';
		echo '<td  onclick="document.location =' . " 'mod_admin.php?admin=$data[username]'" . ' ;" >' . htmlentities($permissions= $data['permissions']) . '</td>';
		echo "</tr>";
	}
	echo "</table>";

?>