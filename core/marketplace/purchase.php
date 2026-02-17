<?php
	header('Content-Type: application/json; charset=utf-8');
	$productId = (int)$_REQUEST['productId'];
	$data = array('success' => 'true', 'status' => 'Bought', 'receipt' => $productId);
?>