<?php

	$placeid = intval($_GET['placeid']);

	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";

	$place = Place::FromID($placeid);

	if($place != null) {
		echo json_encode([
			"UniverseId" => $placeid
		]);
	}
	

?>