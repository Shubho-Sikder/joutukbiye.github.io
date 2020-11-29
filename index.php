<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
	header("location: login/profile.php");
	exit;
}

require_once 'config.php';

$lemail = $lpsw = $yname = $yemail = $yage = $yaddress = $ygender = $ylgender = $ydetails = $ydowry = $ypas = "";
$email_err = $pass_err = $repass_err = "";
$status = "";
$pro = "";

if(isset($_POST["lbtn"])) {
	$lemail = trim($_POST["lemail"]);
	$lpsw = trim($_POST["lpsw"]);
	$lsql = "SELECT id, email, password FROM users WHERE email = ?";
	if($stmt = mysqli_prepare($link, $lsql)) {
		mysqli_stmt_bind_param($stmt, "s", $param_email);
		$param_email = $lemail;
		if(mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result($stmt);
			if(mysqli_stmt_num_rows($stmt) == 1) {
				mysqli_stmt_bind_result($stmt, $id, $lemail, $hashed_password);
				if(mysqli_stmt_fetch($stmt)) {
					if($lpsw == $hashed_password) {
						session_start();
						$_SESSION["loggedin"] = true;
						$_SESSION["id"] = $id;
						$_SESSION["email"] = $lemail;
						header("location: login/profile.php");
					} else {
						echo '<script>alert("The password you entered was not valid.")</script>';
					}
				}
			} else {
				$alsql = "SELECT id, email, password FROM admin_panal WHERE email = ?";
				if($astmt = mysqli_prepare($link, $alsql)) {
					mysqli_stmt_bind_param($astmt, "s", $aparam_email);
					$aparam_email = $lemail;
					if(mysqli_stmt_execute($astmt)) {
						mysqli_stmt_store_result($astmt);
						if(mysqli_stmt_num_rows($astmt) == 1) {
							mysqli_stmt_bind_result($astmt, $id, $lemail, $hashed_password);
							if(mysqli_stmt_fetch($astmt)) {
								if($lpsw == $hashed_password) {
									session_start();
									$_SESSION["loggedin"] = true;
									$_SESSION["id"] = $id;
									$_SESSION["email"] = $lemail;
									header("location: admin/home.php");
								} else {
									echo '<script>alert("The password you entered was not valid.")</script>';
								}
							}
						} else {
							echo '<script>alert("No account found with that email.")</script>';
						}
					} else {
						echo '<script>alert("Oops! Something went wrong. Please try again later.")</script>';
					}
				}
			}
		} else{
			echo '<script>alert("Oops! Something went wrong. Please try again later.")</script>';
		}
		mysqli_stmt_close($stmt);
	} 
}


if (isset($_POST["sbtn"])) {
	if (!empty(trim($_POST["yemail"]))) {
		$sql = "SELECT id FROM users WHERE email = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $param_email);
			$param_email = trim($_POST["yemail"]);
			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) == 1) {
					$email_err = "This email is already taken.";
					echo '<script>alert("This email is already taken.")</script>';
				} else {
					$asql = "SELECT id FROM admin_panal WHERE email = ?";
					if ($astmt = mysqli_prepare($link, $asql)) {
						mysqli_stmt_bind_param($astmt, "s", $param_email);
						$param_email = trim($_POST["yemail"]);
						if(mysqli_stmt_execute($astmt)){
							mysqli_stmt_store_result($astmt);
							if(mysqli_stmt_num_rows($astmt) == 1) {
								$email_err = "This email is already taken.";
								echo '<script>alert("This email is already taken.")</script>';
							} else {
								$yemail = trim($_POST["yemail"]);
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

	if(strlen(trim($_POST["pass"])) < 6) {
		$pass_err = "Password must have atleast 6 characters.";
	} else {
		$ypas = trim($_POST["pass"]);
	}
	if(empty(trim($_POST["rpass"]))){
		$repass_err = "Please confirm password.";
	} else {
		$rpass = trim($_POST["rpass"]);
		if(empty($pass_err) && ($ypas != $rpass)){
			$repass_err = "Password did not match.";
		}
	}

	if(empty($email_err) && empty($pass_err) && empty($repass_err)){
		$yname = $_POST["yname"];
		$yage = trim($_POST["yage"]);
		$yaddress = $_POST["yaddress"];
		$ygender = trim($_POST["ygender"]);
		$ylgender = trim($_POST["ylgender"]);
		$ydowry = $_POST["ydowry"];
		$pro = $_POST["pro"];
		
		if(!empty($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])){
			$file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
			$query = "INSERT INTO userdetails (name, email, address, age, gender, lookingfor, dowrylist, image, pro, status) VALUES ('$yname','$yemail','$yaddress','$yage','$ygender','$ylgender','$ydowry','$file','$pro', 'Single') ";
			if(mysqli_query($link, $query)){
				$user = "INSERT INTO users (email, password) VALUES ('$yemail', '$ypas')";
				if(mysqli_query($link, $user)) {
					echo '<script>alert("Registration successfully done! You can login now.")</script>';
				} else {
					echo '<script>alert("Unsuccessful attempt! Do not use any special characters.")</script>';
				}
			} else {
				echo '<script>alert("Unsuccessful attempt! Do not use any special characters.")</script>';
			}
		} else {
			echo '<script>alert("Image file is larger than 2MB.")</script>';
		}
	}
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Traditional Marriage Management Site</title>

	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/swipebox.css">
	<link rel="stylesheet" href="css/jquery-ui.css">
</head>
<body>
	<div class="first_section">
		<div class="navbar">
			<div class="navbar-header navbar-left">
				<a class="navbar-toggle" href="javascript:void(0);" onclick="myFunction()">
					<i class="fa fa-bars"></i>
				</a>
				<h1><a class="navbar-brand" href="index.html"><i class="fa fa-male" aria-hidden="true"></i><i class="fa fa-female" aria-hidden="true"></i>joutukbiye.com</a></h1>
			</div>
			<div class="collapse navbar-collapse navbar-header navbar-right" id="toggle">
				<nav class="link-effect-2" id="link-effect-2">
					<ul class="nav navbar-nav">
						<li><a onclick="document.getElementById('login').style.display='block'" class="effect-3 scroll">Login</a></li>
						<li><a onclick="document.getElementById('signup').style.display='block'" class="effect-3 scroll">Signup</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<div class="second_section">
		<div class="col-md-6 bdy_left">
			<h1>Welcome to our site</h1>
		</div>
		<div class="col-md-6 bdy_right">
			<h2><i class="fa fa-arrows" aria-hidden="true"></i>Our Services</h2>
			<h4><i class="fa fa-check-square-o" aria-hidden="true"></i>Trade of traditional means business. We try to serve them, who wants to get their life partner in tradition, to find their right partner.</h4>
			<h4><i class="fa fa-check-square-o" aria-hidden="true"></i>Members can visit and send message to their choosen person's profile.</h4>
			<h4><i class="fa fa-check-square-o" aria-hidden="true"></i>If members find any unauthonic behavior from any profile, they can report that profile, so it's a safe site.</h4>
			<h4><i class="fa fa-check-square-o" aria-hidden="true"></i>If any problem is faced by members, they can talk to the site authority.</h4>
			<h4><i class="fa fa-check-square-o" aria-hidden="true"></i>So, whats are you waiting for? Just sign up and find your right partner.</h4>
		</div>
	<div class="third_section">
		<p>@All rights reserved to engr.shubho02@gmail.com</p>
	</div>
	</div>
	<div id="login" class="log">
		<form class="log-content animate" method="post" enctype="multipart/form-data">
			<div class="imgcontainer">
				<span onclick="document.getElementById('login').style.display='none'" class="close" title="Close Login">&times;</span>
				<img src="img/demo.jpeg" alt="Avatar" class="avatar">
				<h1 style="text-align: center; font-family: 'URW Chancery L', cursive;">Let's Login</h1>
			
				<input type="text" name="lemail"  placeholder="Email" required="">
				<input type="password" name="lpsw" placeholder="Passsword" required="">
				<button class="btn" name="lbtn" type="submit">Login</button>
				
				<a onclick="document.getElementById('signup').style.display='block'" style="text-decoration: none; float: left; margin-left: 30px; margin-top: 3px;  padding-bottom: 7px; cursor: pointer;"><i class="fa fa-check-square-o" aria-hidden="true"></i>Don't have an account? Sign up now.</a>
			</div>
		</form>
	</div>
	<div id="signup" class="sign">
		<form class="sign-content animate" method="post" enctype="multipart/form-data">
			<div class="imgcontainer">
				<span onclick="document.getElementById('signup').style.display='none'" class="close" title="Close Signup">&times;</span>
				<h1 style="text-align: center; font-family: 'URW Chancery L', cursive;">Enter the following details</h1>
				<div class="col-md-12">
				<div class="col-md-6">
					<li style="float: left; text-align: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">Your name</li>
					<input type="text" name="yname" placeholder="Ex: Shubho Sikder" required="">
				</div>
				<div class="col-md-6">
					<div class="form-group <?php echo(!empty($email_err)) ? 'has-error' : ''; ?>">
						<li style="float: left; text-align: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">Your email</li>
						<input type="text" name="yemail" placeholder="Ex: example@gmail.com" required="">
						<span class="help-block"><?php echo $email_err; ?></span>
					</div>
				</div>
				<div class="col-md-6">
					<li style="float: left; text-align: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">Your address</li>
					<input type="text" name="yaddress" placeholder="Ex: Village,Thana,Distric" required="">
				</div>
				<div class="col-md-6">
					<li style="float: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">Your age</li>
					<input type="text" name="yage" placeholder="Ex: 21" required="">
				</div>
				<div class="col-md-6">
					<li style="float: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">Your gender</li>
					<input type="text" name="ygender" placeholder="Ex: Male/Female" required="">
				</div>
				<div class="col-md-6">
					<li style="float: left; text-align: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">Gender you are looking for</li>
					<input type="text" name="ylgender" placeholder="Ex: Female/Male" required="">
				</div>
				<div class="col-md-6">
					<li style="float: left; text-align: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">Discuss yourself</li>
					<input type="text" name="pro" placeholder="Ex: Profession" required="">
				</div>
				<div class="col-md-6">
					<li style="float: left; text-align: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">The dowry you want to provide at marriage</li>
					<input type="text" name="ydowry" placeholder="Ex: Tv Bike" required="">
				</div>
				<div class="col-md-6">
					<div class="form-group <?php echo(!empty($pass_err)) ? 'has-error' : ''; ?>">
						<li style="float: left; text-align: left; list-style-type: none;  margin-left: 20px; font-weight: 700;">Password</li>
						<input type="password" name="pass" placeholder="Password" required="">
						<span class="help-block"><?php echo $pass_err; ?></span>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group <?php echo(!empty($repass_err)) ? 'has-error' : ''; ?>">
						<li style="float: left; text-align: left; list-style-type: none; margin-left: 20px; font-weight: 700;">Re-Password</li>
						<input type="password" name="rpass" placeholder="Re-Password" required="">
						<span class="help-block"><?php echo $repass_err; ?></span>
					</div>
				</div>
			</div>
				<div class="col-md-12">
					<div class="form-group <?php echo(!empty($status)) ? 'has-error' : ''; ?>">
						<li style="float: left; text-align: left; list-style-type: none; margin-left: 32px; font-weight: 700;">Choose your profile picture</li>
						<input type="file" id="im" name="image" required="">
						<span class="help-block"><?php echo $status; ?></span>
					</div>
				</div>
				<div class="col-md-12">
					<h2 style="text-align: center; font-family: 'URW Chancery L', cursive;">Terms & condition</h2>
					<p style="text-align: left; font-style: italic; color: #000; font-weight: 500; margin-left: 20px;"><i class="fa fa-check-square-o" aria-hidden="true"></i>Nudity is not allowed. You will be banned, if any user report your profile causing nudity.</p>
					<p style="text-align: left; font-style: italic; color: #000; font-weight: 500; margin-left: 20px;"><i class="fa fa-check-square-o" aria-hidden="true"></i>You will have to behave yourself politely.</p>
					<p style="text-align: left; font-style: italic; color: #000; font-weight: 500; margin-left: 20px;"><i class="fa fa-check-square-o" aria-hidden="true"></i>Don't disturb any user, if they aren't interested in you. Otherwise, you will be banned.</p>
				</div>
				<div class="col-md-12">
					<p style="font-weight: 700; text-align: left; color: #000; margin-left: 20px;"><input type="checkbox" name="check" required="">I accept all the terms and codition.</p>
				</div>
				<div class="col-md-12" style="padding-bottom: 20px;">
					<button class="btn" type="submit" name="sbtn" id="up" style="padding: 10px;width: 200px; text-align: center; margin-top: 10px;">Signup</button>
				</div>
			</div>
		</form>
	</div>
	<script type="text/javascript">
		function myFunction() {
			var x = document.getElementById("toggle");
			if (x.className == "nav") {
				x.className = " responsive";
			} else {
				x.className = "nav";
			}
		}
	</script>
	<script type="text/javascript">
		var log = document.getElementById('login');
		window.onclick = function(event) {
			if (event.target == log) {
				log.style.display = "none";
			}
		}
	</script>
	<script type="text/javascript">
		var sig = document.getElementById('signup');
		window.onclick = function(event) {
			if (event.target == sig) {
				sig.style.display = "none";
			}
		}
	</script>
</body>
</html>
<script type="text/javascript">
	$(document).ready(function() {
		$('#up').click(function(){
			var image_name = $('#im').val();
			if(image_name == '') {
				alert("Please select Image");
				return false;
			} else {
				var extension = $('im').val().split('.').pop().toLowerCase();
				if (jQuery.inArray(extension, ['gif','png', 'jpg', 'jpeg']) == -1) {
					alert('Invalid Image File');
					$('#im').val('');
					return false;
				}
			}
		});
	});
</script>