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
	<title>Home Page | SMS</title>
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
					
						<?php //all records of products
								
							$sql   = "SELECT sp_name, sp_code, sp_price, SUM(sp_quantity) AS sp_quantity, SUM(sp_quantity * sp_price) AS tsp_price
									FROM sales_report
									GROUP BY sp_name, sp_code;";
									
							$query = $conn->query($sql);
							
							if(mysqli_num_rows($query) > 0){
								echo "<div class='bg-success p-1 mb-3 text-center text-white'><h4>All Records List</h4></div>";
					
								echo "<table class='table table-light table-striped table-hover'>
									<tr class='text-center'>
										<th>#</th>
										<th>Product Name</th>
										<th>Product Code</th>
										<th>Product Price</th>
										<th>Product Quantity</th>
										<th>Sale Amount(TK)</th>
									</tr>";
								$sl_no = 0;
								$sum = 0;
								while($data = mysqli_fetch_assoc($query)){
									$sp_name  		= $data['sp_name'];
									$sp_code  		= $data['sp_code'];
									$sp_price   	= $data['sp_price'];
									$sp_quantity   	= $data['sp_quantity'];
									$tsp_price   	= $data['tsp_price'];
									
									
									$sum = $sum+$tsp_price;
									$sl_no++;
									echo "<tr class='text-center'>
											<td>$sl_no</td>
											<td>$sp_name</td>
											<td>$sp_code</td>
											<td>$sp_price</td>
											<td>$sp_quantity</td>
											<td>$tsp_price</td>
										</tr>";
								}
								echo "</table>";//end of show product in table
								echo "<div class='d-flex justify-content-end me-5'>
										<h5 class='text-right text-dark'>Total : $sum TK</h5>
									</div>";									
							}else{
								echo "<div class='alert alert-danger text-center'>No record found.</div>";
								echo "<script>setTimeout(function(){window.location='http://localhost/SMS/all_records.php';}, 2000);</script>";
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
