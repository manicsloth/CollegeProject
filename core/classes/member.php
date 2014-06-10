<?php 

//Member class. This is used for methods that do member related actions for the admin site. 
class Member{
 	private $db;

 	public function __construct($database) {  
	    $this->db = $database;
	}

	function get_member_list_data($search){
	//function to retrieve basic member data for members page list based on search terms. parameter provided is either 'all', user 'id' or users fname or fname+sname

		if ($search == "all"){ //if search is 'all' then search with no parameters, used to generate list of all members
			$query = $this->db->prepare("select members_data.*, members.credits from `members_data` JOIN members on (members_data.id = members.id)"); 
		}
		elseif(is_numeric($search)) { //if search is only numbers - i.e. a members id
			$query = $this->db->prepare("select members_data.*, members.credits from `members_data` JOIN members on (members_data.id = members.id) WHERE members_data.id=?"); 
			$query->bindParam(1, $search); 
		}
		else{//search for a members name
			//split up string on whitespace into an array
			$search = preg_split('/\s+/', $search);

			//assign first part of array to its own variable and apply wild card to search for results that start with the search terms. (e.g  'bo' will find 'bob, will not find 'bill'. 'b' will find both 
			$search1 = "$search[0]%";
			//check if there is second part of array, and if so store it in its own variable and add wildcard
			if(isset($search['1'])){
				$search2 = "$search[1]%";
			}
			//if there was a second part to array, assume the user input is fname and sname and create appropriate query. Otherwise search for only fname,
			if(isset($search2)){
				$query = $this->db->prepare("select members_data.*, members.credits from `members_data` JOIN members on (members_data.id = members.id) WHERE fname like ? and sname like ?"); 
				$query->bindParam(1, $search1); 
				$query->bindParam(2, $search2);
			}
			else{
				$query = $this->db->prepare("select members_data.*, members.credits from `members_data` JOIN members on (members_data.id = members.id) WHERE fname like ?"); 
				$query->bindParam(1, $search1); 
			}
		}

		try{
			$query->execute();
			$count = $query->rowCount();

			if ($count == 0) {//no matches found
				$query="n/a";
			}
			
			//return results to script calling this function
			return $query;
		}catch(PDOException $e){
			die($e->getMessage());
		}
		exit;
	}
	
	public function get_member_data($id){
	//function to get all of a specified member data from provided user id. will get data from tables: members, members_data, members_hs, members_ws

		$query = $this->db->prepare("select members.*, members_data.*, members_hs.*, members_ws.* from `members`
			JOIN members_data on (members.id = members_data.id) 
			JOIN members_hs on (members.id = members_hs.id)
			JOIN members_ws on (members.id = members_ws.id)
			WHERE members.id=?"); 
			$query->bindParam(1, $id); 
		try{
			$query->execute();
			$count = $query->rowCount();
			if ($count == 0) {//no matches found
				$data="n/a";
			}
			else{
			$data= $query ->fetch(PDO::FETCH_ASSOC); //extract data into array
			if (isset($data['password'])){
				unset($data['password']);  //purge password if exists before returning.
			}
		}
			//return results to script calling this function
			return $data;
		}catch(PDOException $e){
			die($e->getMessage());
		}
		exit;


	}


	public function get_age($dob_month,$dob_day,$dob_year){
	//function to calculate age from date. Parameters are in order month, day, year
		$year   = gmdate('Y');
		$month  = gmdate('m');
		$day    = gmdate('d');
		//seconds in a day = 86400
		$days_in_between = (mktime(0,0,0,$month,$day,$year) - mktime(0,0,0,$dob_month,$dob_day,$dob_year))/86400;
		$age_float = $days_in_between / 365.242199; // Account for leap year
		$age = (int)($age_float); // Remove decimal places without rounding up once number is + .5
		return $age;
	}

	public function mod_acc_status($id, $new_acc_status){
	//function to modify a members account status. Parameters provided are members ID number and the new account status (enable, disable, verify)

		if($new_acc_status == "enable" | $new_acc_status == "verify"){ //if account toggle from disable to enabled. Verifying an account will enable it.
			$status="1";
		}
		if($new_acc_status == "disable"){
			$status ="-1";
		}

		$query = $this->db->prepare("UPDATE members set acc_status=? WHERE id=?"); 
		$query->bindParam(1, $status); 
		$query->bindParam(2, $id); 

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}

	public function mod_hs_status($id, $new_hs_status, $admin){
	//function to modify a members health and safety status. Parameters provided are members ID and new status
		
		if($new_hs_status == "verify"){ //accept a form
			$status="2";
		}
		if($new_hs_status == "revoke"){ // deny a form
			$status ="-1";
		}
		$query = $this->db->prepare("UPDATE members_hs set hs_status=?, hs_admin=? WHERE id=?"); 
		//parameters for query
		$query->bindParam(1, $status); 
		$query->bindParam(2, $admin); 
		$query->bindParam(3, $id); 
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}
	public function mod_hs_notes($id, $notes){
	//function to update members hs notes.

		$query = $this->db->prepare("UPDATE members_hs set hs_notes=? WHERE id=?"); 
		$query->bindParam(1, $notes);
		$query->bindParam(2, $id);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		header("Location:../../view_member.php?id=$id"); 
	}
	public function adjust_credit($id, $adjust){
	//function to adjust member credits by 1. parameters are member id and if method should increment (plus) or decrement (minus).

		if($adjust == "plus"){ //increase credits by 1
			$query = $this->db->prepare("UPDATE members set credits=credits+1 WHERE id=?"); 
			$query->bindParam(1, $_GET['id']); 
		}
		if($adjust == "minus"){ //increase credits by 1
			$query = $this->db->prepare("UPDATE members set credits=credits-1 WHERE id=?"); 
			$query->bindParam(1, $_GET['id']); 
		}
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		header("Location:../../view_member.php?id=$id"); 
	}

	public function update_member_data($data, $id){
	//function to update member account data from mod member page. accepts post data from form and member id as parameter.
	extract($data);
		//update members_data table
		$query = $this->db->prepare("UPDATE members_data set fname=?, sname=?, dob=?, address=?, town=?, postcode=?, contact_number=?, gender=? WHERE id=?");
		$query->bindParam(1, $fname); 
		$query->bindParam(2, $sname);
		$query->bindParam(3, $dob);
		$query->bindParam(4, $address);
		$query->bindParam(5, $town);
		$query->bindParam(6, $postcode);
		$query->bindParam(7, $contact_number);
		$query->bindParam(8, $gender);
		$query->bindParam(9, $id);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

		//update members table
		$query = $this->db->prepare("UPDATE members set email=? ,  credits=? WHERE id=?");
		$query->bindParam(1, $email);
		$query->bindParam(2, $credits);
		$query->bindParam(3, $id);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}



	}
	public function delete_member($id){
		$tables=array('members', 'members_data', 'members_hs', 'members_hs_form',' members_ws'); //array to hold list of tables names for loop

		foreach($tables as $x){
			$query = $this->db->prepare("DELETE FROM $x WHERE id=?");
				$query->bindParam(1, $id); 
			try{
				$query->execute();
			}catch(PDOException $e){
				die($e->getMessage());
			}
		}

	}
}
?>