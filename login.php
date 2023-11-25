<?php
	session_start();
	if(isset($_SESSION['user_name'])){
		header('Location: index.php');
	}
	
	require_once "sms_db.php";
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Login Page | SMS</title>
	<?php include("bfcdn.php");?>
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	
	<div class="container"><!-- start container -->
		
	<?php 
		if(isset($_POST['login'])){ //data collect from form and check than login
			$user_email		= $_POST['user_email'];
			$user_password	= $_POST['user_password'];
			
			$sql	= "SELECT * FROM users WHERE user_email='$user_email'";
			$result = mysqli_query($conn, $sql);
			$user 	= mysqli_fetch_array($result, MYSQLI_ASSOC);
			
			if($user){
				if($user_password == $user['user_password']){
					session_start();
					$_SESSION['user_name']=$user['user_name'];
					header('Location: index.php');
					die();
				}else{
					echo "<div class='alert alert-danger'>Password does not match.</div>";
				}
			}else{
				echo "<div class='alert alert-danger'>Email does not match.</div>";
			}
			
		}
	?>
				
		<form action="login.php" method="POST"><!-- start form -->
			<div class="form_group"> 
				<input type="email" class="form-control" name="user_email" placeholder="Email:"/>
			</div>
			<div class="form_group"> 
				<input type="password" class="form-control" name="user_password" placeholder="Password:"/>
			</div>
			<div class="form_btn d-grid"> 
				<input type="submit" class="btn btn-success" name="login" value="Login"/>
			</div>
		</form><!-- end form -->
		
	</div><!-- end container -->
	
</body>
</html>
