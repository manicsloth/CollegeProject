<?php 

//AdminControl class. This is used for methods that are used for the admin control panel.
class AdminControl{
 	private $db;

	public function __construct($database){  
		$this->db = $database;
	}

	
	public function get_admin_data($username){
	//function to get admin data, either specific admin or list of all. Parameter provided is admins username

		if ($username == "all"){ //if username is 'all' then search with no parameters, used to generate list of all members
			$query = $this->db->prepare("select id, username, email, permissions from `admins`"); 
		}
		else{ //search for specified admin by username
			$query = $this->db->prepare("select id, username, email, permissions from `admins` WHERE username=?"); 
			$query->bindParam(1, $username); 
		}

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		$count = $query->rowCount();
		if ($count == 0) {//no matches found
			$data="n/a";
		}
		else{
			switch ($username) {
				case 'all':
					$data= $query ->fetchall(PDO::FETCH_ASSOC); //extract data into array	
					break;
				
				default:
					$data= $query ->fetch(PDO::FETCH_ASSOC); //extract data into array	
					break;
			}
		}
		return $data;
		exit;
	}

	public function new_admin($data){
	//function to create a new admin user. accepts new user data as parameter from form POST
		extract($data);
	
		//convert permissions from data array into single string of characters
		$perms="";
		if(isset($data['perms'])){
			foreach($data['perms'] as $x){
				$perms.= $x;
			}
		}
		//update the data value to match string as this is used to re populate the check boxes upon return
		$data['perms'] = $perms;

		//check for empty fields. If any return error.
		if(empty($username) || empty($password) ||  empty($email) || empty($perms)){
			$data['errors']="You must complete all fields and select at least one permission.";
			return $data;
			exit;
		}

		//parse input for validation errors etc.
		//check if the username is already in use.
		$query = $this->db->prepare("select `username` from `admins` WHERE username=?"); 
		$query->bindParam(1, $username); 
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		$count = $query->rowCount(); //count results
		if ($count > 0) { //if a result is found, then username is already taken.
			$data['errors']="That username is already in use.";
		}

		//check that email is a valid email address
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			$data['errors'] = 'Please enter a valid email address';
		}

		//check if the email is already in use.
		$query = $this->db->prepare("select `email` from `admins` WHERE email=?"); 
		$query->bindParam(1, $email); 
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		$count = $query->rowCount(); //count results
		if ($count > 0) { //if a result is found, then username is already taken.
			$data['errors'].="That email is already taken.";
		}

				//if errors then return to form and display errors
		if(isset($data['errors']) && !empty($data['errors'])){
			return $data;
			exit;
		}

		//encrypt password
		global $bcrypt;
		$password   = $bcrypt->genHash($password);

		$query = $this->db->prepare("INSERT INTO admins (username, password, email, permissions) VALUES(?,?,?,?)"); 
		$query->bindParam(1, $username); 
		$query->bindParam(2, $password); 
		$query->bindParam(3, $email); 
		$query->bindParam(4, $perms); 
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

		return "yes";
		exit;

	}


	public function modify_admin($data){
	//function to modify the data stored of an admin account. accepts array of the new data to be input to the data base as parameter	
		//convert permissions into a string of characters
		$perms="";
		if(isset($data['perms'])){
			foreach($data['perms'] as $x){
				$perms.= $x;
			}
		}

		$data['perms'] = $perms;
		//check for empty fields. If any return error.
		if(empty($data['username']) || empty($data['email']) || empty($data['perms'])){
			$data['errors']="You must complete all fields.";
			return $data;
			exit;
		}

		//check that email is a valid email address
		if(filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
			$data['errors'] = 'Please enter a valid email address';
		}

		//check if email is already taken. Ignore the admin user being modified form search to prevent false positives if email is being submitted without changes
		$query = $this->db->prepare("select email from `admins` WHERE username<>?"); 
		$query->bindParam(1, $data['username']); 
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		while($results= $query ->fetch(PDO::FETCH_ASSOC)) {
			if($data['email'] == $results['email']){
				$data['errors'] = "The email specified is already in use. [$_POST[email]]";
			}
		}

		//if errors then return to form and display errors
		if(isset($data['errors']) && !empty($data['errors'])){
			return $data;
			exit;
		}


		//detect if password has been changed, run appropriated query
		if(empty($data['password'])){//no password change
			$query = $this->db->prepare("UPDATE admins SET username=?, email=?, permissions=? WHERE username =?");
			$query->bindParam(1, $data['username']); 
			$query->bindParam(2, $data['email']); 
			$query->bindParam(3, $data['perms']);
			$query->bindParam(4, $data['username']);
		}
		else{//password changed
			//encrypt new password
			global $bcrypt;
			$data['password'] = $bcrypt->genHash($data['password']);
			$query = $this->db->prepare("UPDATE admins SET username=?, password=?, email=?, permissions=? WHERE username =?");
			$query->bindParam(1, $data['username']); 
			$query->bindParam(2, $data['password']); 
			$query->bindParam(3, $data['email']); 
			$query->bindParam(4, $data['perms']);
			$query->bindParam(5, $data['username']);  

		}
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

		header("Location:admins.php");
		exit;

	}

	public function delete_admin($username){
	//function to delete an admin account. accepts admin username as parameter.
		$query = $this->db->prepare("DELETE FROM admins WHERE username =?");
		$query->bindParam(1, $_GET['admin']);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}

}
