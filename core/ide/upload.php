<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetuploader.php";

	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	$rcc_settings = $settings['renderer'];

	$access = $settings['asset']['ACCESSKEY'];
	
	$user = UserUtils::RetrieveUser();

	function FunnyStrToBool(string $value): bool {
		return $value == "True";
	}

	/* thank you weeg <3 */
	function ValidateRoblox_XML(string $XML_Data): bool {
		//FIND BETTER WAY TO DO THIS
		$xml = new DOMDocument();
		$xml->loadXML($XML_Data);

		if(!@$xml->schemaValidate($_SERVER['DOCUMENT_ROOT']."/roblox.xsd")){
			//throw new Exception("Invalid LEGACY ROBLOX XML Format file");
			return false;
		}else{
			//echo "Valid XML File<br>";
			return true;
		}
	}

	if($user == null) {
		if(isset($_GET['security'])) {
			$user = User::FromSecurityKey(urldecode($_GET['security']));
		}
	}

	if($user != null || (isset($_GET['access']) && $_GET['access'] == $access)) {
		if(isset($_GET['assetid'])) {
			$assetid = intval($_GET['assetid']);

			if($assetid == 0 && $user != null) {
				// Publish new item

				$timer = 31;
				if($user->GetLatestAssetUploaded() != null) {
					$difference = (time()-($user->GetLatestAssetUploaded()->created_at->getTimestamp()-3600));
					$timer = $difference;
				}

				if($timer < 30) {
					http_response_code(501);
					die("You are uploading too many assets! Wait a bit!");
				}

				/*
					type
					name
					description
					ispublic
					commentsenabled
					serversize
					iscopylocked
				*/

				if(
					isset($_GET['type']) &&
					isset($_GET['name']) &&
					isset($_GET['description']) &&
					isset($_GET['ispublic']) &&
					isset($_GET['commentsenabled'])
				) {
					$type = $_GET['type'];
					$name = urldecode($_GET['name']);
					$description = urldecode($_GET['description']);
					$public = FunnyStrToBool($_GET['ispublic']);
					$comments_enabled = FunnyStrToBool($_GET['commentsenabled']);

					$recieveddata = file_get_contents("php://input");
					//echo "parsed:".$recieveddata;
					if(strlen(gzdecode($recieveddata)) != 0) {
						$recieveddata = gzdecode($recieveddata);
						echo "decoding using gz\n";
					}
					die(http_response_code(502));
					
				} else {
					die(http_response_code(502));
				}

			} else {
				$asset = Asset::FromID(intval($assetid));

				$recieveddata = file_get_contents("php://input");
				//echo "parsed:".$recieveddata;
				if(strlen(gzdecode($recieveddata)) != 0) {
					$recieveddata = gzdecode($recieveddata);
					echo "decoding using gz\n";
				}

				if($asset != null) {
					if($asset->type == AssetType::PLACE) {
						$place = Place::FromID(intval($assetid));

						if(($user != null && $asset->creator->id == $user->id) || ($place->teamcreate_enabled && (($user != null && $place->IsCloudEditor($user))  || (isset($_GET['access']) && $_GET['access'] == $access)))) {
							// If the user owns this asset, then allow publishing.
					
							$result = AssetUploader::UpdatePlace($assetid, $recieveddata, $asset->creator);
							if($result['error'] == true) {
								http_response_code(500);
								die($result['reason']);
							}
							http_response_code(200);
							die("Uploaded successfully!");
						}
						
					}
					
				}
			}
		}
	} else {
		http_response_code(503);
		die("Action failed.");
	}

	http_response_code(500);
	die("Action failed.");

?>