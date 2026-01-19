<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";
	header("Content-Type: application/json");

	$place_id = intval($_GET['universeId']);

	$place = Place::FromID($place_id);

	if($place != null) {
		echo json_encode([
			"enabled" => $place->teamcreate_enabled
		]);
	} else {
		echo "{}";
	}
?>