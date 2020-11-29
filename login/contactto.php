<?php
session_start();
error_reporting(0);
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}

require_once '../config.php';
$email = $_SESSION["email"];
$metoadmin = "`".$email."vsadmin`";
$admininbox = "admin_inbox";
if(isset($_POST["send"])) {
	$message = $_POST["write_text"];
	if(!mysqli_query($link, "DESCRIBE $admininbox")) {
		mysqli_query($link, "CREATE TABLE $admininbox (id INT(255) PRIMARY KEY AUTO_INCREMENT, email VARCHAR(255), updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	}
	$fii = "SELECT * FROM $admininbox WHERE email = '$email'";
	if(mysqli_query($link, $fii)) {
			mysqli_query($link,"UPDATE $admininbox SET updated_at=NOW() WHERE email = '$email'");
	} 
	if(!mysqli_num_rows(mysqli_query($link, $fii)) > 0) {
		mysqli_query($link,"INSERT INTO $admininbox (email) VALUES ('$email')");
	}
	if(!mysqli_query($link, "DESCRIBE $metoadmin")) {
		mysqli_query($link, "CREATE TABLE $metoadmin (id INT(255) PRIMARY KEY AUTO_INCREMENT, message VARCHAR(255), sentby VARCHAR(255), seen VARCHAR(255), message_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	}
	if(mysqli_query($link,"INSERT INTO $metoadmin (message, sentby, seen) VALUES ('$message','$email', 'No')")){

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
	<div class="pro">
		<div class="col-md-12" style="">
			<div class="mh" style="top: 0; width: 100%; z-index: 9; background-color: #ffffff; position: fixed; padding-top: 20px; padding-bottom: 10px;">
				<h2 style="margin-left: 9%;">Write to <span>Admin</span></h2>
			</div>
		</div>
		<div id="scroll" class="col-md-12" style="overflow-x: hidden; width: 550px; z-index: -1; margin-bottom: 65px;"> 
				<?php 

					$find1 = "SELECT * FROM userdetails WHERE email = '$email'";
					if($result3 = mysqli_query($link, $find1)) {
						if(mysqli_num_rows($result3) > 0) {
							$row2 = mysqli_fetch_array($result3);
							$myname = $row2["name"];
							$mypic = $row2["image"];
						} else {
							
						}
					}

					$try =  "SELECT * FROM $metoadmin ORDER BY message_time DESC";
					$find3 = mysqli_query($link, $try);
					if(mysqli_num_rows($find3) > 0) {
						echo '<div style="margin-top: 70px;"></div>';
						while ($rowms = mysqli_fetch_array($find3)) {
							$sentms = $rowms["message"];
							$sentby = $rowms["sentby"];
							$message_time = $rowms["message_time"];
							?>
							<div id="scroll" class="col-md-12" style="overflow-x: hidden; width: 550px; z-index: -1; margin-bottom: 65px;"> 
								<?php
									if($sentby == "admin"){
										echo '
											<div class="container" style="width: 350px;">
												<img src="../img/admin.jpeg" alt="Avatar">
												<p style="color:#000;"><span>'.$sentms.'</span></p>
												<span class="time-right">'.$message_time.'</span>
											</div>
										';
									}
									if($sentby == $email){
										echo '
											<div class="container darker" style="width: 350px; float: right;">
												<img src="data:image/jpeg;base64,'.base64_encode($mypic).'" alt="Avatar" class="right">
												<p style="float: right; color:#000;">'.$sentms.'</p>
												<span class="time-left" style="padding-top: ;">'.$message_time.'</span>
											</div>
										';
									} 
								 ?>
							</div>
							<?php
								}
							}
							 ?>
				<div class="input-group" style="padding-top: 5px; position: fixed; bottom: 0; overflow: hidden; width: 100%; z-index: 9; background-color: #ffffff;">
					<form method="post">
					<input style="margin-left: 20px; width: 420px; autocomplete="off"; font-family: Arial, sans-serif; " type="text" name="write_text">
					<button style="padding: 12 20px; font-size: 20px; margin-left: 10px; font-family: Arial, sans-serif; font-weight: 200;" type="submit" name="send"><i class="fa fa-location-arrow" aria-hidden="true"></i>Send</button>
					</form>
				</div>
		</div>
	</div>
</body>
</html>