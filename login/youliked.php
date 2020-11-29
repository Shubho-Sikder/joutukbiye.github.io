<?php
session_start();
error_reporting(~E_WARNING);
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}
require_once '../config.php';
$email = $_SESSION["email"];
$visitortable = "`".$email."_visitor`";
$myliketable = "`".$email."_liked`";
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
		<?php 
		$query = "SELECT * FROM $myliketable ORDER BY liked_at DESC";
			$result = mysqli_query($link, $query);
			if(mysqli_num_rows($result) > 0) {
				while($frow = mysqli_fetch_array($result)) {
					$vmail = $frow["email"];
					$find = "SELECT * FROM userdetails WHERE email = '$vmail'";
					if($result2 = mysqli_query($link, $find)){
						if(mysqli_num_rows($result2) > 0){
							$row = mysqli_fetch_array($result2);
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
					echo '
					<div class="table-responsive" style="padding-top:30px;">
							<table >
								<tr>
									 <td style="padding-left: 50px; padding-top: 10px;" >
									 <img src="data:image/jpeg;base64,'.base64_encode($pim).'" alt="Profile picture" style="width: 120px; height: 120px;"> </td>
								<td style="padding-left: 30px;"><span><h2><a href="visitingprofile.php?id='.$row["email"].'">'.$row["name"].'</a></h2></span><span style="padding-top: 10px; font-weight:700;">Address: '.$row["address"].'</span></td>
								</tr>
							</table>
						</div>
					';
				}
			} else{
				echo '<h1 style="text-align: center;">You did not like any profile yet.</h1>';
			} ?>
	</div>
</body>
</html>