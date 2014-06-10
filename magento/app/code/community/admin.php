<?php 



//create class Users. This will contain functions and scripting relating to the member that is currently logged in.

class Admin{

 	private $db;



 	//ADMIN user OBJECT DATA

	private $logged_in= "false";

	//admins tables fields

	private $id, $username, $email, $permissions;



 	public function __construct($database) {  

	    $this->db = $database;

	}

	

	public function __sleep(){

		unset($bcrypt);

		return array('logged_in', 'id', 'username', 'email', 'permissions');

	}

	public function __wakeup(){

		Global $db;

		$this->db = $db;

		unset($db);

	}



	//private variable getters

	public function get_logged_in(){

		return $this->logged_in;

		exit;

	}

	public function get_id(){

		return $this->id;

		exit;

	}

	public function get_username(){

		return $this->username;

		exit;

	}

	public function get_email(){

		return $this->email;

		exit;

	}

	public function get_permissions(){

		return $this->permissions;

		exit;

	}







	public function logged_out_protect() {

	//To protect areas that non logged in users are restricted from. redirects to index

		if ($this->logged_in == "false") {

			header('Location: admin_login.php');

			exit();

		}	

	}

	public function logged_in_protect() {

		//To protect areas that logged in users are restricted from (e.g. registration pages).

		if ($this->logged_in == "true") {

			header('Location: admin_home.php');

			exit();		

		}

	}

	public function permission_check($req_perms){

	//function to check if currently logged in admin user has the require permissions to access a certain area. Parameters provided are the permission that the calling script wants to check for. 



		$permissions= $this->permissions;

		//if character Z is found then return yes and end, (Z is all perms flag)

		if(strpos($permissions, 'Z')  !== false){

			$result = "yes";

			return $result;

			end;

		}

		//if the specified character is found, return yes

		elseif(strpos($permissions, $req_perms)  !== false){

			$result = "yes";

			return  $result;

			end;

		}

		//otherwise return no

		else{

			$result = "no";

			return $result;

			end;

		}			

	}



	public function login($id){

		//function to update and set the object variables when logging a user in

		$this->logged_in = "true";

		

		//grab users data from db and store it in object variables.

		$data = $this->retrieve_user_data("admins", $id);

		foreach ($data as $key => $value) {

			$this->$key= $value;

		}



	}



	public function login_credentials($username, $password) {

	 //function to check if login credentials are correct. Returns user id if they are

		global $bcrypt; 



		$query = $this->db->prepare("SELECT `id`, `password` FROM `admins` WHERE `username` = ?");

		$query->bindValue(1, $username);



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



	public function retrieve_user_data($table_name, $id) {

	 //function to retrieve a members  data from supplied database using id no.

		$query = $this->db->prepare("SELECT * FROM $table_name WHERE `id`= ?");

		$query->bindValue(1, $id);

	 

		try{

			$query->execute();

			$data = $query->fetch(PDO::FETCH_ASSOC);

			

			if (isset($data['password'])){

				unset($data['password']);  //purge password if exists before returning.

			}

			

			return $data;

		} catch(PDOException $e){

			die($e->getMessage());

		}

	}

	public function email_exists($email) {

	 //function to check if an email is used by an admin account

		$query = $this->db->prepare("SELECT COUNT(`id`) FROM `admins` WHERE `email`= ?");

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

		} catch (PDOException $e){

			die($e->getMessage());

		}

	}



	public function password_reset_email($email){

	//function to send admin user an email during password reset process

		$unique = uniqid('',true); // generate a unique string

		$random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0, 10); // generate a more random string

		$generated_string = $unique . $random; // a random and unique string

		 

		$query = $this->db->prepare("UPDATE `admins` SET `reset_pass_code` = ? WHERE `email` = ?");

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

		$message = Swift_Message::newInstance('Walk Kernow Password Reset') ->setFrom(array('webkrunch@gmx.co.uk' => 'Walk_Kernow_Alpha')) ->setTo(array('ab122701@ghs.truropenwith.ac.uk')) ->setBody("Hello " . $email. ",\r\nWe have received a request to reset your password. If this was you, please visit the link below and enter the code displayed below.\r\n\r\nhttp://localhost/web/walk_kernow/admin_site/admin_password_reset.php?email=" . $email . "&&code=" .$generated_string . " \r\n\r\n-- Walk Kernow (Alpha)");

		$result = $mailer->send($message);



	}



	public function password_reset_validate($email, $code){

	//function to check if a supplied email and password reset code are a valid pair. Used during admin password reset process.

		$query = $this->db->prepare("SELECT `reset_pass_code` FROM `admins` WHERE `email` = ?");

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

	//function to change admin users password and purge reset code. Used during admin password reset process and admin password reset once logged in
		global $bcrypt; 
		$password   = $bcrypt->genHash($password);// generating a hash using the $bcrypt object
		
		$query = $this->db->prepare("UPDATE `admins` SET `password` = ?, `reset_pass_code` = ? WHERE `email` = ?");
		$query->bindValue(1, $password);
		$query->bindValue(2, "0");
		$query->bindValue(3, $email);
		
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
		return "yes";
		exit;	
	}



	public function change_password_validate($username, $password){

	//function to check if a supplied password is correct to an username during admin password change

		global $bcrypt; 



		$query = $this->db->prepare("SELECT `password` FROM `admins` WHERE `username` = ?");

		$query->bindValue(1, $username);



		try{

			$query->execute();

			$data = $query->fetch(PDO::FETCH_ASSOC);

			$stored_password = $data['password'];

			

			//hashing the supplied password and comparing it with the stored hashed password.

			if($bcrypt->verify($password, $stored_password) === true){

				return true;

			}else{

				return false;	

			}

			

		}catch(PDOException $e){

			die($e->getMessage());

		}

	}



	public function password_change($username, $password){

	//function to change admin users password during password change process

		global $bcrypt; 

		$password   = $bcrypt->genHash($password);// generating a hash using the $bcrypt object



		$query = $this->db->prepare("UPDATE `admins` SET `password` = ? WHERE `username` = ?");

		$query->bindValue(1, $password);

		$query->bindValue(2, $username);

		

		try{

			$query->execute();	 

		} catch(PDOException $e){

			die($e->getMessage());

		}

	}







}