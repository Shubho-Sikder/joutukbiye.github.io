<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}

require_once '../config.php';
$email = $_SESSION["email"];

if(isset($_POST["rbtn"])){
	$oldp = $_POST["oldp"];
	$newp = $_POST["newp"];
	$cp = $_POST["cp"];
	if(!strlen(trim($newp)) > 0) {
		echo '<script>alert("Password must have characters.")</script>';
	} else {
		if($newp == $cp) {
			if(!empty($oldp) && !empty($newp) && !empty($cp)) {
				$findoldp = "SELECT * FROM admin_panal WHERE email = '$email'";
				if($resultpsw = mysqli_query($link, $findoldp)){
					if(mysqli_num_rows($resultpsw) > 0){
						$psw = mysqli_fetch_array($resultpsw);
						$fopsw = $psw['password'];
					} else {
						echo '<script>alert("Database not found.")</script>';
					}
				}
				if($oldp == $fopsw) {
					mysqli_query($link, "UPDATE admin_panal SET password = '$newp' WHERE email = '$email'");
					echo '<script>alert("Password updated successfully.")</script>';
				} else {
					echo '<script>alert("Old Password does not match. Try again.")</script>';
				}
			} else {
				echo '<script>alert("Password form can not be empty.")</script>';
			}
		} else {
			echo '<script>alert("New Password does not match. Try again.")</script>';
		}
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
		<img src="../img/admin.jpeg" alt="Avatar" class="avatar" style="width: 130px; height: 130px; margin-left: 25px; padding-bottom: 5px;">
		<a href="home.php" style="border-top: 1px solid #818181">Home</a>
		<a href="inbox.php">Inbox</a>
		<a href="reportedprofile.php">Reported Profile</a>
		<a href="addadmin.php">Add Admin</a>
		<a href="resetpass.php">Reset Password</a>
		<a href="../login/logout.php">Logout</a>
	</div>
	<div class="pro">
		<form method="post" style="width: 600px; padding-top: 70px; text-align: center;">
			<li style="float: left; list-style-type: none;  margin-left: 35px; font-weight: 700;">Old Password</li>
			<input type="text" name="oldp" placeholder="Old Password" required="">
			<li style="float: left; list-style-type: none;  margin-left: 35px; font-weight: 700;">New Password</li>
			<input type="text" name="newp" placeholder="New Password" required="">
			<li style="float: left; list-style-type: none;  margin-left: 35px; font-weight: 700;">Confirm Password</li>
			<input type="text" name="cp" placeholder="Confirm Password" required="">
			<button class="btn" type="submit" name="rbtn" id="up" style="padding: 10px;width: 200px; text-align: center; margin-top: 10px;">Reset Password</button>
		</form>
	</div>
</body>
</html>