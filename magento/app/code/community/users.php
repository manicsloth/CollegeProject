<?php 
//create class Users. This will contain functions and scripting relating to the member that is currently logged in.
class Users{
 	private $db; 	//MEMBER OBJECT DATA
	private $logged_in= "false" ;
	// members tables fields
	protected $id, $acc_status, $email, $credits, $bookings, $pole_renting;
	
	// members_data tables fields
	private $fname, $sname, $gender, $dob, $address, $town, $postcode, $contact_number, $alt_contact_number;
	
	// members_hs table fields
	private $hs_status, $hs_admin, $hs_notes;

	//member_hs_data fields
	private $q1, $q2, $q3, $q4, $q5, $q6, $q6_1, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $emerg_name, $emerg_prim_number, $emerg_alt_number, $tc;
	
	// members_ws table fields
	private $ws_status, $ws_admin, $ws_notes, $ws_date;

	public function get_hs($data){
		return $this->$data;
		exit;
	}
	public function get_logged_in(){
		return $this->logged_in;
		exit;
	}
	public function get_id(){
		return $this->id;
		exit;
	}
	public function get_acc_status(){
		return $this->$id;
		exit;
	}
	public function get_email(){
		return $this->email;
		exit;
	}
	public function get_credits(){
		return $this->credits;
		exit;
	}
	public function get_bookings(){
		return $this->bookings;
		exit;
	}
	public function set_bookings($bookings){
		$query = $this->db->prepare("UPDATE `members` SET `bookings` = ? WHERE `id` = ?");
		$query->bindValue(1, $bookings);
		$query->bindValue(2, $this->id);		
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
		//update object to hold new booking data, re serialize object.
		$query = $this->db->prepare("SELECT `bookings` FROM `members` WHERE `id` =?");
		$query->bindValue(1, $this->id);
		try{
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			die($e->getMessage());
		}
		$this->bookings = $data['bookings'];
		$_SESSION['users_ser'] = serialize($this);
		return "yes";
		exit;
	}
	public function get_pole_renting(){
		return $this->pole_renting;
		exit;
	}
	public function set_pole_renting($value){
		//change value of 'poles' fields in database. should be either y/n to indicate if the user has specified they own poles
		$query = $this->db->prepare("UPDATE `members` SET `pole_renting` = ? WHERE `id` = ?");
		$query->bindValue(1, $value);
		$query->bindValue(2, $this->id);		
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
		//update object and re serialize
		$this->pole_renting = $value;
		$_SESSION['users_ser'] = serialize($this);
		return "yes";
		exit;
	}

	public function get_fname(){
		return $this->fname;
		exit;
	}
	public function get_sname(){
		return $this->sname;
		exit;
	}
	public function get_gender(){
		return $this->gender;
		exit;
	}
	public function get_dob(){
		return $this->dob;
	}
	public function get_address(){
		return $this->address;
		exit;
	}
	public function get_town(){
		return $this->town;
		exit;
	}
	public function get_postcode(){
		return $this->postcode;
		exit;
	}
	public function get_contact_number(){
		return $this->contact_number;
		exit;
	}
	public function get_alt_contact_number(){
		return $this->alt_contact_number;
		exit;
	}
	public function get_hs_status(){
		return $this->hs_status;
		exit;
	}
	public function get_hs_admin(){
		return $this->hs_admin;
		exit;
	}
	public function get_hs_notes(){
		return $this->hs_notes;
		exit;
	}
	public function get_ws_status(){
		return $this->ws_status;
		exit;
	}
	public function get_ws_admin(){
		return $this->ws_admin;
		exit;
	}
	public function get_ws_notes(){
		return $this->ws_notes;
		exit;
	}
	public function get_ws_date(){
		return $this->ws_date;
		exit;
	}
 	
	public function __construct($database) {  
	    $this->db = $database;
	}
	
	public function __sleep(){
		$this->db = "";
		unset($bcrypt);
		return array('logged_in', 'id', 'acc_status', 'email', 'credits', 'bookings', 'pole_renting', 'fname', 'sname', 'gender', 'dob', 'address', 'town', 'postcode', 'contact_number', 'alt_contact_number', 'hs_status', 'hs_admin', 'hs_notes', 'ws_status', 'ws_admin', 'ws_notes', 'ws_date', 'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q6_1', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'emerg_name', 'emerg_prim_number', 'emerg_alt_number', 'tc' );
	}
	public function __wakeup(){
		Global $db_members;
		$this->db = $db_members;
		unset($db_members);
	}	
	public function logged_out_protect() {
	//To protect areas that non logged in users are restricted from. redirects to index
		if ($this->logged_in == "false") {
			header('Location: login.php');
			exit();
		}	
	}
	public function logged_in_protect() {
		//To protect areas that logged in users are restricted from (e.g. registration pages).
		if ($this->logged_in == "true") {
			header('Location: member_home.php');
			exit();		
		}
	}
	public function login($id){
		//function to update and set the object variables when logging a user in
		$this->logged_in = "true";
		$this->id= $id;
		//grab users data from db and store it in object variables.
		$data = $this->retrieve_user_data("members", $id);
		foreach ($data as $key => $value) {
			$this->$key= $value;
		}
		$data = $this->retrieve_user_data("members_data", $id);
		foreach ($data as $key => $value) {
			$this->$key= $value;
		}
		$data = $this->retrieve_user_data("members_hs", $id);
		foreach ($data as $key => $value) {
			$this->$key= $value;
		}
		$data = $this->retrieve_user_data("members_hs_form", $id);
		foreach ($data as $key => $value) {
			$this->$key= $value;
		}
		$data = $this->retrieve_user_data("members_ws", $id);
		foreach ($data as $key => $value) {
			$this->$key= $value;
		}
	}
	public function email_exists($email) {
	 //function to check if an email is already taken when registering for an account.
		$query = $this->db->prepare("SELECT COUNT(`id`) FROM `members` WHERE `email`= ?");
		$query->bindValue(1, $email);
		try{
			$query->execute();
			$rows = $query->fetchColumn();
	 		
			if($rows >= 1){
				return true;
			}
			else if($rows == 0){
				return false;
			}
			else{
				echo "Error checking email existence.";
				exit;
			}
		} catch (PDOException $e){
			die($e->getMessage());
		}
	}
	 
	public function register($data){
	//function to create new member accounts
		extract($data);
		//recheck that email is not in use before proceeding. if register script is working this should never fail
		if ($this->email_exists($email) === true){
			echo "Error. Duplicate email.";
			exit;
		}
		global $bcrypt;
		$date = date("Y-m-d"); ; //todays date
		$email_code = $email_code = uniqid('EC_',true); 
		$password   = $bcrypt->genHash($password);// generating a hash using the $bcrypt object
	
	 	//insert member account data into members table
		$query 	= $this->db->prepare("INSERT INTO `members` (`email`, `password`, `question`, `answer`, `reg_date`, `email_code`, `refer`, `cards`) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ");
		$query->bindValue(1, $email);
		$query->bindValue(2, $password);
		$query->bindValue(3, $question);
		$query->bindValue(4, $answer);
		$query->bindValue(5, $date);
		$query->bindValue(6, $email_code);
		$query->bindValue(7, $refer);
		$query->bindValue(8, $cards);
	 	
		try{
			//run query to insert account data
			$query->execute();
			//grab the ID to be used in the rest of insert scripts below
			$query = $this->db->prepare("SELECT id from `members` WHERE `email` = ?");
			$query->bindValue(1, $email);
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$id = $data['id'];
		}catch(PDOException $e){
			die($e->getMessage());
		}
		//insert member personal data into members_data table
		$query 	= $this->db->prepare("INSERT INTO `members_data` (`id`, `fname`, `sname`, `gender`, `dob`, `address`, `town`, `postcode`, `contact_number`, `alt_contact_number`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$query->bindValue(1, $id);
		$query->bindValue(2, $fname);
		$query->bindValue(3, $sname);
		$query->bindValue(4, $gender);
		$query->bindValue(5, $dob);
		$query->bindValue(6, $address);
		$query->bindValue(7, $town);
		$query->bindValue(8, $postcode);
		$query->bindValue(9, $contact_number);
		$query->bindValue(10, $alt_contact_number);
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}		//set up blank hs entry for new member
		$query 	= $this->db->prepare("INSERT INTO `members_hs` (`id`) VALUES (?) ");
		$query->bindValue(1, $id);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		//set up blank hs_form entry for new member
		$query 	= $this->db->prepare("INSERT INTO `members_hs_form` (`id`) VALUES (?) ");
		$query->bindValue(1, $id);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		//set up blank ws entry for new member
		$query 	= $this->db->prepare("INSERT INTO `members_ws` (`id`) VALUES (?) ");
		$query->bindValue(1, $id);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		//email user activation link using Swift Mailer
		require_once '../swift/lib/swift_required.php';
		$transport = Swift_SmtpTransport::newInstance('smtp.gmx.com', 465, "ssl") ->setUsername('webkrunch@gmx.co.uk') ->setPassword('toothpick');
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance('Thankyou for Registering to Walk Kernow Alpha') ->setFrom(array('webkrunch@gmx.co.uk' => 'Walk_Kernow_Alpha')) ->setTo(array('ab122701@ghs.truropenwith.ac.uk')) ->setBody("Hello " . $email. ",\r\nThank you for registering with us. Please visit the link below so we can activate your account:\r\n\r\nhttp://localhost/web/walk_kernow/site/activate.php?email=" . $email . "&email_code=" . $email_code . "\r\n\r\n-- Walk Kernow (Alpha)");
		$result = $mailer->send($message);
	
		return "yes";
			
	}
	public function resend_activation($email){
	//function to resend a user the email containing their activation code
		$query = $this->db->prepare("SELECT `email_code` FROM `members` WHERE `email` =?");
		$query->bindValue(1, $email);
		try{
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			die($e->getMessage());
		}
		//email user activation link using Swift Mailer
		require_once 'core/swift/lib/swift_required.php';
		$transport = Swift_SmtpTransport::newInstance('smtp.gmx.com', 465, "ssl") ->setUsername('webkrunch@gmx.co.uk') ->setPassword('toothpick');
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance('Thankyou for Registering to Walk Kernow Alpha') ->setFrom(array('webkrunch@gmx.co.uk' => 'Walk_Kernow_Alpha')) ->setTo(array('ab122701@ghs.truropenwith.ac.uk')) ->setBody("Hello " . $email. ",\r\nThank you for registering with us. Please visit the link below so we can activate your account:\r\n\r\nhttp://localhost/web/walk_kernow/member_site/activate.php?email=" . $email . "&email_code=" . $data['email_code'] . "\r\n\r\n-- Walk Kernow (Alpha)");
		$result = $mailer->send($message);	}
	public function activate($email, $email_code) {
	//function to activate a user account via the email link		
		$query = $this->db->prepare("SELECT COUNT(`id`) FROM `members` WHERE `email` = ? AND `email_code` = ? AND `acc_status` = ?");
		$query->bindValue(1, $email);
		$query->bindValue(2, $email_code);
		$query->bindValue(3, 0);
		try{
			$query->execute();
			$rows = $query->fetchColumn();
 			
			if($rows == 1){ //if result is found from query
				
				$query_2 = $this->db->prepare("UPDATE `members` SET `acc_status` = ? WHERE `email` = ?");
 
				$query_2->bindValue(1, 1);
				$query_2->bindValue(2, $email);				
 
				$query_2->execute();
				return true;
			}
			else{
				return false;
			}
 
		} catch(PDOException $e){
			die($e->getMessage());
		}
	}
	public function login_credentials($email, $password) {
	 //function to check if login credentials are correct.
		global $bcrypt; 
		$query = $this->db->prepare("SELECT `password`, `id` FROM `members` WHERE `email` = ?");
		$query->bindValue(1, $email);
		try{
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$stored_password = $data['password'];
			$id = $data['id'];
			//hashing the supplied password and comparing it with the stored hashed password.
			if($bcrypt->verify($password, $stored_password) === true){
				return $id;
			}else{
				return false;	
			}
	 
		}catch(PDOException $e){
			die($e->getMessage());
		}
		
	}
	public function account_status($email) {
	 //function to check the  account status when user attempts to login.
		$query = $this->db->prepare("SELECT * FROM `members` WHERE `email`= ?");
		$query->bindValue(1, $email);
		//echo var_dump($query); //exit;
		try{
			$query->execute();
			
		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data['acc_status'];
	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
	 
	}
	public function credit_control($id, $action, $value){
	//function to perform various credit tasks and update the current value stored in object data for the users credit. accepts users id, an action (add / remove. If none specified will only update ) and a numerical value. It will no update object data if the ID is of a different user to currently logged in,
		switch ($action) {
			case 'add':
				$query = $this->db->prepare("UPDATE `members` SET `credits` = `credits` + ? WHERE `id` = ?");
				$query->bindValue(1, $value);
				$query->bindValue(2, $id);
				try{
					$query->execute();	 
				} catch(PDOException $e){
					die($e->getMessage());
				}
				break;
			case 'remove':
				$query = $this->db->prepare("UPDATE `members` SET `credits` = `credits` - ? WHERE `id` = ?");
				$query->bindValue(1, $value);
				$query->bindValue(2, $id);
				try{
					$query->execute();	 
				} catch(PDOException $e){
					die($e->getMessage());
				}
				break;	
			default:
				
				break;
		}
		//Update object value from database if it is the same user as currently logged in as this object
		if($id == $this->id){
			$query = $this->db->prepare("SELECT `credits` FROM `members` WHERE `id`= ?");
			$query->bindValue(1, $id);
			try{
				$query->execute();	 
				$data = $query->fetch(PDO::FETCH_ASSOC);
			} catch(PDOException $e){
				die($e->getMessage());
			}
			$this->credits = $data['credits'];
			//update session serialize
			$_SESSION['users_ser'] = serialize($this);
		}

	}

	public function retrieve_user_data($table_name, $id) {
	 //function to retrieve a members  data from supplied database using id no.
		$query = $this->db->prepare("SELECT * FROM $table_name WHERE `id`= ?");
		$query->bindValue(1, $id);
	 
		try{
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			
			if (isset($data['password'])){
				$data['password'] = "";
				unset($data['password']);  //purge password if exists before returning.
			}
		
			return $data;
		} catch(PDOException $e){
			die($e->getMessage());
		}
	}
	public function password_reset_email($email){
	//function to send user an email during password reset process
		$unique = uniqid('',true); // generate a unique string
		$random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0, 10); // generate a more random string
		$generated_string = $unique . $random; // a random and unique string
		 
		$query = $this->db->prepare("UPDATE `members` SET `reset_pass_code` = ? WHERE `email` = ?");
		$query->bindValue(1, $generated_string);
		$query->bindValue(2, $email);
		
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
		//email user password reset link and code using Swift Mailer
		require_once 'core/swift/lib/swift_required.php';
		$transport = Swift_SmtpTransport::newInstance('smtp.gmx.com', 465, "ssl") ->setUsername('webkrunch@gmx.co.uk') ->setPassword('toothpick');
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance('Walk Kernow Password Reset') ->setFrom(array('webkrunch@gmx.co.uk' => 'Walk_Kernow_Alpha')) ->setTo(array('ab122701@ghs.truropenwith.ac.uk')) ->setBody("Hello " . $email. ",\r\nWe have received a request to reset your password. If this was you, please visit the link below and enter the code displayed below.\r\n\r\nhttp://localhost/web/walk_kernow/member_site/password_reset.php?email=" . $email . "&&code=" .$generated_string . " \r\n\r\n-- Walk Kernow (Alpha)");
		$result = $mailer->send($message);
	}
	public function password_reset_validate($email, $code){
	//function to check if a supplied email and password reset code are a valid pair. Used during password reset process.
		$query = $this->db->prepare("SELECT `reset_pass_code` FROM `members` WHERE `email` = ?");
		$query->bindValue(1, $email);
		try{
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e){
			die($e->getMessage());
		}
		if($code == $data['reset_pass_code']){
			return true;
			exit;
		}
		else{
			return false;
			exit;
		}
	}
	public function password_reset_update($email, $password){
	//function to change a users password and purge reset code. Used during password reset process and user password reset once logged in
		global $bcrypt; 
		$password   = $bcrypt->genHash($password);// generating a hash using the $bcrypt object
		$query = $this->db->prepare("UPDATE `members` SET `password` = ?, `reset_pass_code` = ? WHERE `email` = ?");
		$query->bindValue(1, $password);
		$query->bindValue(2, "0");
		$query->bindValue(3, $email);
		
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
	}
	public function security_question($email, $action, $answer){
	//function to handle security question. takes user email and action as parameters. action defines if this will return the question, or check the answer.
		if($action == "get"){
			$query = $this->db->prepare("SELECT `question` FROM `members` WHERE `email` = ?");
			$query->bindValue(1, $email);
			try{
				$query->execute();
				$data = $query->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e){
				die($e->getMessage());
			}
			return $data['question'];
		}
		elseif($action == "check"){
			$query = $this->db->prepare("SELECT `answer` FROM `members` WHERE `email` = ?");
			$query->bindValue(1, $email);
			try{
				$query->execute();
				$data = $query->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e){
				die($e->getMessage());
			}
			if($answer == $data['answer']){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}	}
	public function hs_form_submit($data, $id){
	extract($data);
	//function to submit a user input health and safety form to the database
		$query = $this->db->prepare("UPDATE `members_hs_form` SET `q1` = ?, `q2` = ?, `q3` = ?, `q4` = ?, `q5` = ?, `q6` = ?, `q6_1`=?, `q7` = ?, `q8` = ?, `q9` = ?, `q10` = ?, `q11` = ?, `q12` = ?, `q13`=?,  `emerg_name` = ?, `emerg_prim_number` = ?, `emerg_alt_number` = ?,  `tc` = ? WHERE `id` = ?");
		$query->bindValue(1, $q1);
		$query->bindValue(2, $q2);
		$query->bindValue(3, $q3);
		$query->bindValue(4, $q4);
		$query->bindValue(5, $q5);
		$query->bindValue(6, $q6);
		$query->bindValue(7, $q6_1);
		$query->bindValue(8, $q7);
		$query->bindValue(9, $q8);
		$query->bindValue(10, $q9);
		$query->bindValue(11, $q10);
		$query->bindValue(12, $q11);
		$query->bindValue(13, $q12);
		$query->bindValue(14, $q13);
		$query->bindValue(15, $emerg_name);
		$query->bindValue(16, $emerg_prim_number);
		$query->bindValue(17, $emerg_alt_number);
		$query->bindValue(18, $tc);
		$query->bindValue(19, $id);
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
		$query = $this->db->prepare("UPDATE `members_hs` SET `hs_status` = ? WHERE `id` = ?");
		$query->bindValue(1, '1');
		$query->bindValue(2, $id);
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
		
		//update class variable to hold new hs status and data. Update session serialization.
		$this->hs_status = "1";
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
		$_SESSION['users_ser'] = serialize($this);
		return "yes";
	}
	public function update_account_info($data, $id){
	//function to update member account info in databases.
		//update members_data table
		$query = $this->db->prepare("UPDATE members_data set fname=?, sname=?, dob=?, address=?, town=?, postcode=?, contact_number=?, alt_contact_number=?, gender=? WHERE id=?");
		$query->bindParam(1, $data['fname']); 
		$query->bindParam(2, $data['sname']);
		$query->bindParam(3, $data['dob']);
		$query->bindParam(4, $data['address']);
		$query->bindParam(5, $data['town']);
		$query->bindParam(6, $data['postcode']);
		$query->bindParam(7, $data['contact_number']);
		$query->bindParam(8, $data['alt_contact_number']);
		$query->bindParam(9, $data['gender']);
		$query->bindParam(10, $id);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		//update members table
		$query = $this->db->prepare("UPDATE members set email=? WHERE id=?");
		$query->bindParam(1, $data['email']);
		$query->bindParam(2, $id);
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}
		//rerun login script to update all data stored in object, re serialize the session variable to hold new object.
		$this->login($id);
		$_SESSION['users_ser'] = serialize($this);
		return "yes";
	}




	
	//DEVELOPMENT TESTING FUNCTIONS.
	public function change_hs_status($id, $status){
		//parameters include user id and new status code
		$query = $this->db->prepare("UPDATE `members_hs` SET `hs_status` = ? WHERE `id` = ?");
		$query->bindValue(1, $status);
		$query->bindValue(2, $id);
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
		$this->hs_status = $status;
		$_SESSION['users_ser'] = serialize($this);
	}
	public function change_ws_status($id, $status){
		//parameters include user id and new status code
		$query = $this->db->prepare("UPDATE `members_ws` SET `ws_status` = ? WHERE `id` = ?");
		$query->bindValue(1, $status);
		$query->bindValue(2, $id);
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
		$this->ws_status = $status;
		$_SESSION['users_ser'] = serialize($this);
	}
}