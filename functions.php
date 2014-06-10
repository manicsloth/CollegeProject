<?php

//db connection info
function db_connect() {
	$config['db'] = array(
		'host'		=> 'localhost',
		'username'	=> 'manic_sloth',
		'password'	=> '123fistandantilus!',
		'dbname'	=> 'manic_nordic'
		);
	$nordic_db= new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['username'],$config['db']['password']);
	return $nordic_db;
}
// Connect to member database
function member_connect() {
	$config['db'] = array(
		'host'		=> 'localhost',
		'username'	=> 'manic_sloth',
		'password'	=> '123fistandantilus!',
		'dbname'	=> 'manic_members'
		);
	$member_db= new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['username'],$config['db']['password']);
	return $member_db;
}
//pulls walks for menu
function walk_info() {			
	$nordic_db=db_connect();
	$query = $nordic_db->prepare("select `id` , `location` , `wname` , `meet` , `directions` , `details` , `dogs` , `wtime` , `distance` , `imageurl` , `url` from `walks`");
	$query->execute();
		while($data= $query ->fetch(PDO::FETCH_ASSOC)) {
			echo "<li>";
			echo "<a href='walkpage.php?id=$data[id]'>$data[wname]</a>";
			echo "</li>";
		}
}
function walkFetch() {
	$nordic_db=db_connect();
	$query = $nordic_db->prepare("select `id` , `location` , `meet` , `directions` , `details` , `dogs` , `wtime` , `distance` , `imageurl` , `url` from `walks`");
	$query->execute();
	while($data= $query ->fetch(PDO::FETCH_ASSOC)) {

			

          echo "<div class='routes'>";
          	echo "<table border=1>";
          		echo "<tr>";
          			echo "<td>Location:</td>";

          			echo "<td>$data[location]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Meeting Point:</td>";
          			echo "<td>$data[meet]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Directions:</td>";
          			echo "<td>$data[directions]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>About:</td>";
          			echo "<td>$data[details]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Dogs:</td>";
          			echo "<td>$dogs</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Walk Time:</td>";
          			echo "<td>$data[wtime]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Distance:</td>";
          			echo "<td>$data[distance]</td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Map:</td>";
          			echo "<td><a href='$data[url]'>Map</a></td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td>Edit this walk?</td>";
          			echo "<td><input type='radio' value=$data[id]></td>";
          		echo "</tr>";
          		echo "<tr>";
          			echo "<td><input type='submit' name='id' value=$data[id]></td>";
          		echo "</tr>";
          	echo "</table>";
          echo "</div>";
    }
	
	
} 	
function walkInfo() {
     $nordic_db=db_connect();
     $query = $nordic_db->prepare("select `location` , `meet` , `directions` , `details` , `dogs` , `wtime` , `distance` , `imageurl` , `url` from `walks`");
     $query->execute();
     while($data= $query ->fetch(PDO::FETCH_ASSOC)) {

               

          echo "<div class='routes'>";
               echo "<table border=1>";
                    echo "<tr>";
                         echo "<td>Location:</td>";

                         echo "<td>$data[location]</td>";
                    echo "</tr>";
                    echo "<tr>";
                         echo "<td>Meeting Point:</td>";
                         echo "<td>$data[meet]</td>";
                    echo "</tr>";
                    echo "<tr>";
                         echo "<td>Directions:</td>";
                         echo "<td>$data[directions]</td>";
                    echo "</tr>";
                    echo "<tr>";
                         echo "<td>About:</td>";
                         echo "<td>$data[details]</td>";
                    echo "</tr>";
                    echo "<tr>";
                         echo "<td>Dogs:</td>";
                         echo "<td>$dogs</td>";
                    echo "</tr>";
                    echo "<tr>";
                         echo "<td>Walk Time:</td>";
                         echo "<td>$data[wtime]</td>";
                    echo "</tr>";
                    echo "<tr>";
                         echo "<td>Distance:</td>";
                         echo "<td>$data[distance]</td>";
                    echo "</tr>";
                    echo "<tr>";
                         echo "<td>Map:</td>";
                         echo "<td><a href='$data[url]'>Map</a></td>";
                    echo "</tr>";
               echo "</table>";
          echo "</div>";
    }
     
     
} 
function updateWalks($id, $location, $meet, $directions, $details, $dogs, $wtime, $distance, $imageurl, $url){
     $nordic_db = db_connect();
     $query=$nordic_db->prepare("insert into walks (id,location,meet,directions,details,dogs,wtime,distance,imageurl,url) values (?,?,?,?,?,?,?,?,?,?)");
     $query->execute(array($id, $location, $meet, $directions, $details, $dogs, $wtime, $distance, $imageurl, $url));

}

