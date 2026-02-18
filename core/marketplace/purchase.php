<?php
	header('Content-Type: application/json; charset=utf-8');
	
	if(!isset($_REQUEST['productId'])) {
		$productId = rand(0,100000);
	} else {
		$productId = (int)$_REQUEST['productId'];
	}
	$data = json_encode(array('success' => 'true', 'status' => 'Bought', 'receipt' => $productId));
?>