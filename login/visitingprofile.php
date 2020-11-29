<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}
require_once '../config.php';
$email = $_SESSION["email"];

$tovisit = $_GET['id'];
$imgtable = "`".$tovisit."_image`";
$myliketable = "`".$email."_liked`";
$metouser = "`".$email."vs".$tovisit."`";

if(isset($_POST["inb"])){
	$myinbox = "`".$email."_inbox`";
	if(!mysqli_query($link, "DESCRIBE $myinbox")) {
		mysqli_query($link, "CREATE TABLE $myinbox (id INT(255) PRIMARY KEY AUTO_INCREMENT, email VARCHAR(255), updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	}
	$fii = "SELECT * FROM $myinbox WHERE email = '$tovisit'";
	if(mysqli_query($link, $fii)) {
			mysqli_query($link,"UPDATE $myinbox SET updated_at=NOW() WHERE email = '$tovisit'");
	} 
	if(!mysqli_num_rows(mysqli_query($link, $fii)) > 0) {
		mysqli_query($link,"INSERT INTO $myinbox (email) VALUES ('$tovisit')");
	}
	if(!mysqli_query($link, "DESCRIBE $metouser")) {
		mysqli_query($link, "CREATE TABLE $metouser (id INT(255) PRIMARY KEY AUTO_INCREMENT, sent VARCHAR(255), recieved VARCHAR(255), seen VARCHAR(255), message_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	}

	header("location: inbox.php");
}

if(isset($_POST["lyes"])) {
	if(!mysqli_query($link, "DESCRIBE $myliketable")) {
		$query = "CREATE TABLE $myliketable (id INT(255) PRIMARY KEY AUTO_INCREMENT, email VARCHAR(255), liked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)";
		mysqli_query($link, $query);
	}
	$fil = "SELECT * FROM $myliketable WHERE email = '$tovisit'";
	if(!mysqli_num_rows(mysqli_query($link, $fil)) > 0) {
		mysqli_query($link,"INSERT INTO $myliketable (email) VALUES ('$tovisit')");
		echo '<script>alert("You just added this profile in your liked list.")</script>';
	} else {
		echo '<script>alert("You already liked this profile.")</script>';
	}
}

if(isset($_POST["lno"])) {
	if(!mysqli_query($link, "DESCRIBE $myliketable")) {
		echo '<script>alert("You did not like this profile yet.")</script>';
	} else {
		$fil = "SELECT * FROM $myliketable WHERE email = '$tovisit'";
		if(!mysqli_num_rows(mysqli_query($link, $fil)) > 0) {
			echo '<script>alert("You did not like this profile yet.")</script>';
		} else {
			mysqli_query($link,"DELETE FROM $myliketable WHERE email = '$tovisit'");
			echo '<script>alert("You just removed this profile from your liked list.")</script>';
		}
	}

}

$find = "SELECT * FROM userdetails WHERE email = '$tovisit'";
if($result = mysqli_query($link, $find)){
	if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_array($result);
		$name = $row['name'];
		$age = $row['age'];
		$address = $row['address'];
		$pro = $row['pro'];
		$dowry = $row['dowrylist'];
		$gender = $row['gender'];
		$lgender = $row['lookingfor'];
		$status = $row['status'];
		$pim = $row['image'];
	} else {
		echo '<script>alert("Database not found.")</script>';
	}	
}

$visitortable = "`".$tovisit."_visitor`";
if(!mysqli_query($link, "DESCRIBE $visitortable")) {
	$query = "CREATE TABLE $visitortable (id INT(255) PRIMARY KEY AUTO_INCREMENT, email VARCHAR(255), visited_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)";
	mysqli_query($link, $query);
}
$findymail = "SELECT * FROM $visitortable WHERE email = '$email'";
if(mysqli_query($link, $findymail)) {
		mysqli_query($link,"UPDATE $visitortable SET visited_at=NOW() WHERE email = '$email'");
} 
if(!mysqli_num_rows(mysqli_query($link, $findymail)) > 0) {
	mysqli_query($link,"INSERT INTO $visitortable (email) VALUES ('$email')");
}

if(isset($_POST["sr"])) {
	$rc = $_POST["sd"];
	$rtable = "report_profile";
		if(!mysqli_query($link, "DESCRIBE $rtable")) {
			mysqli_query($link,"CREATE TABLE $rtable (id INT(255) PRIMARY KEY AUTO_INCREMENT, email VARCHAR(255), report_cause VARCHAR(255))");
		} else{
		}
		if(mysqli_query($link,"INSERT INTO $rtable (email, report_cause) VALUES ('$tovisit', '$rc')")){
			echo '<script>alert("Report sent to admin.")</script>';
		} else {
			echo '<script>alert("Do not use special characters.")</script>';
		}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Traditional Marriage Management Site</title>

	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css" type="text/css" media="all">
	<link rel="stylesheet" href="../css/font-awesome.css">
	<link rel="stylesheet" href="../css/swipebox.css">
	<link rel="stylesheet" href="../css/jquery-ui.css">
</head>
<body>
	<div class="sidenav">
		<img src="../img/demo.jpeg" alt="Avatar" class="avatar" style="width: 130px; height: 130px; margin-left: 25px; padding-bottom: 5px;">
		<a href="profile.php" style="border-top: 1px solid #818181">Profile</a>
		<a href="editprofile.php">Edit Profile</a>
		<a href="youliked.php">Liked People</a>
		<a href="visitor.php">Visitor</a>
		<a href="find-partner.php">Find Your Partner</a>
		<a href="inbox.php">Inbox</a>
		<a href="contactto.php">Contact Authority</a>
		<a href="logout.php">Logout</a>
	</div>
	<div class="pro" style="padding-top: 20px;">
		<div class="col-md-12">
			<div class="col-md-2">
				<?php
				echo '<img src="data:image/jpeg;base64,'.base64_encode($pim).'" alt="Profile picture" style="width: 100%; height: 150px;">';
				?>
			</div>
			<div class="col-md-10">
				<h2 style="padding-top: 10px; margin-left: 4px;">Name: <span><?php echo "$name"; ?></span></h2>
				<p style="font-weight: 700; margin-left: 4px;">Age: <span><?php echo "$age"; ?></span>; Gender: <span><?php echo "$gender"; ?></span>; Looking for <span><?php echo "$lgender"; ?></span>;</p>
				<p style="font-weight: 700; margin-left: 4px;">Address: <span><?php echo "$address"; ?></span>;</p>
				<p style="font-weight: 500; margin-left: 4px;"><span style="font-weight: 700;">Details: </span><span><?php echo "$pro"; ?>.</span></p>
				<p style="font-weight: 700; margin-left: 4px;">Status: <span><?php echo "$status"; ?></span>;</p>
			</div>
		</div>
		<div class="col-md-12">
			<h2 style="margin-top: 4px;">List of Dowry</h2>
			<p style="font-weight: 700;"><?php echo "$dowry"; ?></p>
		</div>
		<div class="col-md-12">
			<div class="col-md-4">
			<form method="post">
				<p style="font-weight: 700; padding-top: 10px;">Click to <span><button type="submit" name="inb">inbox</button></span> this profile</p>
			</form>
			</div>

			<div class="col-md-4">
				<p style=" font-weight: 700; padding-top: 10px;">Click to <span><button type="submit" name="reb" onclick="ead()">report</button></span> this profile</p>
			<form method="post" class="hide" id="sad" style="width: 80%; margin-top: 10px;">
				<input type="text" name="sd" required="" placeholder="Cause of reporting">
				<button name="sr" type="submit" style="margin-left: 25%;">Send Report</button>
			</form>
			</div>
			<div class="col-md-4">
			<form method="post">
					<p style="padding-right: 20%;"><span style="font-weight: 700;">Do you like this profile?</span><br><span style="padding-right: 30px; padding-left: 10px;"><button type="submit" name="lyes">Yes</button></span><span><button type="submit" name="lno">No</button></span></p>
				</form>
			</div>
		</div>
		<div class="col-md-12">
			<div class="col-md-6">
				<h2 style="margin-top: 25px; margin-left: -15px;">Gallary</h2>
			</div>
			<div class="col-md-6">
			</div>
		</div>
		<div class="col-md-12 ">
			<div class="gallary">
				<?php
					$resultim = mysqli_query($link, "SELECT * FROM $imgtable ORDER BY uploaded_at DESC");
					
					if(!mysqli_num_rows($resultim) > 0) {
						echo '
							<h2 style="text-align: center; padding-top: 50px;">No photo uploaded yet</h2>
						';
					} else {
						while ($rowim = mysqli_fetch_array($resultim)) {
							echo '
								<img src="data:image/jpeg;base64,'.base64_encode($rowim['image'] ).'" >
							';	
						}
					}
				?>
			</div>
			
							
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	function ead(){
		document.getElementById('sad').className="sho";
	}
</script>