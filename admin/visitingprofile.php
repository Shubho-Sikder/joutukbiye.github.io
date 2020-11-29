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
		<a href="home.php" style="border-top: 1px solid #818181">Home</a>
				<a href="inbox.php">Inbox</a>
				<a href="reportedprofile.php">Reported Profile</a>
				<a href="addadmin.php">Add Admin</a>
		<a href="resetpass.php">Reset Password</a>
				<a href="../login/logout.php">Logout</a>
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
