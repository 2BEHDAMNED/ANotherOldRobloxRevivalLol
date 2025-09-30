<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetuploader.php";

	$user = UserUtils::RetrieveUser();

	function FunnyStrToBool(string $value): bool {
		return $value == "True";
	}

	if($user != null) {
		if(isset($_GET['assetid'])) {
			$assetid = intval($_GET['assetid']);

			if($assetid == 0) {
				// Publish new item

				$timer = 31;
				if($user->GetLatestAssetUploaded() != null) {
					$difference = (time()-($user->GetLatestAssetUploaded()->created_at->getTimestamp()-3600));
					$timer = $difference;
				}

				if($timer < 30) {
					http_response_code(500);
					die("You are uploading too many assets! Wait a bit!");
				}

				/*
					type
					name
					description
					ispublic
					commentsenabled
					serversize
					chattype
					iscopylocked
				*/

				if(
					isset($_GET['type']) &&
					isset($_GET['name']) &&
					isset($_GET['description']) &&
					isset($_GET['ispublic']) &&
					isset($_GET['commentsenabled']) &&
					isset($_GET['serversize']) &&
					isset($_GET['chattype']) &&
					isset($_GET['iscopylocked'])
				) {
					
				}

			} else {
				$asset = Asset::FromID($assetid);

				if($asset != null && $asset->creator->id == $user->id) {
					// If the user owns this asset, then allow publishing.
				}
			}
		}
	}

	http_response_code(500);
	die("Action failed.");

?>