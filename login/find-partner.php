<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	header("location: ../index.php");
	exit;
}
$ch = "";
require_once '../config.php';
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
			$sitiou = mysqli_query($link, "SELECT * FROM userdetails WHERE gender = '$lgender'");
			$num = mysqli_num_rows($sitiou);
			if($num > 0) { ?>
				<div class="form-group">
						<form method="post">
					<div class="input-group" style="padding-top: 50px; width: 80%; padding-bottom: 10px;">
							<span class="input-group-addon">Search</span>
							<input type="text" name="search_text" id="search_text" placeholder="Search by Name or Address or Age or Dowry List" class="form-control" />
					</div>
							<button style="margin-left: 70px;" type="submit" name="search">Search</button>
						</form>
				</div>
			<?php }

			
			if (isset($_POST["search"])) {
				$ch = $_POST["search_text"];
				$search = mysqli_real_escape_string($link, $ch);
				$squery = "SELECT * FROM userdetails WHERE name  LIKE '%".$search."%' OR address  LIKE '%".$search."%' OR age   LIKE '%".$search."%' OR  dowrylist LIKE '%".$search."%' ORDER BY id DESC";
				$rip =  mysqli_query($link, $squery);
				if(mysqli_num_rows($rip) > 0) {
					while($row2 = mysqli_fetch_array($rip)) {
						if($row2["gender"] == $lgender) {
							echo '
							<div id="sh" class="table-responsive">
									<table >
										<tr>
											 <td style="padding-left: 50px; padding-top: 10px;" >
											 <img src="data:image/jpeg;base64,'.base64_encode($row2["image"]).'" alt="Profile picture" style="width: 120px; height: 120px;"> </td>
										<td style="padding-left: 30px;"><span><h3><a href="visitingprofile.php?id='.$row2["email"].'">'.$row2["name"].'</a></h3></span><span style="padding-top: 10px;">Age: '.$row2["age"].'</span><br><span>Address: '.$row2["address"].'</span><br><span>Dowry: '.$row2["dowrylist"].'</span></td>
										</tr>
									</table>
								</div>
							';
						} 
						
					}
				}
				 else{
					echo '<h1>No user found</h1>';
				}
			} else {
				$query = "SELECT * FROM userdetails WHERE gender = '$lgender' ORDER BY id DESC";
			$result = mysqli_query($link, $query);
			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					echo '
					<div id="sh" class="table-responsive">
							<table >
								<tr>
									 <td style="padding-left: 50px; padding-top: 10px;" >
									 <img src="data:image/jpeg;base64,'.base64_encode($row["image"]).'" alt="Profile picture" style="width: 120px; height: 120px;"> </td>
								<td style="padding-left: 30px;"><span><h3><a href="visitingprofile.php?id='.$row["email"].'">'.$row["name"].'</a></h3></span><span style="padding-top: 10px;">Age: '.$row["age"].'</span><br><span>Address: '.$row["address"].'</span><br><span>Dowry: '.$row["dowrylist"].'</span></td>
								</tr>
							</table>
						</div>
					';
				}
			} else{
				echo '<h1>No user found</h1>';
			}
			}

		 ?>


	</div>
</body>
</html>

