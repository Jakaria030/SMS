<?php
function validate_data($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
							
	return $data;
}

date_default_timezone_set("Asia/Dhaka");
function format_date($date){
	$date = new DateTime($date);
							
	return $date->format('d-m-Y');
}
?>