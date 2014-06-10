<?php 
//General class to contain functions etc not directly related to the user
class General{

	public function serializeUser($users){
		//Function to serialize user object and store it in session var
		$_SESSION['users_ser'] = serialize($users);
	}
	public function unserializeUser(){
		//Function to unserialize user object from session var and return it to calling script
		$users = unserialize($_SESSION['users_ser']);
		return $users;
	}
	public function serializeAdmin($admin){
		//Function to serialize admin user  object and store it in session var
		$_SESSION['admin_ser'] = serialize($admin);
	}
	public function unserializeAdmin(){
		//Function to unserialize admin object from session var and return it to calling script
		$admin = unserialize($_SESSION['admin_ser']);
		return $admin;
	}

}