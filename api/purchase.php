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
			//echo "user is logged in and recieved post";
			$type = strtolower(trim($_POST['typatransaction']));
			
			$asset_id = intval($_POST['asset_id']);
			$item = Asset::FromID($asset_id);
			if($item != null) {
				$result = TransactionUtils::BuyItem($type, $asset_id);
				if($result != "yay") {
					echo "{ \"error\" : true, \"message\":\"$result\"}";
				} else {
					echo "{ \"error\" : false, \"message\":\"Success!\"}";
				}
			} else {
				echo "{ \"error\" : true, \"message\":\"Asset does not exist!\"}";
			}
		} else {
			echo "{ \"error\" : true, \"message\":\"Invalid purchase.\"}";
		}

		
		die();
	} else {
		echo "{ \"error\" : true, \"message\":\"User is not logged in.\"}";
	}
?>