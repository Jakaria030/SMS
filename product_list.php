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
	<title>Product List Page | SMS</title>
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
						include('myFunction.php');
						if(isset($_GET['deleteId'])){ //start of delete product
							$deleteId = $_GET['deleteId'];
		
							$sql = "DELETE FROM product WHERE p_id=$deleteId";
		
							if($conn->query($sql)){
								echo "<div class='alert alert-danger text-center'>Product is deleted from product list.</div>";
								echo "<script>setTimeout(function(){window.location='http://localhost/SMS/product_list.php';}, 2000);</script>";
							}
						}//end of delete product
						
						
						//start of show product in table
						$sql   = "SELECT * FROM product ORDER BY p_name, p_code"; 
						$query = $conn->query($sql);
						
						if(mysqli_num_rows($query) > 0){
							echo "<div class='bg-success p-1 mb-3 text-center text-white'><h4>Product List</h4></div>";
							echo "<table class='table table-light table-striped table-hover'>
							<tr class='text-center'>
								<th>#</th>
								<th>Product Name</th>
								<th>Product Code</th>
								<th>Product Price</th>
								<th>Product Quantity</th>
								<th>Product Entry Date</th>
								<th>Action</th>
							</tr>";
							$sl_no = 0;
							while($data  = mysqli_fetch_assoc($query)){
								$p_id 	  		= $data['p_id'];
								$p_name 	  	= $data['p_name'];
								$p_code    		= $data['p_code'];
								$p_price   		= $data['p_price'];
								$p_quantity   	= $data['p_quantity'];
								$p_entry_date   = format_date($data['p_entry_date']);
								
								$sl_no++;
								echo "<tr class='text-center'>
										<td>$sl_no</td>
										<td>$p_name</td>
										<td>$p_code</td>
										<td>$p_price</td>
										<td>$p_quantity</td>
										<td>$p_entry_date</td>
										<td>
											<a href='edit_product.php?editId=$p_id'class='btn btn-success'>Edit</a>
											<a href='product_list.php?deleteId=$p_id'><button type='button' class='btn btn-danger'>Delete</button></a>
										</td>
									</tr>";
							}
							echo "</table>";//end of show product in table
						}else{
							echo "<div class='alert alert-danger p-1 mb-3 text-center'><h4>Product is not found.</h4></div>";
						}	
						
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
