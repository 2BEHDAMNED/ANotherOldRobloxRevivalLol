<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";

	//?keyword=&type=FreeModels&sortBy=Relevance&startOffset=0&resultSize=26&_=1770476003587

	header("Content-Type: application/json");

	$result = [];

	if(
		isset($_GET['keyword']) &&
		isset($_GET['type']) &&
		isset($_GET['sortBy']) &&
		isset($_GET['startOffset']) &&
		isset($_GET['resultSize'])
	) {
		$query = trim($_GET['keyword']);
		$type = $_GET['type'];
		$sort = $_GET['sortBy'];
		$startOffset = intval(trim($_GET['startOffset']));
		$resultSize = intval(trim($_GET['resultSize']));

		$assets = [];

		if($type == "FreeModels") {
			$assets = AssetUtils::GetPaged($query, AssetType::MODEL, 1, $resultSize);
		} elseif($type == "FreeDecals") {
			$assets = AssetUtils::GetPaged($query, AssetType::DECAL, 1, $resultSize);
		}

		foreach($assets as $asset) {
			if($asset instanceof Asset) {
				array_push($result, [
					"ID" => $asset->id,
					"AssetID" => $asset->id,
					"Name" => $asset->name,
					"IsVotable" => false
				]);
			}
		}
		
	}

	die(json_encode($result));
?>