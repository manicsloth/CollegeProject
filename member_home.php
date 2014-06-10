<?php 
	require 'core/init.php';
	$users->logged_out_protect();
	$title="Walk Kernow Members Page";
	require "header.php";
	//TEST BUTTONS TO SKIP AUTHORIZATION STEPS
	if(isset($_GET['skip'])){
		switch ($_GET['skip']){
			case "hs":
				$users->change_hs_status($users->get_id(), "2");
				break;
			case "ws":
				$users->change_ws_status($users->get_id(), "2");
				break;
		}
	}
	//check for walk cancellation confirm - used in the event javascript is disabled.
	if(isset($_GET['cancel_confirm'])){
		echo "<div id='content'>";
		echo "Are you sure you want to cancel your booking for the walk displayed below. This cannot be undone, although you can rebook should you change your mind. Any credits spent will be refunded.<br /><br />$_GET[location] at $_GET[time] $_GET[date]";
		echo "<br />";
		echo "<a href='member_home.php?cancel=$_GET[cancel_confirm]&&location=$_GET[location]'><button>Confirm Cancellation</button></a>";
		echo "<a href='member_home.php'><button>Go back</button></a>";
		echo "</div>";
		exit;
	}
	//check for walk cancellation GET data
	if(isset($_GET['cancel']) && isset($_GET['location'])){
		require "bookingclasses.php";
		$dates = new dates;
		$dates->walkCancel($users->get_id(), $_GET['cancel']);
		$_SESSION['notification'] = "We have canceled your booking for the walk at $_GET[location] as requested. Any credits spent have been refunded.";
		header('Location:member_home.php');
	}
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
	$( document ).ready(function() {
		var count = document.getElementsByClassName('cancel_js');
		for (var i=0;i<count.length; i++) {
			document.getElementsByClassName("cancel_js")[i].removeAttribute("href"); 
		}
	});
	function confirmCancel (walk, location, date, time){
		var x = confirm('Are you sure you want to cancel your booking for the walk displayed below. This cannot be undone, although you can rebook should you change your mind. Any credits spent will be refunded.\n\n'+location+' at '+time+' '+date);
		if( x ===true){
			window.location = "member_home.php?cancel="+walk+"&&location="+location;
		}
		else{
			return false;
		}
	}
</script>
	<div id="content">
		<h1> Welcome, <?php echo $users->get_fname();?></h1>
		<?
		//NOTIFICATION AREA
		//If no session notification then check various account status and display relevent notifications.
		if($_SESSION['notification']){
			$notification = $_SESSION['notification'];
			unset($_SESSION['notification']);
		}
		elseif($users->get_hs_status() =="0"){//no hs form submitted
			$notification = "Now you have successfully registered and log in, great! The next step is to complete a Physical Activity Readiness Questionnaire to be reviewed by one of our Instructors.<br />Please click here to go to the form: <a href='parq.php'><button>Physical Activity Readiness Questionnaire</button></a>";
		}
		elseif($users->get_hs_status() =="1"){//hs form submitted, waiting for admin to review
			$notification = "Thankyou for sending your Physical Activity Readiness Questionnaire to us. One of our Instructors will review is as soon as possible and you will be update by email.<br />Please note that all information is stored confidentially and will not be view by anyone other than staff at Walk Kernow.";
		}
		elseif($users->get_ws_status() =="0"){//hs form accepted, awaiting booking of workshop
			$notification = "Our Instructors have verified your Physical Activity Readiness Questionnaire. You can now start Nordic Walking! Before you can join us on a trip you will need to learn how to Nordic Walk - We provide Beginners Workshops that you can book onto using the button below or alternatively if you already have experience please contact us and let us know.";
		}
		elseif($users->get_ws_status() =="1"){//hs form accepted, workshop booked but not completed
			$notification = "Thanks for booking onto one of our Beginners Workshop, we hope to see you soon! Once you are done an Instructors will authorize your account to book onto Nordic Walks.";
		}

		if(isset($notification) && !empty($notification)){
			echo "<p id='notification'>" . $notification . "</p>";
		}
		?>
		
		<p> This is your personal user area. From here you can access the various account tools and sign up to walk events.</p>
		<h3>Your current credit balance: <?php echo $users->get_credits(); ?></h3>
		<h3>Your Referal Link</h3>
		<p>Send the link below to your friends and if they complete one of our Beginners Workshops when they book their first Nordic Walk you will receive a free walk!
		<br />http://ns6577.hostgator.com/~manic/register.php?refer=<?php echo $users->get_email();?></p>
		<h3> Walk Bookings</h3>
		<?php  
			//load files for retrieving walk data
			require 'bookingclasses.php';
			require 'walksforhistory.php';
		
			//show table of walks user is booked onto
			$bookings =  $users->get_bookings();
			//obtain string of walk data and split into individual walks
			$bookings = explode(",", $bookings);
			//remove blank entry at end of array
			if(empty($bookings[count($bookings)-1])) {
			    unset($bookings[count($bookings)-1]);
			}
			if(empty($bookings)){
				echo "<p>You are not booked onto any walks at this time.</p>";
			}
			else{
				echo "<p>You are currently booked onto the following walks: </p>";
				echo "<table id='walks_booked'>
				<tr><th>Date</th><th>Location & Meeting Place</th><th>Distance*</th><th>Dogs?</th><th>Map</th></tr>";
				foreach ($bookings as $data) {
					//split walk details apart from string
					$cols =explode("|", $data);
					//format time
					$time = join(':', str_split($cols['0'], 2));
					//format date
					$date = join('/', str_split($cols['1'], 2));
					$walkid = $cols['2'];
					$location = ${'walk' . $walkid}->spitLocation();
					$meet = ${'walk' . $walkid}->spitMeet();
					$distance = ${'walk' . $walkid}->spitDistance();
					$walktime = ${'walk' . $walkid}->spitWtime();
					$dogs = ${'walk' . $walkid}->spitDogs();
					switch ($dogs) {
						case 'y':
							$dogs="Dogs are Allowed";
							break;
						default:
							$dogs="Dogs are Not Allowed";
							break;
					}
					$map = ${'walk' . $walkid}->spitUrl();
					echo "<tr><td>$date<br />$time</td><td><strong>$location.</strong><br />$meet</td><td>$distance<br/>$walktime</td><td>$dogs</td><td><a href='$map' target='_blank'>Open Map</a></td><td>"; ?><a class="cancel_js" href="<?php echo "member_home.php?cancel_confirm=$data&&location=$location&&date=$date&&time=$time";?>" onclick="confirmCancel('.<?php echo $data;?>', '<?php echo $location;?>', '<?php echo $date;?>', '<?php echo $time;?>')"><?php echo "<img src='images/icons/cross.png' alt='Cancel' class='cancel_walk'/></a></td></tr>";			
				}
				echo "</table>";
				echo "<p style='font-size:12px;'>*Distance and walk times are approximate.</p>";
			}
		?>
	
		<a href="#"><button class="event"> Book a workshop</button></a>
		<a href="booking.php"><button class="event"> Book a walk</button></a>

		
	</div>

<?php include 'footer.php'; ?>	
</body>
</html>