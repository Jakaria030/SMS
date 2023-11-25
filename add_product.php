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
	<title>Add Product | SMS</title>
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
						include ('myFunction.php'); //function file include and data collect from form and validate than insert
						
						$p_name = $p_price = $p_quantity = $p_entry_date = "";
						if(isset($_POST['add_product'])){
							$p_name 		= validate_data($_POST['p_name']);
							$p_code 		= validate_data($_POST['p_code']);
							$p_price 		= validate_data($_POST['p_price']);
							$p_quantity 	= validate_data($_POST['p_quantity']);
							$p_entry_date 	= $_POST['p_entry_date'];
							
							$errors = array();
							
							if(empty($p_code) or empty($p_name) or empty($p_price) or empty($p_quantity) or empty($p_entry_date)){
								array_push($errors, 'All the fields are required.');
							}
							
							$sql = "SELECT * FROM product WHERE p_name='$p_name' AND p_code='$p_code'";
							$query = $conn->query($sql);
							if(mysqli_num_rows($query) > 0){
								array_push($errors, 'Product is already Exist. You can update it.');
							}
						
							if(count($errors) > 0){
								foreach($errors as $error){
									echo "<div class='alert alert-danger'>$error</div>";
								}
								echo "<script>setTimeout(function(){window.location='http://localhost/SMS/add_product.php';}, 2000);</script>";
							}else{
								//product insert here
								$stmt = $conn->prepare("INSERT INTO product(p_code, p_name, p_price, p_quantity, p_entry_date) VALUES(?,?,?,?,?)");
								$stmt->bind_param('ssiis', $p_code, $p_name, $p_price, $p_quantity, $p_entry_date);
								
								if($stmt->execute()){
									echo "<div class='alert alert-success'>Your product is added successfully.</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/add_product.php';}, 2000);</script>";
								}
							}
						}
					?>
						
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"><!-- start form -->
							<div class="mb-3"> 
								<input type="text" class="form-control" name="p_name" placeholder="Product Name:" />
							</div>
							<div class="mb-3"> 
								<input type="text" class="form-control" name="p_code" placeholder="Product Code:" />
							</div>
							<div class="mb-3"> 
								<input type="number" class="form-control" name="p_price" min="1" placeholder="Product Price:" />
							</div>
							<div class="mb-3"> 
								<input type="number" class="form-control" name="p_quantity" min="1" placeholder="Product Quantity:" />
							</div>
							<div class="mb-3"> 
								<input type="date" class="form-control" name="p_entry_date"/>
							</div>
							<div class="form_btn"> 
								<input type="submit" class="btn btn-success" name="add_product" value="Add"/>
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
