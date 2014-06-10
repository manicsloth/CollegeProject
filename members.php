<?php
	require 'core/init.php';
	$title="Members";
	require"header.php";

	//kick user to login page if not logged in.
	$admin->logged_out_protect();
	//check if currently logged in admin has the correct permission for this page
	$perm_check = $admin->permission_check( 'L');
	if($perm_check == "no"){
		echo"<h2>Sorry, you do not have sufficient permissions to view this page.</h2> ";
		echo"<br /><button  onClick='history.go(-1);return true;'> Go Back </button>";
		exit;
	}

	//check if user has done a search and store in variable if so.
	if(isset($_GET['searchbox']) && !empty($_GET['searchbox']) ){
		$search=htmlentities($_GET['searchbox']);
	}
	//otherwise set search term to be 'all' as default.
	else{
		$search= "all";
	}

?>
<div id="content">
	<h1>
		Member List
	</h1>


	<form name="search" id="searchform" action="members.php" method="get">
		<label for="searchbox">
			Search for member: 
		</label>
		<input name="searchbox" id="searchbox" />
		<a href="members.php"><img src="images/icons/undo.png" id="searchbox_reset" /></a>
	</form>
	<?php 
		//retrieve member data from db, based on users search if search is done (if not then show all members) and print into table.
		
		//run function to search for members
		$query= $member-> get_member_list_data($search); 

		//if no members found
		if($query=="n/a"){
			echo "<br /><p>No members found for search term '$search'.</p>";
		}
		//else make table
		else{
			echo "<table id='member_list'>
				<tr>
					<th>ID No.</th>
					<th>Name</th>
					<th>Location</th>
					<th>Credits</th>
				</tr>";
		
			//for each row in the table
			foreach ($query as $data) {				
				//store that rows data into variables
				$id=$data['id'];
				$name= $data['fname'];
				$sname = $data['sname'];
				$location= $data['town'];
				$credits= $data['credits'];

				//make new table row
				echo '<tr>';
				//echo each row out with link to view that member on click
				echo '<td  onclick="document.location =' . " 'view_member.php?id=$data[id]'" . ' ;" id="'. $id . '">' . $id . '</td>';
				echo '<td  onclick="document.location =' . " 'view_member.php?id=$data[id]'" . ' ;">' . "<a href='view_member.php?id=$data[id]'>" . htmlentities($name) . " " . htmlentities($sname). '</a></td>';
				echo '<td  onclick="document.location =' . " 'view_member.php?id=$data[id]'" . ' ;">' . htmlentities($location) . '</td>';
				echo '<td  onclick="document.location =' . " 'view_member.php?id=$data[id]'" . ' ;">' . htmlentities($credits) . '</td>';
				
				echo "</tr>";
			}
			echo "</table>";
			echo "<p><a href='#heading'>	Return to top</a></p>";
		}

	?>
</div>
</body>
</html>


