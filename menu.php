<?php

echo "<ul id='nav'>";
   echo "<li>";
   	echo "<a href='home.php'>Home</a>";
   echo "</li>";
   echo "<li>";
   	echo "<a href='#'>About</a>";
      echo "<ul>";
         echo "<li>";
         	echo "<a href='whatisnordicwalking.php'>What is Nordic walking?</a>";
         echo "</li>";
         echo "<li>";
         	echo "<a href='doineedtobefit.php'>Do I need to be fit?</a>";
         echo "</li>";
         echo "<li>";
         	echo "<a href='awholeworldofbenefits.php'>A whole world of benefits</a>";
         echo "</li>";
         echo "<li>";
         	echo "<a href='hodoijoinyou.php'>How do I join you?</a>";
         echo "</li>";
         echo "<li>";
         	echo "<a href='kelly.php'>Kelly Bennett INWA Instructor</a>";
         echo "</li>";
         echo "<li>";
         	echo "<a href='suzanne.php'>Suzanne Allin BNW Walk Leader</a>";
         echo "</li>";
         echo "<li>";
         	echo "<a href='testimonials.php'>Testimonials</a>";
         echo "</li>";
      echo "</ul>";
   echo "</li>";
   echo "<li>";
   	echo "<a href='#'>What we offer</a>";
   	echo "<ul>";
   		echo "<li>";
   			echo "<a href='beginnersworkshops.php'>Beginners Workshops</a>";
   		echo "</li>";
   		echo "<li>";
   			echo "<a href='nordicwalks.php'>Nordic Walks</a>";
   		echo "</li>";
   		echo "<li>";
   			echo "<a href='parties.php'>Hen/Stag/Birthday Parties</a>";
   		echo "</li>";
   	echo "</ul>";
   echo "</li>";
   echo "<li>";
   	echo "<a href='#'>Nordic Walks</a>";
   	 echo "<ul>";
   	 walk_info();
	echo "</ul>";
   echo "</li>";
   echo "<li>";
   	echo "<a href='#'>Booking</a>";
   	echo "<ul>";
   		echo "<li>";
   			echo "<a href='beginnersworkshops.php'>Beginners Workshop</a>";
   		echo "</li>";
   		echo "<li>";
   			echo "<a href='booking.php'>Book Nordic Walks</a>";
   		echo "</li>";
   		echo "<li>";
   			echo "<a href='pricelist.php'>Pricing</a>";
   		echo "</li>";
   	echo "</ul>";
   echo "</li>";
   echo "<li>";
   	echo "<a href='#'>Products</a>";
   	echo "<ul>";
   		echo "<li>";
   			echo "<a href='nordicwalkingpoles.php'>Nordic Walking Poles</a>";
   		echo "</li>";
   		echo "<li>";
   			echo "<a href='clothing.php'>Clothing and Accesories</a>";
   		echo "</li>";
	echo "</ul>";
//Logged in user content
if($users->get_logged_in() == "true"){	
	echo "<li>";
		echo "<a href='member_home.php'>Members Area</a>";
	echo "</li>";
	echo "<li>";
		echo "<a href='account_info.php'>Account</a>";
	echo "</li>";
	echo "<li>";
		echo "<a href='logout.php'>Logout</a>";
	echo "</li>";

}
//Admin content
elseif($admin->get_logged_in() == "true"){
	echo "<li>";
		echo "<a href='admin_home.php'>Admin Area</a>";
	echo "</li>";
   echo "<li>";
      echo "<a href='walkedit.php'>Edit Walks</a>";
      echo "<ul>";
         echo "<li>";
            echo "<a href='add.php'>Add a Route</a>";
         echo "</li>";
         echo "<li>";
            echo "<a href='remove.php'>Remove a route</a>";
         echo "</li>";
         echo "<li>";
            echo "<a href='adminwalkinfo.php'>Edit route details</a>";
         echo "</li>";
      echo "</ul>";
   echo "</li>";
   echo "<li>";
      echo "<a href='alterdates.php'>Edit Events</a>";
      echo "<ul>";
         echo "<li>";
            echo "<a href='adddates.php'>Add Events</a>";
         echo "</li>";
         echo "<li>";
            echo "<a href='removedates.php'>Cancel Event</a>";
         echo "</li>";
         echo "<li>";
            echo "<a href='editdates.php'>Edit Event</a>";
         echo "</li>";
      echo "</ul>";
   echo "</li>";
	echo "<li>";
		echo "<a href='logout.php'>Logout</a>";
	echo "</li>";

}
//guest content
elseif($users->get_logged_in() == "false" && $admin->get_logged_in() == "false"){
	echo "<li>";
		echo "<a href='login.php'>Login</a>";
	echo "</li>";
	echo "<li>";
		echo "<a href='register.php'>Register</a>";
	echo "</li>";
	echo "<li>";
		echo "<a href='admin_login.php'>Admin Login</a>";
	echo "</li>";

}

echo "</ul>";
echo "</div>";