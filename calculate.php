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
						<h2 class='text-center text-dark'>Store Management System</h2>
						<p class='m-0 p-0 text-center text-dark'>Account Manager : <?php echo $_SESSION['user_name'];?></p>
						<p class='mb-3 p-0 text-center text-dark'>Date : <?php include('myFunction.php'); echo date('d-m-Y');?></p>
						
					<?php
							$sql   = "SELECT tsr.tsrp_id, p.p_name, p.p_code, p.p_price, tsr.tsrp_quantity, tsr.tsrp_date FROM tsales_report AS tsr INNER JOIN product AS p ON tsr.p_id=p.p_id;"; //start of show temporary sale product in table
							$query = $conn->query($sql);
							
							echo "<table class='table table-light table-striped table-hover'>
							<tr class='text-center'>
								<th>#</th>
								<th>Product Name</th>
								<th>Product Code</th>
								<th>Product Price</th>
								<th>Product Quantity</th>
								<th>Amount(TK)</th>
							</tr>";
							$sl_no = 0;
							$sum = 0;
							while($data  = mysqli_fetch_assoc($query)){
								$tsrp_id 	  	= $data['tsrp_id'];
								$tsrp_name    	= $data['p_name'];
								$tsrp_price   	= $data['p_price'];
								$tsrp_code   	= $data['p_code'];
								$tsrp_quantity  = $data['tsrp_quantity'];
								$tsrp_amount   	= ($tsrp_price*$tsrp_quantity);
								
								$sum = $sum+$tsrp_amount;
								
								$sl_no++;
								echo "<tr class='text-center'>
										<td>$sl_no</td>
										<td>$tsrp_name</td>
										<td>$tsrp_code</td>
										<td>$tsrp_price</td>
										<td>$tsrp_quantity</td>
										<td>$tsrp_amount</td>
									</tr>";
							}
							echo "</table>";//end of show temporary sale product in table
							echo "<div class='d-flex justify-content-end me-5'>
									<h5 class='text-right text-dark'>Total : $sum TK</h5>
								</div>";
							echo "<div class='d-flex mt-5'>
									<div class='me-auto'><a href='sale_product.php' class='btn btn-outline-secondary ps-5 pe-5'>Back</a></div>
									<div class='ms-auto'><a href='sale_product.php?okId=1' class='btn btn-outline-secondary ps-5 pe-5'>Ok</a></div>
								</div>";
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
