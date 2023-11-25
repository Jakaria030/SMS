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
	<title>Update Product | SMS</title>
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
						include ('myFunction.php'); //function file include here
						
						$p_id = $p_code = $p_name = $p_price = $p_quantity = $p_entry_date = ""; //fetch data from database and show into form
						if(isset($_GET['editId'])){
							$editId = $_GET['editId'];
							
							$sql   = "SELECT * FROM product WHERE p_id='$editId'";
							
							$query = $conn->query($sql);
							$data  = mysqli_fetch_assoc($query);
							
							$p_id			= $data['p_id'];
							$p_code			= $data['p_code'];
							$p_name 		= $data['p_name'];
							$p_price 		= $data['p_price'];
							$p_quantity 	= $data['p_quantity'];
							$p_entry_date 	= $data['p_entry_date'];
						}
						
						//collect data from form and validate then update
						$new_p_id = $new_p_name = $new_p_code = $new_p_price = $new_p_quantity = $new_p_entry_date = "";
						if(isset($_POST['update'])){
							$new_p_id 			= $_POST['p_id'];
							$new_p_name 		= validate_data($_POST['p_name']);
							$new_p_code 		= validate_data($_POST['p_code']);
							$new_p_price 		= validate_data($_POST['p_price']);
							$new_p_quantity 	= validate_data($_POST['p_quantity']);
							$new_p_entry_date 	= $_POST['p_entry_date'];

							$errors = array();
							
							if(empty($new_p_name) or empty($new_p_code) or empty($new_p_price) or empty($new_p_quantity) or empty($new_p_entry_date)){
								array_push($errors, 'All the fields are required.');
							}
							
							$sql = "SELECT * FROM product WHERE p_id=$new_p_id";
							$query = mysqli_query($conn,$sql);
							$result = mysqli_fetch_assoc($query);
							$exist_p_name = $result['p_name'];
							$exist_p_code = $result['p_code'];
							
							if(($exist_p_name != $new_p_name and $exist_p_code == $new_p_code) || ($exist_p_name = $new_p_name and $exist_p_code != $new_p_code)){
								$sql = "SELECT * FROM product WHERE p_name='$new_p_name' AND p_code='$new_p_code'";
								$query = mysqli_query($conn,$sql);
								$rowCount = mysqli_num_rows($query);
								if($rowCount > 0){
									array_push($errors, 'Product is already exists.');
								}
							}
						
							if(count($errors) > 0){
								foreach($errors as $error){
									echo "<div class='alert alert-danger'>$error</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/product_list.php';}, 1000);</script>";
								}
							}else{
								//Data update here
								$stmt = $conn->prepare("UPDATE product SET p_name=?, p_code=?, p_price=?, p_quantity=?, p_entry_date=? WHERE p_id=?");
								$stmt->bind_param('ssiisi', $new_p_name, $new_p_code, $new_p_price, $new_p_quantity, $new_p_entry_date, $new_p_id);
								
								if($stmt->execute()){
									echo "<div class='alert alert-success'>Update is successfull.</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/product_list.php';}, 1000);</script>";
								}
							}
						}
					?>
						
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"><!-- start form -->
							<div class="mb-3"> 
								<input type="text" class="form-control" name="p_id" placeholder="Product ID:" value="<?php echo $p_id;?>" hidden />
							</div>
							<div class="mb-3"> 
								<input type="text" class="form-control" name="p_name" placeholder="Product Name:" value="<?php echo $p_name;?>" />
							</div>
							<div class="mb-3"> 
								<input type="text" class="form-control" name="p_code" placeholder="Product Code:" value="<?php echo $p_code;?>"/>
							</div>
							<div class="mb-3"> 
								<input type="number" class="form-control" name="p_price" min="1" placeholder="Product Price:" value="<?php echo $p_price;?>" />
							</div>
							<div class="mb-3"> 
								<input type="number" class="form-control" name="p_quantity" min="1" placeholder="Product Quantity:" value="<?php echo $p_quantity;?>" />
							</div>
							<div class="mb-3"> 
								<input type="date" class="form-control" name="p_entry_date" value="<?php echo $p_entry_date;?>" />
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
	
	<?php include('jscript.php'); ?>
	
</body>
</html>
