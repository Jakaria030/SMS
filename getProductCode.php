<?php
	require_once "sms_db.php";
	
	$pName = $_REQUEST['pName'];
	
	$sql = "SELECT p_code FROM product WHERE p_name = '$pName' GROUP BY p_code";
	$query = $conn->query($sql);
	
	if(mysqli_num_rows($query) > 0){
		while($data = mysqli_fetch_assoc($query)){
			$p_code  = $data['p_code'];
			echo "<option value='$p_code'>$p_code</option>";
		}
	}
?>