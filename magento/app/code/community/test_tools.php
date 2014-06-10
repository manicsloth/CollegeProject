<?php 


class Test_tools{
	//class to hold various temporary testing functions and tools
 	private $db;

 	//database connection
 	public function __construct($database) {  
		$this->db = $database;
	}

	public function change_acc_status($id, $status){
		//parameters include user id and new status code
		$query = $this->db->prepare("UPDATE `members` SET `acc_status` = ? WHERE `id` = ?");
		$query->bindValue(1, $status);
		$query->bindValue(2, $id);
		try{
			$query->execute();	 
		} catch(PDOException $e){
			die($e->getMessage());
		}
	}

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
	}
}
