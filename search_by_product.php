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
						
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
							<div class="d-flex mb-3"> 
								<div class="mb-3 me-2">
									<select class="form-control ps-4 pe-4" name="sp_name"  onchange="showProductCode(this.value)">
									<option value="">Product Name</option>
									<?php
										$sql = "SELECT p_name FROM product GROUP BY p_name";
										$query = $conn->query($sql);
										if(mysqli_num_rows($query) > 0){
											while($data = mysqli_fetch_assoc($query)){
												$p_name  = $data['p_name'];
												echo "<option value='$p_name'>$p_name</option>";
											}
										}
									?>
									</select>
								</div>
								<div class="mb-3 me-2">
									<select class="form-control ps-4 pe-4" name="sp_code" id="pCode">
										<option value="">Product Code</option>
									</select>
								</div>
								<div class="ms-2">
									<input name="search" class="btn btn-success my-2 my-sm-0" type="submit" value="Search">
								</div>
							</div>
						</form>	

						<?php //search product and show details
							include('myFunction.php');
							if(isset($_POST['search'])){
								$sp_name = $_POST['sp_name'];
								$sp_code = $_POST['sp_code'];
								
								$sql   = "SELECT sr.sp_date, sr.sp_code, sr.sp_price, SUM(sr.sp_quantity) AS sp_quantity
										FROM sales_report AS sr
										WHERE sr.sp_name='$sp_name' AND sr.sp_code='$sp_code'
										GROUP BY sr.sp_date";
										
								$query = $conn->query($sql);
								
								if(mysqli_num_rows($query) > 0){
									echo "<div class='bg-success p-1 mb-3 text-center text-white'><h4>Report List for $sp_name </h4></div>";
						
									echo "<table class='table table-light table-striped table-hover'>
										<tr class='text-center'>
											<th>#</th>
											<th>Sale Date</th>
											<th>Product Code</th>
											<th>Product Price</th>
											<th>Product Quantity</th>
											<th>Sale Amount(TK)</th>
										</tr>";
									$sl_no = 0;
									$sum = 0;
									while($data = mysqli_fetch_assoc($query)){
										$sp_date  		= format_date($data['sp_date']);
										$sp_code  		= $data['sp_code'];
										$sp_price   	= $data['sp_price'];
										$sp_quantity   	= $data['sp_quantity'];
										$sp_amount  	= ($sp_price*$sp_quantity);
										
										$sum = $sum+$sp_amount;
										$sl_no++;
										echo "<tr class='text-center'>
												<td>$sl_no</td>
												<td>$sp_date</td>
												<td>$sp_code</td>
												<td>$sp_price</td>
												<td>$sp_quantity</td>
												<td>$sp_amount</td>
											</tr>";
									}
									echo "</table>";//end of show product in table
									echo "<div class='d-flex justify-content-end me-5'>
											<h5 class='text-right text-dark'>Total : $sum TK</h5>
										</div>";									
								}else{
									echo "<div class='alert alert-danger text-center'>No record found.</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/search_by_product.php';}, 2000);</script>";
								}
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
	<script type="text/javascript"> 
		function showProductCode(str){
			if(str == ''){
				document.getElementById('pCode').innerHTML = '<option value="">Product Code</option>';
					return;
				}
				const xhttp = new XMLHttpRequest();
				xhttp.onload = function(){
				document.getElementById('pCode').innerHTML = this.responseText;
			}
			xhttp.open('GET', 'getProductCode.php?pName='+str);
			xhttp.send();
		}
	</script>
	
</body>
</html>
