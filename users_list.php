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
	<title>User's List | SMS</title>
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
					<div class="container p-3 m-3"> 
						
					<?php
						if(isset($_GET['deleteid'])){ //start of delete user
							$getid = $_GET['deleteid'];
		
							$sql = "DELETE FROM users WHERE user_id=$getid";
		
							if($conn->query($sql)){
								echo "<div class='alert alert-danger text-center'>User is deleted from user's list.</div>";
								echo "<script>setTimeout(function(){window.location='http://localhost/SMS/users_list.php';}, 2000);</script>";
							}
						}//end of delete user
						
						//start of show user's information in table
						echo "<div class='bg-success p-1 mb-3 text-center text-white'><h4>User's List</h4></div>";
						$sql   = "SELECT * FROM users"; 
						$query = $conn->query($sql);
						
						if(mysqli_num_rows($query) > 0){
							echo "<table class='table table-light table-striped table-hover'>
							<tr class='text-center'>
								<th>#</th>
								<th>Name</th>
								<th>Email</th>
								<th>Action</th>
							</tr>";
							$sl_no = 0;
							while($data  = mysqli_fetch_assoc($query)){
								$user_id 	  = $data['user_id'];
								$user_name    = $data['user_name'];
								$user_email   = $data['user_email'];
								
								$sl_no++;
								echo "<tr class='text-center'>
										<td>$sl_no</td>
										<td>$user_name</td>
										<td>$user_email</td>
										<td>
											<a href='edit_user.php?id=$user_id'class='btn btn-success'>Edit</a>
											<a href='users_list.php?deleteid=$user_id'><button type='button' class='btn btn-danger'>Delete</button></a>
										</td>
									</tr>";
							}
							echo "</table>";
						}
						//end of show user's information in table
					?>
						
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
