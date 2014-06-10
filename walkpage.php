<?php 
	require 'core/init.php';
	require_once 'functions.php';
	require 'bookingclasses.php';
	require 'walksforhistory.php';


	//$title = "Walk Kernow";
	$title= ${'walk' . $_GET['id']}->spitWname();
	require "header.php";

?>
<div id="content">
	<div id="content1">
		<img src='images/walks/<?php echo ${'walk' . $_GET['id']}->spitImageUrl(); ?>'>
		<iframe src="http://maps.google.com/maps?q=http://ns6577.hostgator.com/~manic/test.kml&output=embed" width="365" height="300" class="map"></iframe>
		<br />

	</div>
	<div id="content2">
		<h1>
			<?php echo ${'walk' . $_GET['id']}->spitWname(); ?>
		</h1>
		<h3>
			Meeting Place:
		</h3>
		<p>
			<?php echo ${'walk' . $_GET['id']}->spitMeet(); ?>
		</p>
		
		<h3>
			Directions:
		</h3>
		<p>
			<?php echo ${'walk' . $_GET['id']}->spitDirections(); ?>
		</p>
		
		<h3>
			About...
		</h3>
		<p>
			<?php echo ${'walk' . $_GET['id']}->spitDetails(); ?>
			<br /><br /><strong><?php if(${'walk' . $_GET['id']}->spitDogs() == "y"){ echo "WELL BEHAVED DOGS ARE WELCOME ON THIS WALK";}else{ echo "DOGS ARE NOT PERMITTED ON THIS WALK";} ?></strong>
			<br /><br />If you are not sure if this walk is suitable for you please ask.
			<br /><br />Approximate distance: <?php echo ${'walk' . $_GET['id']}->spitDistance(); ?>, <?php echo ${'walk' . $_GET['id']}->spitWtime(); ?>
		</p>
	</div>
	

<div class="clear"></div>



</div>
<?php include 'footer.php'; ?>
</body>
</html>