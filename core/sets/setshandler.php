<?php
	require_once $_SERVER['DOCUMENT_ROOT']. "/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']. "/core/utilities/userutils.php";

	header("Content-Type: application/json");
	
	//startRowIndex=0&maximumRows=26&query=&rqtype=getmydecals

	if(isset($_GET['rqtype'])) {
		$request_type = $_GET['rqtype'];

		if($request_type == "getmydecals") {
			$user = UserUtils::RetrieveUser();

			if($user != null) {
				
				$decals = $user->GetAllOwnedAssetsOfType(AssetType::DECAL);
				$output_decals = [];
				foreach($decals as $decal) {
					if($decal instanceof Asset) {

						/*
							{
								"AssetSetID": 464140,
								"AssetTypeID": 10,
								"AssetVersionID": 890129,
								"ID": 7597721,
								"Name": "Vote to Kick Player Script",
								"SortOrder": 2147483647,
								"NewerVersionAvailable": "False",
								"AssetID": "300221",
								"IsEndorsed": false
							},
						*/

						$decal_data = [
							"AssetID" => $decal->id,
							"AssetSetID" => 0,
							"AssetVersionID" => $decal->GetVersionID(),
							"Name" => $decal->name,
							"IsEndorsed" => false,
							"IsVoteable" => true
						];

						array_push($output_decals, $decal_data);

					}
				}

				die(json_encode($output_decals));


			} else {
				die(json_encode(['error'=> true, 'reason' => 'request invalid']));
			}
		}
	} else {
		die(json_encode(['error'=> true, 'reason' => 'request invalid']));
	}	
?>