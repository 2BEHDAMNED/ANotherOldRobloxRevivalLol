<?php

	function IsRewrite() {
		if(!empty($_SERVER['IIS_WasUrlRewritten']))
			return true;
		else if(array_key_exists('HTTP_MOD_REWRITE',$_SERVER))
			return true;
		else if( array_key_exists('REDIRECT_URL', $_SERVER))
			return true;
		else
			return false;
	}

	if(!isset($_GET['id']) && !isset($_GET['ID'])) {
		die(http_response_code(500));
	}

	if(isset($_GET['id'])) {
		$id = intval($_GET["id"]);
	} else if(isset($_GET['ID'])) {
		$id = intval($_GET["ID"]);
	}

	if(!IsRewrite()) {
		die(header("Location: /asset/?id=".$id));
	}

	function checkMimeType($contents) {
		$file_info = new finfo(FILEINFO_MIME_TYPE);
		return $file_info->buffer($contents);
	}

	$sign_ids = [];

	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);

	$access = $settings['asset']['ACCESSKEY'];

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER["DOCUMENT_ROOT"] . "/core/classes/asset.php";
	
	$user = UserUtils::RetrieveUser();

	$asset = Asset::FromID($id);
	if($asset != null) {
		if(isset($_GET['version']) && intval($_GET['version']) != 0) {
			$version = intval($_GET['version']);
			$asset_version = AssetVersion::GetVersionOf($asset, $version);

			if($asset_version != null) {
				$filename = $_SERVER['DOCUMENT_ROOT']."/../assets/".$asset_version->md5sig;
			} else {
				die(http_response_code(404));
			}

		} else {
			$filename = $_SERVER['DOCUMENT_ROOT']."/../assets/".$asset->GetLatestVersionDetails()->md5sig;
		}
		
	} else {
		// TESTING REASONS ONLY, DO NOT USE ON PROD AT ALL.
		$filename = $_SERVER['DOCUMENT_ROOT']."/../assets/$id";
	}

	if($asset != null) {
		/*if(
			(
				!isset($_GET['access']) || 
				(isset($_GET['access']) && $_GET['access'] != $access)
			) && $user == null
		) {
			die(http_response_code(503));
		}*/
		
		if($asset->type == AssetType::PLACE) {
			$place = Place::FromID($asset->id);
			
			if($place->copylocked && $user->id != $place->creator->id
				&& (!isset($_GET['access']) || 
				(isset($_GET['access']) && $_GET['access'] != $access))
			) {
				die(http_response_code(503));
			}
		}
		
		if(file_exists($filename)) {
//			die(strval(filesize($filename)));
			$handle = fopen($filename, "r"); 
			$contents = fread($handle, filesize($filename)); 
			fclose($handle);
			header("Content-Type: application/octet-stream");//.checkMimeType($contents));
			$contents = str_replace("www.roblox.com", "arl.lambda.cam",$contents);
			$contents = str_replace("api.roblox.com", "arl.lambda.cam",$contents);

			$contents = str_replace("arl.lambda.cam", $_SERVER['SERVER_NAME'], $contents);

			if (isset($_GET['serverplaceid'])) {
				$serverplace = Place::FromID(intval($_GET['serverplaceid']));
				
				if ($serverplace == null && intval($_GET['serverplaceid']) != 0) {
					http_response_code(400);
					die("Bad Request");
				}

				$blacklist = ["MeshId", "Script", "Remote", "Service", "Model"];
				$whitelist = ["Keyframe", "Animation"];
				
				foreach($whitelist as $white) {
					if(strpos($contents, $white) !== false) {
						foreach($blacklist as $black) {
							if(strpos($contents, $black) !== false && (intval($_GET['serverplaceid']) != 0 && $asset->type != AssetType::HAT)) {
								http_response_code(405);
								die("Method Not Allowed");
							}
						}
					}
				}
			}
			
			echo $contents;
		} else {
			die(http_response_code(404));
		}
	} else {
		error_reporting(0);

		if(isset($_GET['version'])) {
			$version = intval($_GET['version']);
		}

		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../assets/rbx_".$id.(isset($_GET['version']) ?  "_".$version : ""))) {
			$url = 'https://assetdelivery.roblox.com/v1/asset/?id='.$id.(isset($_GET['version']) ? '&version='.$version : "");
			$ch = curl_init ($url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: .ROBLOSECURITY=_|WARNING:-DO-NOT-SHARE-THIS.--Sharing-this-will-allow-someone-to-log-in-as-you-and-to-steal-your-ROBUX-and-items.|_CAEaAhADIhwKBGR1aWQSFDEzMDE4NzM0Nzk4MTY5MzI2NzM3KAM.96IxbhII0plfO0-sutwtWcZ6VGtGpUkrb_qA4-7erYDzkobQOxhyPmagepO2qf-k7Cg4JcYtwS2md-cb-5578IPsFclIzQNPNaGMxMKrXokv5IB-bI_3RWKg-sgMw69GWpr51wdf5K9Kya07CbiXAMome47HcAoUUvfdTzvobPLluzxstvCQ2JLryJH3diLQBQMpn8aslB4z4jHcOfHCBReitSz2ognLYJ2ytVFwVKAY-BALtRnbblwSAIX2b6UDVtizP3HRSh0vLhpIYuY3X4RoR0wtI8bdXpFkrj3oRZlSMLfuBobC3fB4Ou8Y92uroPguQSAVxx3SMDFSYzhcokztPirv_vL7ToiLsxYb_5M5InFA1NpM65_PxowFBbAFWWhsiMz2t62t0_TcUqD6lPyypxEN9auJgVdARFv0wtof_9KbuOjhK23pMPuwKuDTMSpDo0jgdhe10b2yN3TYVcnaq2itXzikJh04kjKAl6cJ7oWWh90NaEBUtEdUnk-zpA4T7hM3e8Qg1XR-mMccz63kN509NO2yb2-8zRZ1WzDuWDrYZPrhQKcZ1qQtHs9Jy3S2fa-hsvvo-aw2n034UPchOc6VlroAVU-2e8C2Wgu1eLE_dRgnKr4v0MmjSSvaC5NK-DMKHrlSFaD5deWhLhI7bU0YdwH2DG-J4L-g2QSH9rrh24WBWPyAZgsk0ei0b8VJ5vCUU0OcPlrggx9KMKqTkjjHzCXYftp3wwwgKLsIPbxW07dz2NUqsH59gyDRiUpOwITvm3u6AvGVSG4R2VmIlkyLnmH4h38NZ2QAagWyz0EY"));
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$output = curl_exec($ch);
			curl_close($ch);

			if(strlen(gzdecode($output)) != 0) {
				$output = gzdecode($output);
			}

			header("Content-Type: ".checkMimeType($output));

			$contents = str_replace("www.roblox.com", "arl.lambda.cam",$output);

			if(!isset($_GET['version'])) {
				file_put_contents($_SERVER['DOCUMENT_ROOT']."/../assets/rbx_".$id, $contents);
			} else {
				file_put_contents($_SERVER['DOCUMENT_ROOT']."/../assets/rbx_".$id."_".$version, $contents);
			}
		} else {
			$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../assets/rbx_".$id.(isset($_GET['version']) ?  "_".$version : ""));
		}
		
		echo $contents;	
		//die(http_response_code(404));
	}
?>
