<?php 
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/asset.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/utilities/userutils.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/utilities/transactionutils.php";

	$user = UserUtils::RetrieveUser();
	header("Content-Type: application/json");
	if($user != null && !$user->IsBanned() && isset($_POST['asset_id']) && isset($_POST['typatransaction'])) {

		$allowed_types = [
			"cones",
			"lights",
			"freeitem"
		];

		if(in_array(trim(strtolower($_POST['typatransaction'])), $allowed_types)) {
			$type = strtolower(trim($_POST['typatransaction']));
			$result = TransactionUtils::BuyItem($type, intval($_POST['asset_id']));
			if($result != "yay") {
				echo "{ \"error\" : true, \"message\":\"$result\"}";
			} else {
				echo "{ \"error\" : false, \"message\":\"Success!\"}";
			}
		} else {
			echo "{ \"error\" : true, \"message\":\"Invalid purchase.\"}";
		}

		
		die();
	} else {
		echo "{ \"error\" : true, \"message\":\"User is not logged in.\"}";
	}
?>