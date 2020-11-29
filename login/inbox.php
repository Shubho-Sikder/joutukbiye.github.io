<?php
session_start();
error_reporting(0);
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}
require_once '../config.php';
$email = $_SESSION["email"];
$myinbox = "`".$email."_inbox`";
$mail = $_GET['id'];

$metouser = "`".$email."vs".$mail."`";
$usertome = "`".$mail."vs".$email."`";
$touserinbox = "`".$mail."_inbox`";

if(mysqli_query($link, "DESCRIBE $metouser")) {
	$findcp = "SELECT * FROM $metouser WHERE seen = 'NO' AND recieved IS NOT NULL";
	if ($resultcp= mysqli_query($link, $findcp)) {
		mysqli_query($link, "UPDATE $metouser SET seen = 'YES' WHERE seen = 'NO'");
	}
}

if(isset($_POST["send"])) {
	$message = $_POST["write_text"];
	mysqli_query($link,"INSERT INTO $metouser (sent, seen) VALUES ('$message','NO')");
	if(!mysqli_query($link, "DESCRIBE $usertome")) {
		mysqli_query($link, "CREATE TABLE $usertome (id INT(255) PRIMARY KEY AUTO_INCREMENT, sent VARCHAR(255), recieved VARCHAR(255), seen VARCHAR(255), message_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	}
	if(mysqli_query($link,"INSERT INTO $usertome (recieved, seen) VALUES ('$message','NO')")){

	} else {
		echo '<script>alert("Do not use special characters.")</script>';
	}
	if(!mysqli_query($link, "DESCRIBE $touserinbox")) {
		mysqli_query($link, "CREATE TABLE $touserinbox (id INT(255) PRIMARY KEY AUTO_INCREMENT, email VARCHAR(255), updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)");
	}
	$fii = "SELECT * FROM $touserinbox WHERE email = '$email'";
	if(mysqli_query($link, $fii)) {
			mysqli_query($link,"UPDATE $touserinbox SET updated_at=NOW() WHERE email = '$email'");
	} 
	if(!mysqli_num_rows(mysqli_query($link, $fii)) > 0) {
		mysqli_query($link,"INSERT INTO $touserinbox (email) VALUES ('$email')");
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
	<div class="col-md-12">
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
	<div style="margin-left: 180px; background-color: #ABB1BA; width: 200px; height: 100%; position: fixed; overflow-x: hidden; top: 0; left: 0;">
			<h2 style="padding-bottom: 7px; padding-left: 7px; padding-top: 7px; position: fixed; z-index: 7; background-color: #ABB1BA; width: 200px;">Inbox</h2>
			<div style="position: absolute; display: block; z-index: 5; margin-top: 50px;">
				<?php
				$rin = mysqli_query($link, "SELECT * FROM $myinbox ORDER BY updated_at DESC");
				if(!mysqli_num_rows($rin) > 0) {
					echo '
						<h2 style="text-align: center; padding-top: 50px;">Empty Inbox Yet</h2>
					';
				} else {
					while ($rowin = mysqli_fetch_array($rin)) {
						$imail = $rowin["email"];
						$find = "SELECT * FROM userdetails WHERE email = '$imail'";
						if($result2 = mysqli_query($link, $find)){
							if(mysqli_num_rows($result2) > 0){
								$row = mysqli_fetch_array($result2);
								$name = $row['name'];
								$pim = $row['image'];
								$toemail = $row["email"];
							} else {
								
							}	
						}
						$checktable = "`".$email."vs".$toemail."`";
						if(mysqli_query($link, "DESCRIBE $checktable")) {
							$findc = "SELECT * FROM $checktable WHERE seen = 'NO' AND recieved IS NOT NULL";
							if ($resultc= mysqli_query($link, $findc)) {
								$numrow = mysqli_num_rows($resultc);
								if($numrow > 0) {
									echo '
									<p><span><img src="data:image/jpeg;base64,'.base64_encode($pim).'" alt="Avatar" class="avatar" style="margin-top:0px; width: 40px; height: 40px; margin-left: 15px; padding-bottom: 5px;"></span><span style="font-weight: 700; padding-left: 10px; color: #000; padding-top: 1em;"><a id="tlink" href="inbox.php?id='.$toemail.'" style="cursor: pointer;">'.$name.'<span style="color: #D20000;">('.$numrow.')</span></a></span></p>
									';
								} else {
									echo '
									<p><span><img src="data:image/jpeg;base64,'.base64_encode($pim).'" alt="Avatar" class="avatar" style="margin-top:0px; width: 40px; height: 40px; margin-left: 15px; padding-bottom: 5px;"></span><span style="font-weight: 700; padding-left: 10px; color: #000; padding-top: 1em;"><a id="tlink" href="inbox.php?id='.$toemail.'" style="cursor: pointer;">'.$name.'</a></span></p>
									';
								}
							}
						}
							
					}
				}
				?>
			</div>
	</div>
	
		<div  id="inboxform" style="margin-left: 380px;">
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
				$find2 = "SELECT * FROM userdetails WHERE email = '$mail'";
				if($result4 = mysqli_query($link, $find2)) {
					if(mysqli_num_rows($result4) > 0) {
						$row3 = mysqli_fetch_array($result4);
						$username = $row3["name"];
						$userpic = $row3["image"];
					} else {
						
					}
				}
					
					if(mysqli_query($link, "DESCRIBE $metouser")) {
						echo '
							<div class="col-md-12" style="">
								<div class="mh" style="top: 0; width: 100%; z-index: 9; background-color: #ffffff; position: fixed; padding-top: 20px; padding-bottom: 10px;">
									<h2 style="margin-left: 9%;">Write to <span><a href="visitingprofile.php?id='.$mail.'">'.$username.'</a></span></h2>
								</div>
							</div>
					';
					}
					$try =  "SELECT * FROM $metouser ORDER BY message_time DESC";
				$find3 = mysqli_query($link, $try);
				if(mysqli_num_rows($find3) > 0) {
					echo '<div style="margin-top: 70px;"></div>';
					while ($rowms = mysqli_fetch_array($find3)) {
						$sentms = $rowms["sent"];
						$recievedms = $rowms["recieved"];
						$message_time = $rowms["message_time"];
						?>
						<div id="scroll" class="col-md-12" style="overflow-x: hidden; width: 550px; z-index: -1; margin-bottom: 65px;"> 
							<?php
								if(!$recievedms == ""){
									echo '
										<div class="container" style="width: 350px;">
											<img src="data:image/jpeg;base64,'.base64_encode($userpic).'" alt="Avatar">
											<p style="color:#000;"><span>'.$recievedms.'</span></p>
											<span class="time-right">'.$message_time.'</span>
										</div>
									';
								}
								if(!$sentms == ""){
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
					<?php }
					echo '
						<div class="input-group" style="padding-top: 5px; position: fixed; bottom: 0; overflow: hidden; width: 100%; z-index: 9; background-color: #ffffff;">
							<form method="post">
							<input style="margin-left: 20px; width: 420px; autocomplete="off"; font-family: Arial, sans-serif; " type="text" name="write_text">
							<button style="padding: 12 20px; font-size: 20px; margin-left: 10px; font-family: Arial, sans-serif; font-weight: 200;" type="submit" name="send"><i class="fa fa-location-arrow" aria-hidden="true"></i>Send</button>
							</form>
						</div>
					';
				}
				else {
					if(mysqli_query($link, "DESCRIBE $metouser")){
					echo '
						<div class="input-group" style="padding-top: 5px; position: fixed; bottom: 0; overflow: hidden; width: 100%; z-index: 9; background-color: #ffffff;">
							<form method="post">
							<input style="margin-left: 20px; width: 420px; autocomplete="off"; font-family: Arial, sans-serif; " type="text" name="write_text">
							<button style="padding: 12 20px; font-size: 20px; margin-left: 10px; font-family: Arial, sans-serif; font-weight: 200;" type="submit" name="send"><i class="fa fa-location-arrow" aria-hidden="true"></i>Send</button>
							</form>
						</div>
					';
					} else    {
						echo '
							<h1 style="margin-left:20px; margin-top:20px;">Start conversation</h1>
						';
					}
				}

			?>
		</div>
	</div>
</body>
</html>


