
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
						<div class='bg-success p-1 mb-3 text-center text-white'><h4>Sale Product</h4></div>
						
						<?php //start of copy data from tsales_report table to sales_report table and last delete tsales_report table
							include('myFunction.php');
							if(isset($_GET['okId'])){
								$sql1 = "SELECT p.p_name, p.p_code, p.p_price, tsr.tsrp_quantity, tsr.tsrp_date FROM tsales_report AS tsr INNER JOIN product AS p ON tsr.p_id=p.p_id;"; //join two table
								$query1 = $conn->query($sql1);
								
								while($data = mysqli_fetch_assoc($query1)){
									$sp_name    	= $data['p_name'];
									$sp_code   		= $data['p_code'];
									$sp_price   	= $data['p_price'];
									$sp_quantity   	= $data['tsrp_quantity'];
									$sp_date   		= $data['tsrp_date'];
									
									$sql2 = "INSERT INTO sales_report(sp_name,sp_code,sp_price,sp_quantity,sp_date)VALUES('$sp_name','$sp_code',$sp_price,$sp_quantity,'$sp_date')";
									
									$conn->query($sql2);
								}
								//update product quantity of product table
								$sql3 = "UPDATE product 
										SET p_quantity=p_quantity-(SELECT COALESCE(SUM(tsrp_quantity), 0) 
																   FROM tsales_report 
																   WHERE product.p_id = tsales_report.p_id)
										WHERE p_id IN (SELECT p_id FROM tsales_report)";
									
								$conn->query($sql3);
								
								//start of delete all data from tsales_report table
								$sql4 = "DELETE FROM tsales_report";
								
								if($conn->query($sql4) == TRUE){
									echo "<div class='alert alert-success text-center'>Thank you.</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/sale_product.php';}, 2000);</script>";
								}
							}
						?>
						
						
						<?php
							if(isset($_GET['deleteId'])){ //start of delete product from sale list
								$deleteId = $_GET['deleteId'];
		
								$sql = "DELETE FROM tsales_report WHERE tsrp_id=$deleteId";
		
								if($conn->query($sql)){
									echo "<div class='alert alert-danger text-center'>Product is deleted from sale list.</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/sale_product.php';}, 2000);</script>";
								}
							}//end of delete product from sale list
						
						
							if(isset($_POST['add'])){ //data get from form and add tsales_report table
								$tsrp_name 		= $_POST['tsrp_name'];
								$tsrp_code 		= $_POST['tsrp_code'];
								$tsrp_quantity 	= $_POST['tsrp_quantity'];
								$tsrp_date 		= $_POST['tsrp_date'];
								
								if(empty($tsrp_quantity)){
									echo "<div class='alert alert-danger text-center'>Please select product quantity.</div>";
									echo "<script>setTimeout(function(){window.location='http://localhost/SMS/sale_product.php';}, 2000);</script>";
								}else{
									$sql1 = "SELECT * FROM product WHERE p_name='$tsrp_name' AND p_code='$tsrp_code'";
								
									$query1 = mysqli_query($conn, $sql1);
									if(mysqli_num_rows($query1) > 0){
										$data  = mysqli_fetch_assoc($query1);
										$p_id = $data['p_id'];
										
										$sql2 = "INSERT INTO tsales_report(p_id, tsrp_quantity, tsrp_date) VALUES($p_id,$tsrp_quantity,'$tsrp_date')";
										$conn->query($sql2);
									}else{
										echo "<div class='alert alert-danger text-center'>Please select a product.</div>";
										echo "<script>setTimeout(function(){window.location='http://localhost/SMS/sale_product.php';}, 2000);</script>";
									}
								}								
							}
							
						?>
										
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"><!-- start form -->
						<div class="d-flex flex-row "> 
							<div class="mb-3 me-2"> 
								<select class="form-control" name="tsrp_name" onchange="showProductCode(this.value)">
								<option value="">Prodcut Name</option>
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
								<select class="form-control" name="tsrp_code" id="pCode">
									<option value="">Product Code</option>
								</select>
							</div>
							<div class="mb-3 me-2"> 
								<input type="number" class="form-control" min="1" name="tsrp_quantity" placeholder="Product Quantity:"/>
							</div>
							<div class="mb-3 me-2"> 
								<input type="date" class="form-control" name="tsrp_date" value="<?php echo date('Y-m-d');?>" hidden />
							</div>
							<div class="form_btn"> 
								<input type="submit" class="btn btn-success" name="add" value="Add"/>
							</div>
						</div>
						</form><!-- end form -->
						
						<?php //start of show temporary sale product in table
							$sql3   = "SELECT tsr.tsrp_id, p.p_name, p.p_code, p.p_price, tsr.tsrp_quantity FROM tsales_report AS tsr INNER JOIN product AS p ON tsr.p_id=p.p_id"; //join two table
							
							$query3 = $conn->query($sql3);
							
							if(mysqli_num_rows($query3) > 0){
								echo "<table class='table table-light table-striped table-hover'>
							<tr class='text-center'>
								<th>#</th>
								<th>Product Name</th>
								<th>Product Code</th>
								<th>Product Price</th>
								<th>Product Quantity</th>
								<th>Total Amount</th>
								<th>Action</th>
							</tr>";
							$sl_no = 0;
							while($data = mysqli_fetch_assoc($query3)){
								$tsrp_id 	  	= $data['tsrp_id'];
								$tsrp_name    	= $data['p_name'];
								$tsrp_code   	= $data['p_code'];
								$tsrp_price   	= $data['p_price'];
								$tsrp_quantity  = $data['tsrp_quantity'];
								$tsrp_amount   	= ($tsrp_price*$tsrp_quantity);
								
								$sl_no++;
								echo "<tr class='text-center'>
										<td>$sl_no</td>
										<td>$tsrp_name</td>
										<td>$tsrp_code</td>
										<td>$tsrp_price</td>
										<td>$tsrp_quantity</td>
										<td>$tsrp_amount</td>
										<td>
											<a href='sale_product.php?deleteId=$tsrp_id'><button type='button' class='btn btn-danger'>Delete</button></a>
										</td>
									</tr>";
							}
							echo "</table>";//end of show temporary sale product in table
							echo "<a href='calculate.php'><button type='button' class='btn btn-success'>Calculate</button></a>";
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
