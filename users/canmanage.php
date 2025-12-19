<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	if(isset($_GET['userId']) && isset($_GET['placeId'])) {
		die(json_encode([
			"Success" => true,
			"CanManage" => true 
		]));
	}

	die(json_encode([
		"Success" => false,
		"CanManage" => false 
	]));
?>