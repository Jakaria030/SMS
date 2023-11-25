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
				
					<div class="container p-2 m-2">
						<div class="row p-2"> 
							<div class="col-sm-3"> 
								<a href="sale_product.php"><i class="ms-2 fas fa-cart-shopping fa-3x text-success" ></i></a>
								<p>Sale Product</p>
							</div>
						</div>
						<hr/>
						<div class="row p-2"> 
							<div class="col-sm-3"> 
								<a href="add_product.php"><i class="ms-2 fas fa-folder-plus fa-3x text-success" ></i></a>
								<p>Add Product</p>
							</div>
							<div class="col-sm-3"> 
								<a href="product_list.php"><i class="ms-2 fas fa-list fa-3x text-success" ></i></a>
								<p>Product List</p>
							</div>
						</div>
						<hr/>
						<div class="row p-2"> 
							<div class="col-sm-3"> 
								<a href="all_records.php"><i class="ms-2 fas fa-chart-bar fa-3x text-success" ></i></a>
								<p>All Records</p>
							</div>
							<div class="col-sm-3"> 
								<a href="search_by_product.php"><i class="ms-2 fas fa-chart-simple fa-3x text-success" ></i></a>
								<p>Search By<br/>Products</p>
							</div>
							<div class="col-sm-3"> 
								<a href="search_by_date.php"><i class="ms-2 fas fa-chart-line fa-3x text-success" ></i></a>
								<p>Search By<br/>Date</p>
							</div>
						</div>
						<hr/>
						<div class="row p-2"> 
							<div class="col-sm-3"> 
								<a href="add_user.php"><i class="ms-2 fas fa-user-plus fa-3x text-success" ></i></a>
								<p>Add User</p>
							</div>
							<div class="col-sm-3"> 
								<a href="users_list.php"><i class="ms-2 fas fa-users fa-3x text-success" ></i></a>
								<p>User's List</p>
							</div>
						</div>
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
