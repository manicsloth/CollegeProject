<?php
class Bcrypt {
	private $rounds;
	public function __construct($rounds = 12) {
		if(CRYPT_BLOWFISH != 1) {
			throw new Exception("Bcrypt error.");
		}
		$this->rounds = $rounds;
	}
 

	private function genSalt() {
 		//function to generate salt 
		$string = str_shuffle(mt_rand());// generating a random string
		$salt 	= uniqid($string ,true);// generating a random and unique string
		return $salt;
	}
 

	public function genHash($password) {
		//function to generate hash
		/* 2y selects bcrypt algorithm */
		/* $this->rounds is the workload factor, which is kept usually from 12 to 15 */
 
		$hash = crypt($password, '$2y$' . $this->rounds . '$' . $this->genSalt());
		return $hash;
	}
	
	public function verify($password, $existingHash) {
		//function to compare hashed passwords (at user login)
		/* Hash new password with old hash */
		$hash = crypt($password, $existingHash);
		
		/* Do Hashs match? */
		if($hash === $existingHash) {
			return true;
		} else {
			return false;
		}
	}
}
?>