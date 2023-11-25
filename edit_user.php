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
	<title>Update User | SMS</title>
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
						include ('myFunction.php'); //function file include
						
						// fetch information from database and show into input form
						$user_id = $user_name = $user_email="";
						if(isset($_GET['id'])){ 
							$getid = $_GET['id'];
							
							$sql   = "SELECT * FROM users WHERE user_id=$getid";
							
							$query = mysqli_query($conn, $sql);
							$data  = mysqli_fetch_assoc($query);
							
							$user_id = $data['user_id'];
							$user_name = $data['user_name'];
							$user_email = $data['user_email'];
						}
						
						// collect information from form and validate than update
						$new_user_name = $new_user_email = $new_user_password = $new_repeat_password = "";
						if(isset($_POST['update'])){
							$new_user_id 			= $_POST['user_id'];
							$new_user_name 			= validate_data($_POST['user_name']);
							$new_user_email 		= validate_data($_POST['user_email']);
							$new_user_password 		= validate_data($_POST['user_password']);
							$new_repeat_password 	= validate_data($_POST['repeat_password']);

							$errors = array();
							
							if(empty($new_user_name) or empty($new_user_email) or empty($new_user_password) or empty($new_repeat_password)){
								array_push($errors, 'All the fields are required.');
							}
							if(!filter_var($new_user_email, FILTER_VALIDATE_EMAIL)){
								array_push($errors, "Email is not valid.");
							}
							if(strlen($new_user_password) < 8){
								array_push($errors, 'Password must be at least 8 character long.');
							}
							if($new_user_password != $new_repeat_password){
								array_push($errors, 'Password does not match.');
							}
							$sql = "SELECT * FROM users WHERE user_id=$new_user_id";
							$query = mysqli_query($conn, $sql);
							$result = mysqli_fetch_assoc($query);
							$exist_user_email = $result['user_email'];
							
							if($exist_user_email != $new_user_email){
								$sql = "SELECT * FROM users WHERE user_email='$new_user_email'";
								$query = mysqli_query($conn, $sql);
								$rowCount = mysqli_num_rows($query);
								if($rowCount > 0){
									array_push($errors, 'Email already exists.');
								}
							}
						
							if(count($errors) > 0){
								foreach($errors as $error){
									echo "<div class='alert alert-danger'>$error</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/users_list.php';}, 2000);</script>";
								}
							}else{
								//user information update here
								$stmt = $conn->prepare("UPDATE users SET user_name=?, user_email=?, user_password=? WHERE user_id=?");
								$stmt->bind_param('sssi', $new_user_name, $new_user_email, $new_user_password, $new_user_id);
								
								if($stmt->execute()){
									echo "<div class='alert alert-success'>Update is successfull.</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/users_list.php';}, 2000);</script>";
								}
							}
						}
					?>

						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"><!-- start form -->
							<div class="mb-3">
								<input type="text" class="form-control" name="user_id" value="<?php echo $user_id;?>" hidden />
							</div>
							<div class="mb-3">
								<input type="text" class="form-control" name="user_name" placeholder="Name:" value="<?php echo $user_name;?>"/>
							</div>
							<div class="mb-3">
								<input type="email" class="form-control" name="user_email" placeholder="Email:" value="<?php echo $user_email;?>" />
							</div>
							<div class="mb-3">
								<input type="password" class="form-control" name="user_password" placeholder="Password:" />
							</div>
							<div class="mb-3">
								<input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:" />
							</div>
							<div class="form_btn"> 
								<input type="submit" class="btn btn-success" name="update" value="Update"/>
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
	
</body>
</html>
