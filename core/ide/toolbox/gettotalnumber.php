<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";

	//?keyword=&type=FreeModels&sortBy=Relevance&startOffset=0&resultSize=26&_=1770476003587

	header("Content-Type: application/json");

	$result = [
        [
            "TotalNumberOfResults" => 0
        ]
    ];

	if(
		isset($_GET['keyword']) &&
		isset($_GET['type']) &&
		isset($_GET['sortBy'])
	) {
		$query = trim($_GET['keyword']);
		$type = $_GET['type'];
		$sort = $_GET['sortBy'];

		if($type == "FreeModels") {
			$result[0]['TotalNumberOfResults'] = count(AssetUtils::Get($query, AssetType::MODEL));
		} elseif($type == "FreeDecals") {
			$result[0]['TotalNumberOfResults'] = count(AssetUtils::Get($query, AssetType::DECAL));

        }
		
	}

	die(json_encode($result));
?>