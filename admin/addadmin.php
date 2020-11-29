<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}

require_once '../config.php';
$email = $_SESSION["email"];

if(isset($_POST["sbtn"])) {

	if (!empty(trim($_POST["mail"]))) {
		$sql = "SELECT id FROM users WHERE email = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $param_email);
			$param_email = trim($_POST["mail"]);
			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) == 1) {
					echo '<script>alert("This email is already taken.")</script>';
				} else {
					$asql = "SELECT id FROM admin_panal WHERE email = ?";
					if ($astmt = mysqli_prepare($link, $asql)) {
						mysqli_stmt_bind_param($astmt, "s", $param_email);
						$param_email = trim($_POST["mail"]);
						if(mysqli_stmt_execute($astmt)){
							mysqli_stmt_store_result($astmt);
							if(mysqli_stmt_num_rows($astmt) == 1) {
								echo '<script>alert("This email is already taken.")</script>';
							} else {
								$namail = trim($_POST["mail"]);
								$napass = trim($_POST["pass"]);
								if(!empty($napass)) {
									mysqli_query($link, "INSERT INTO admin_panal (email, password) VALUES ('$namail', '$napass')");
									echo '<script>alert("New admin added.")</script>';
								}
							}
						}
					}
				}
			} else {
				echo '<script>alert("Oops! Something went wrong. Please try again later.")</script>';
			}
			mysqli_stmt_close($stmt);
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
		<form method="post" style="padding-top: 70px; text-align: center;">
			<li style="float: left; list-style-type: none;  margin-left: 40px; font-weight: 700;">Email</li>
			<input type="text" name="mail" required="" placeholder="Email">
			<li style="float: left; list-style-type: none;  margin-left: 40px; font-weight: 700;">Password</li>
			<input type="password" name="pass" required="" placeholder="Password">
			<button class="btn" type="submit" name="sbtn" id="up" style="padding: 10px;width: 200px; text-align: center; margin-top: 10px;">Add Admin</button>
		</form>
	</div>
</body>
</html>