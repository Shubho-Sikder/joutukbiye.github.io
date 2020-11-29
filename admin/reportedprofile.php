<?php
session_start();
error_reporting(0);
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}

$gtmail = "";
require_once '../config.php';
$email = $_SESSION["email"];
$gtmail = $_GET['id'];

if(!$gtmail == "") {
	mysqli_query($link, "DELETE FROM userdetails WHERE email = '$gtmail'");
	mysqli_query($link, "DELETE FROM users WHERE email = '$gtmail'");
	mysqli_query($link, "DELETE FROM report_profile WHERE email = '$gtmail'");
	mysqli_query($link, "DELETE FROM admin_inbox WHERE email = '$gtmail'");
	$imgtable = "`".$gtmail."_image`";
	if(mysqli_query($link, "DESCRIBE $imgtable")) {
		mysqli_query($link, "DROP TABLE $imgtable");
	}
	$adtable = "`".$gtmail."vsadmin`";
	if(mysqli_query($link, "DESCRIBE $adtable")) {
		mysqli_query($link, "DROP TABLE $adtable");
	}
	$visitior = "`".$gtmail."_visitor`";
	if(mysqli_query($link, "DESCRIBE $visitior")) {
		mysqli_query($link, "DROP TABLE $visitior");
	}
	$liked = "`".$gtmail."_liked`";
	if(mysqli_query($link, "DESCRIBE $liked")) {
		mysqli_query($link, "DROP TABLE $liked");
	}
	$find4 = "SELECT * FROM userdetails";
	if($result5 = mysqli_query($link, $find4)){
		if(mysqli_num_rows($result5) > 0){
			while($row5 = mysqli_fetch_array($result)) {
				$otheremail = $row["email"];
				$chatable1 = "`".$otheremail."vs".$gtmail."`";
				$chatable2 = "`".$gtmail."vs".$otheremail."`";
				
				if(mysqli_query($link, "DESCRIBE $chatable1")) {
					mysqli_query($link, "DROP TABLE $chatable1");
				}
				if(mysqli_query($link, "DESCRIBE $chatable2")) {
					mysqli_query($link, "DROP TABLE $chatable2");
				}

			}
		} else {
			
		}	
	}
	echo '<script>alert("Profile has been deleted.")</script>';
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
		<?php
			$rin = mysqli_query($link, "SELECT * FROM report_profile ORDER BY id DESC");
			if(!mysqli_num_rows($rin) > 0) {
				echo '
					<h2 style="text-align: center; padding-top: 50px;">No reported account Yet</h2>
				';
			} else {
				while ($rowin = mysqli_fetch_array($rin)) {
					$imail = $rowin["email"];
					$cr = $rowin["report_cause"];
					$find = "SELECT * FROM userdetails WHERE email = '$imail'";
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
						}	
					}
					$del = "del";
					echo '
					<div id="sh" class="table-responsive">
							<table >
								<tr>
									 <td style="padding-left: 50px; padding-top: 10px;" >
									 <img src="data:image/jpeg;base64,'.base64_encode($row["image"]).'" alt="Profile picture" style="width: 120px; height: 120px;"> </td>
								<td style="padding-left: 30px;"><span><h3><a href="visitingprofile.php?id='.$row["email"].'">'.$row["name"].'</a></h3></span><span style="padding-top: 10px; font-weight:600; color:#A699A6;">Cause of report: '.$cr.'</span><br><span><button><span><a href="inbox.php?idmail='.$row["email"].'">Remove from report list with a warning</a></span></button></span><br><span><button style="margin-top: 5px;"><span><a onclick="sdl()">Delete this account</a></span></button></span></td>
								<td id="dl" class="hide" style="padding-left:30px; text-align: center; margin-top: 40px;"><span>Are you sure to delete this account? <br></span><button style="margin-top: 5px;"><span><a href="reportedprofile.php?id='.$row["email"].'">Yes</a></span></button><button style="margin-top: 5px; margin-left: 20px;"><span><a onclick="cncl()">No</a></span></button></td>
								</tr>
							</table>
						</div>
					';
				}
			}

		 ?>
	</div>
</body>
</html>
<script type="text/javascript">
	function sdl(){
		document.getElementById('dl').className="sho";
	}
</script>
<script type="text/javascript">
	function cncl(){
		document.getElementById('dl').className="hide";
	}
</script>