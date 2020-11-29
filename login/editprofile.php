<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}

require_once '../config.php';
$name = $address = $age = $pro = $dowry = "";
$pim = "";
$oldp = $newp = $cp = $fopsw = "";

$email = $_SESSION["email"];


$find = "SELECT * FROM userdetails WHERE email = '$email'";
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

if(isset($_POST["upim"])){
	if(!empty($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])){
			$file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
			$query = "UPDATE userdetails SET image = '$file' WHERE email = '$email'";
			if(mysqli_query($link, $query)){
				echo '<script>alert("Profile picture updated successfully.")</script>';
				header("location: editprofile.php");
			} else {
				echo '<script>alert("Image file is larger than 2MB.")</script>';
			}
	} else {
		echo '<script>alert("Image file is larger than 2MB.")</script>';
	}
}

if(isset($_POST["sname"])) {
	if(!empty($_POST["ename"])) {
		$nname = $_POST["ename"];
		$query = "UPDATE userdetails SET name = '$nname' WHERE email = '$email'";
			if(mysqli_query($link, $query)){
				echo '<script>alert("Name updated successfully.")</script>';
				header("location: editprofile.php");
			} else {
				echo '<script>alert("Something went wrong. Try again later.")</script>';
			}
	}
}

if(isset($_POST["sage"])) {
	if(!empty($_POST["eage"])) {
		$nage = $_POST["eage"];
		$query = "UPDATE userdetails SET age = '$nage' WHERE email = '$email'";
			if(mysqli_query($link, $query)){
				echo '<script>alert("Age updated successfully.")</script>';
				header("location: editprofile.php");
			} else {
				echo '<script>alert("Something went wrong. Try again later.")</script>';
			}
	}
}

if(isset($_POST["sadd"])) {
	if(!empty($_POST["eadd"])) {
		$nadd = $_POST["eadd"];
		$query = "UPDATE userdetails SET address = '$nadd' WHERE email = '$email'";
			if(mysqli_query($link, $query)){
				echo '<script>alert("Address updated successfully.")</script>';
				header("location: editprofile.php");
			} else {
				echo '<script>alert("Something went wrong. Try again later.")</script>';
			}
	}
}

if(isset($_POST["sdet"])) {
	if(!empty($_POST["edet"])) {
		$ndet = $_POST["edet"];
		$query = "UPDATE userdetails SET pro = '$ndet' WHERE email = '$email'";
			if(mysqli_query($link, $query)){
				echo '<script>alert("Details updated successfully.")</script>';
				header("location: editprofile.php");
			} else {
				echo '<script>alert("Something went wrong. Don not use special character. Try again later.")</script>';
			}
	}
}

if(isset($_POST["sdol"])) {
	if(!empty($_POST["edol"])) {
		$ndol = $_POST["edol"];
		$query = "UPDATE userdetails SET dowrylist = '$ndol' WHERE email = '$email'";
			if(mysqli_query($link, $query)){
				echo '<script>alert("Dowry list updated successfully.")</script>';
				header("location: editprofile.php");
			} else {
				echo '<script>alert("Something went wrong. Try again later.")</script>';
			}
	}
}

if(isset($_POST["mingle"])) {
	mysqli_query($link, "UPDATE userdetails SET status = 'Mingle' WHERE email = '$email'");
	echo '<script>alert("Status updated successfully.")</script>';
}

if(isset($_POST["single"])) {
	mysqli_query($link, "UPDATE userdetails SET status = 'Single' WHERE email = '$email'");
	echo '<script>alert("Status updated successfully.")</script>';
}

if(isset($_POST["rbtn"])){
	$oldp = $_POST["oldp"];
	$newp = $_POST["newp"];
	$cp = $_POST["cp"];
	if(strlen(trim($newp)) < 6) {
		echo '<script>alert("Password must have atleast 6 characters.")</script>';
	} else {
		if($newp == $cp) {
			if(!empty($oldp) && !empty($newp) && !empty($cp)) {
				$findoldp = "SELECT * FROM users WHERE email = '$email'";
				if($resultpsw = mysqli_query($link, $findoldp)){
					if(mysqli_num_rows($resultpsw) > 0){
						$psw = mysqli_fetch_array($resultpsw);
						$fopsw = $psw['password'];
					} else {
						echo '<script>alert("Database not found.")</script>';
					}
				}
				if($oldp == $fopsw) {
					mysqli_query($link, "UPDATE users SET password = '$newp' WHERE email = '$email'");
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

if(isset($_POST["dbtn"])) {
	mysqli_query($link, "DELETE FROM userdetails WHERE email = '$email'");
	mysqli_query($link, "DELETE FROM users WHERE email = '$email'");
	mysqli_query($link, "DELETE FROM report_profile WHERE email = '$email'");
	mysqli_query($link, "DELETE FROM admin_inbox WHERE email = '$email'");
	$imgtable = "`".$email."_image`";
	if(mysqli_query($link, "DESCRIBE $imgtable")) {
		mysqli_query($link, "DROP TABLE $imgtable");
	}
	$adtable = "`".$email."vsadmin`";
	if(mysqli_query($link, "DESCRIBE $adtable")) {
		mysqli_query($link, "DROP TABLE $adtable");
	}
	$visitior = "`".$email."_visitor`";
	if(mysqli_query($link, "DESCRIBE $visitior")) {
		mysqli_query($link, "DROP TABLE $visitior");
	}
	$liked = "`".$email."_liked`";
	if(mysqli_query($link, "DESCRIBE $liked")) {
		mysqli_query($link, "DROP TABLE $liked");
	}
	$find4 = "SELECT * FROM userdetails";
	if($result5 = mysqli_query($link, $find4)){
		if(mysqli_num_rows($result5) > 0){
			while($row5 = mysqli_fetch_array($result)) {
				$otheremail = $row["email"];
				$chatable1 = "`".$otheremail."vs".$email."`";
				$chatable2 = "`".$email."vs".$otheremail."`";
				
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
	session_destroy();
	echo '<script>alert("Account deleted successfully.")</script>';
	header("location: logout.php");
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
		
		<div class="col-md-12">
			<div class="col-md-2">
				<?php
				echo '<img src="data:image/jpeg;base64,'.base64_encode($pim).'" alt="Profile picture" style="width: 100%; height: 150px;">';
				?>
				
			</div>
			<div class="col-md-10">
				<form method="post">
					<h4 style="padding-top: 50px;">Did you find your partner?</h4>
					<p style="padding-top: 10px; padding-left: 10px;"><span style="padding-right: 20px;"><button name="mingle" type="submit">Yes</button></span>        <span><button name="single" type="submit">No</button></span></p>
				</form>
			</div>
		</div>
		<div class="col-md-12">
			<form method="post" enctype="multipart/form-data">
					<li style=" list-style-type: none; font-weight: 600; display: inline-block;">Update profile picture<span style="padding-left: 50px;"><button class="hide" id="up" name="upim" type="submit">Update</button></span></li>
						<input type="file" name="image" onclick="changepp()">
				</form>
		</div>
		<div class="col-md-12">
			<h2 style="padding-top: 15px; margin-left: 4px;">Name: <span><?php echo "$name";?></span></h2>
			<button style="margin-left: 6px;" onclick="ena()">Edit Name</button>
			<form method="post" class="hide" id="sna" style="width: 60%; margin-top: 10px;">
				<input type="text" name="ename">
				<button name="sname" type="submit">Save</button>
			</form>
			<p style="font-weight: 700; margin-left:4px; margin-top: 7px;">Age: <span><?php echo "$age";?></span>;</p>
			<button style="margin-left: 6px;" onclick="eag()">Edit Age</button>
			<form method="post" class="hide" id="sag" style="width: 60%; margin-top: 10px;">
				<input type="text" name="eage">
				<button name="sage" type="submit">Save</button>
			</form>
			<p style="font-weight: 700; margin-left: 4px; margin-top: 7px;">Address: <span><?php echo "$address";?></span>;</p>
			<button style="margin-left: 6px;" onclick="ead()">Edit Address</button>
			<form method="post" class="hide" id="sad" style="width: 60%; margin-top: 10px;">
				<input type="text" name="eadd">
				<button name="sadd" type="submit">Save</button>
			</form>
			<p style="font-weight: 500; margin-left: 4px; margin-top: 7px;"><span style="font-weight: 700;">Details: </span><span><?php echo "$pro";?>.</span></p>
			<button style="margin-left: 6px;" onclick="edt()">Edit Details</button>
			<form method="post" class="hide" id="sdt" style="width: 60%; margin-top: 10px;">
				<input type="text" name="edet">
				<button name="sdet" type="submit">Save</button>
			</form>
		</div>
		<div class="col-md-12" style="padding-bottom: 30px;">
			<h2 style="margin-top: 8px; margin-left: 4px;">List of Dowry</h2>
			<p style="font-weight: 700; margin-left: 4px;"><?php echo "$dowry";?></p>
			<button style="margin-left: 6px;" onclick="edl()">Edit Dowry List</button>
			<form method="post" class="hide" id="sdl" style="width: 60%; margin-top: 10px;">
				<input type="text" name="edol">
				<button name="sdol" type="submit">Save</button>
			</form>
		</div>
		<p style="font-weight: 700; float: left; margin-left: 20px; padding-bottom: 10px;">Click to <span><button onclick="res()">Reset</button></span> your password.</p>

		<p style="font-weight: 700; float: right; margin-right: 20px;">Click to <span><button onclick="del()">delete</button></span> your account.</p>
	</div>
	<div id="dlt" class="delt">
		<form class="log-content animate" method="post">
			<div class="imgcontainer">
				<span style="padding-top: 17px;" onclick="document.getElementById('dlt').style.display='none'" class="close" title="Close form">&times;</span>
				<h3 style="padding-top: 40px; text-align: center; font-family: 'URW Chancery L', cursive;">Are you sure to delete this account?</h3>
				<button class="btn" type="submit" name="dbtn" style="padding: 10px;width: 40%; margin-top: 10px;">Yes</button>
				<button class="btn" onclick="document.getElementById('dlt').style.display='none'" style="padding: 10px;width: 40%; margin-top: 10px; margin-left: 5%;">Cancel</button>
			</div>
		</form>
	</div>

	<div id="rs" class="reset">
		<form class="log-content animate" method="post">
			<div class="imgcontainer">
				<h1 style=" text-align: center; font-family: 'URW Chancery L', cursive;">Reset Password</h1>
				<span onclick="document.getElementById('rs').style.display='none'" class="close" title="Close form">&times;</span>
				<li style="float: left; text-align: left; list-style-type: none; margin-left: 30px; font-weight: 600;">Old Password</li>
				<input type="text" name="oldp" placeholder="Old Password" required="">
				<li style="float: left; text-align: left; list-style-type: none;  margin-left: 30px; font-weight: 600;">New Password</li>
				<input type="text" name="newp" placeholder="New Password" required="">
				<li style="float: left; text-align: left; list-style-type: none;  margin-left: 30px; font-weight: 600;">Confirm Password</li>
				<input type="text" name="cp" placeholder="Confirm Password" required="">
				<button class="btn" name="rbtn" type="submit">Reset Password</button>
			</div>
		</form>
	</div>
	
</body>
</html>

<script type="text/javascript">
	function changepp(){
		document.getElementById('up').className="sho";
	}
</script>
<script type="text/javascript">
	function ena(){
		document.getElementById('sna').className="sho";
	}
</script>
<script type="text/javascript">
	function eag(){
		document.getElementById('sag').className="sho";
	}
</script>
<script type="text/javascript">
	function ead(){
		document.getElementById('sad').className="sho";
	}
</script>
<script type="text/javascript">
	function edt(){
		document.getElementById('sdt').className="sho";
	}
</script>
<script type="text/javascript">
	function edl(){
		document.getElementById('sdl').className="sho";
	}
</script>
<script type="text/javascript">
	function res(){
		document.getElementById("rs").style.display="block";
	}
</script>
<script type="text/javascript">
	function del(){
		document.getElementById("dlt").style.display="block";
	}
</script>