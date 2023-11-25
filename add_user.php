<?php 
	session_start();
	if(!isset($_SESSION['user_name'])){
		header('Location: login.php');
	}
	
	require_once "sms_db.php";
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Add User | SMS</title>
	<?php include("bfcdn.php");?>
</head>
<body>
	
	<div class="container border border-success p-0"><!--start of container-->
	
		<div class="container-fluid border-bottom border-success"><!--start of header-->
			<?php include('header.php'); ?>
		</div><!--end of header-->
		
		
		<div class="container-fluid"><!--start of body-->
			
			<div class="row"><!--start of row-->
			
				<div class="col-sm-3 p-0 border-end border-success"><!--start of leftside-->
					<?php include('leftside.php'); ?>
				</div><!--end of leftside-->
				
				<div class="col-sm-9"> <!--start of rightside-->
					<div style="max-width:400px" class="container p-3 m-3"> 
					
					<?php
						include ('myFunction.php'); //function file include and collect information from form and validate than insert
						
						$user_name = $user_email = $user_password = $repeat_password = "";
						if(isset($_POST['register'])){
							$user_name 			= validate_data($_POST['user_name']);
							$user_email 		= validate_data($_POST['user_email']);
							$user_password 		= validate_data($_POST['user_password']);
							$repeat_password 	= validate_data($_POST['repeat_password']);

							$errors = array();
							
							if(empty($user_name) or empty($user_email) or empty($user_password) or empty($repeat_password)){
								array_push($errors, 'All the fields are required.');
							}
							if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
								array_push($errors, "Email is not valid.");
							}
							if(strlen($user_password) < 8){
								array_push($errors, 'Password must be at least 8 character long.');
							}
							if($user_password != $repeat_password){
								array_push($errors, 'Password does not match.');
							}
							$sql = "SELECT * FROM users WHERE user_email='$user_email'";
							$result = mysqli_query($conn, $sql);
							$rowCount = mysqli_num_rows($result);
							if($rowCount > 0){
								array_push($errors, 'Email already exists.');
							}
						
							if(count($errors) > 0){
								foreach($errors as $error){
									echo "<div class='alert alert-danger'>$error</div>";
								}
								echo "<script>setTimeout(function(){window.location='http://localhost/SMS/add_user.php';}, 2000);</script>";
							}else{
								//user information insert here
								$stmt = $conn->prepare("INSERT INTO users(user_name, user_email, user_password) VALUES(?,?,?)");
								$stmt->bind_param('sss', $user_name, $user_email, $user_password);
									
								if($stmt->execute()){
									echo "<div class='alert alert-success'>You are registered successfully.</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/add_user.php';}, 2000);</script>";
								}
							}
						}
						
					?>
						
						<form action="add_user.php" method="POST"><!-- start form -->
							<div class="mb-3"> 
								<input type="text" class="form-control" name="user_name" placeholder="Full Name:" />
							</div>
							<div class="mb-3"> 
								<input type="email" class="form-control" name="user_email" placeholder="Email:" />
							</div>
							<div class="mb-3"> 
								<input type="password" class="form-control" name="user_password" placeholder="Password:" />
							</div>
							<div class="mb-3"> 
								<input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:" />
							</div>
							<div class="form_btn"> 
								<input type="submit" class="btn btn-success" name="register" value="Register"/>
							</div>
						</form><!-- end form -->
					</div>
				</div><!--end of right-->

			</div><!--end of row-->

		</div><!--end of body-->
		
		
		<div class="container-fluid border-top border-success"><!--start of footer-->
			<?php include('footer.php'); ?>
		</div><!--end of footer-->
		
	</div><!--start of container-->
	
	<?php include('jscript.php'); ?>
	
</body>
</html>
