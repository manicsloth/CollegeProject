<?php
class walk {
	// Creates a set of objects from the walks database, makes each walk an object. Also creates an index array of walk id's/object id's
	protected $id;
	protected $wname;
	protected $location;
	protected $meet;
	protected $directions;
	protected $details;
	protected $dogs;
	protected $wtime;
	protected $distance;
	protected $imageurl;
	protected $url;

	public function spitJson($input) {
		// Takes the objects and iterates them so that json_encode can pass them accross pages correctly
		if(!is_array($input) && !is_object($input)){   
            return json_encode($input);   
        }else{   
            $pieces = array();   
            foreach($input as $k=>$v){   
                $pieces[] = "\"$k\":".$this->spitJson($v);   
            }   
            return '{'.implode(',',$pieces).'}';   
        }   
    }   
	
	public function unspitJson($walks) {
		$walks = json_decode($walks);
		return $walks;
	}
	public function spitObjects($walks,$count) {
		// iteratively named walk objects created
		${'walk' . $count} = new walk;
		${'walk' . $count} = $walks[$count];
		return ${'walk' . $count};

	}

	private function makeNew(){
		// new object
		$walk1 = new walk;
		$walk1=$this;
		return $walk1;
	}
	public function retrieveData() {
		//Populate initial array from database
		$count = 1;
		$nordic_db=db_connect();
		$query = $nordic_db->prepare("select `id`, `wname` , `location`, `meet`, `directions`, `details`, `dogs`, `wtime`, `distance`, `imageurl`, `url` from `walks`");
		$query->execute();
						
		foreach ($query->fetchAll(PDO::FETCH_CLASS, 'walk') as $r) {
			$walk1[$count] = $r->makeNew(); // call the class method
			$walkIndex[$count]=$r->spitId();
						$count++;
		}
		$walk1[$count] = &$walkIndex;
		$count--;
		$walk1[0] = $count;
			return $walk1;	
	}
	//-----------------------------------------------------------------------//
	//      The following spits out individual properties of the object       //
	//-----------------------------------------------------------------------//
	public function spitId(){
		return $this->id;
	}
	public function spitWname(){
		return $this->wname;
	}
	public function spitLocation(){
		return $this->location;
	}
	public function spitMeet(){
		return $this->meet;
	}
	public function spitDirections(){
		return $this->directions;
	}
	public function spitDetails(){
		return $this->details;
	}
	public function spitDogs(){
		return $this->dogs;
	}
	public function spitWtime(){
		return $this->wtime;
	}
	public function spitdistance(){
		return $this->distance;
	}
	public function spitImageurl(){
		return $this->imageurl;
	}
	public function spitUrl(){
		return $this->url;
	}
	

}

class dates {
	// Creates a set of objects from the dates database, each walk instance is a separate object. 
	protected $wdate;
	protected $time;
	protected $id;
	protected $unqid;
	protected $ids;
	protected $walkercount;

	public function spitArray() {
		//create an array of all object variables
		$object = get_object_vars($this);
		return $object;
	}

	public function spitJson($input) {
		if(!is_array($input) && !is_object($input)){   
            return json_encode($input);   
        }else{   
            $pieces = array();   
            foreach($input as $k=>$v){   
                $pieces[] = "\"$k\":".$this->spitJson($v);   
            }   
            return '{'.implode(',',$pieces).'}';   
        }   
    }   
	
	public function retrieveData() {
		// Gets all walk dates from today onwards
		$today = array();
		$today = date("Y-m-d");
		$counter = 1;
		$nordic_db=db_connect();
		$query = $nordic_db->prepare("select `wdate`, `time`, `id`, `unqid`, `ids`, `walkercount` from `dates` where `wdate` >= ?");
		$query->execute(array($today));
		foreach ($query->fetchAll(PDO::FETCH_CLASS, 'dates') as $t) {
			$dates1[$counter] = $t->makeNew();
			$counter++;
		}
		$counter--;
		$dates1[0] = $counter;
		return $dates1;
	}
	private function makeNew(){
		// creates date objects
		$dates1 = new walk;
		$dates1=$this;
		return $dates1;
	}

	public function spitObjects($dates1,$counter) {
		// throw out iteratively named objects for each date
		${'date' . $counter} = new dates;
		${'date' . $counter} = $dates1[$counter];
		return ${'date' . $counter};

		}
	public function insertDates($wdate, $time, $id, $walkerlimit, $polesavailable){
		$nordic_db = db_connect();
		$query = $nordic_db->prepare("insert into dates (wdate,time,id,walkerlimit,polesavailable) values (?,?,?,?,?)");
		$query->execute(array($wdate, $time, $id, $walkerlimit, $polesavailable));
	}
}

class members{
	protected $hs_status;
	protected $workshop_comp;

	public function checkStatus($id){
		$nordic_db=member_connect();
		$query = $nordic_db->prepare("select `hs_status` , `workshop_comp` from `members` where `id` = ?");
		$query->execute(array($id));
		$temp = $query->fetch(PDO::FETCH_ASSOC);
		
		if ( $temp['hs_status'] != 2 ){
			if ( $temp['hs_status'] != 1){
				$status = 1;
				return $status;
			}else {
				$status = 2;
				return $status;
			}
		}else{
			if ( $temp['workshop_comp'] > "n" ){
				$status = 4;
				return $status;
			} else{
				$status = 3;
				return $status;
			}
		}
	}
	public function checkCredits($id, $booked){
		$nordic_db=member_connect();
		$query = $nordic_db->prepare("select `credits` from `members` where `id` = ?");
		$query->execute(array($id));
		$temp = $query->fetch(PDO::FETCH_ASSOC);
		$remain = $temp['credits'] - $booked;
		return $remain;
	}
	public function updateCredits($id, $creditBalance){
		$nordic_db=member_connect();
		$query = $nordic_db->prepare("update `members` set `credits` = ? where `id` = ?");
		$query->execute(array($creditBalance,$id));

	}

}
class bookingTemp{
	public function getAttempt($id){
		$nordic_db = db_connect();
		$query = $nordic_db->prepare("select ids from bookingtemp where id = ?");
		$query->execute(array($id));
		$temp = $query->fetch(PDO::FETCH_ASSOC);
		return $temp["ids"];
	}
	public function insert($id, $booked, $ids, $date){
		$nordic_db=db_connect();
		$prepare = $nordic_db->prepare("delete from bookingtemp where `id` = ?");
		$prepare->execute(array($id));
		$query=$nordic_db->prepare("insert into bookingtemp (id,count,ids,date) values (?,?,?,?)");
		$query->execute(array($id, $booked, $ids, $date));
	}
}

class bookingInsert{
	
	public function update($id, $walk){
		$nordic_db=db_connect();
		$prepare = $nordic_db->prepare("update `dates` set `ids` = ? , `walkercount` = `walkercount` + 1 where `unqid` = ?");
		$retrieve = $nordic_db->prepare("select `wdate` , `time` , `id` , `ids` from `dates` where `unqid` = ?");
		$bookingholder = "";
		foreach( $walk as $w) {
			//Store the booking onto the date and increase walkercount
			$retrieve->execute(array($w));
			$retrieved = $retrieve->fetch(PDO::FETCH_ASSOC);
			$s = $id . "," . $retrieved['ids'];
			$prepare->execute(array($s, $w));
			//Prepare database entry for member personal walk booking list and put into database
			$stringy = $retrieved['time'];
			$stringy=str_replace(":","",$stringy);
			$stringy = substr($stringy, 0, 4);
			$strungy = $retrieved['wdate'];
			$strungy = str_replace("-","",$strungy);
			$strungy = substr($strungy, 2, 6);
			$stringy = $stringy . "|" . $strungy . "|" . $retrieved['id'] . ",";
			$bookingholder = $bookingholder . $stringy;
			}
	return $bookingholder;
	}

}

?>