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

	$sign_ids = [
		1, // StarterScript
		2, // Library Registration
		3, // RbxGui
		4, // RbxGear
		5, // RbxStatus
		6, // RbxUtility
		7, // RbxStamper
		9, // Tooltip
		10, // Settings
		18, // DialogScript / MainBotChatScript
		19, // PopupScript
		21, // Friend NotificationScript
		22, // ChatScript
		26, // PurchasePromptScript
		64, // Playerlist
		95, // BackpackBuilder
		107, //BackpackManager
		110, // BackpackGear
		111, // LoadoutScript
		140, // LoadingScript
	];

	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);

	$access = $settings['asset']['ACCESSKEY'];

	include $_SERVER["DOCUMENT_ROOT"] . "/core/asset.php";

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

	if($asset != null && $asset->status == AssetStatus::REJECTED && (!isset($_GET['access']) && $_GET['access'] == $access)) {
		die(http_response_code(403));
	} else {
		if(file_exists($filename)) {
			$handle = fopen($filename, "r"); 
			$contents = fread($handle, filesize($filename)); 
			fclose($handle);
			header("Content-Type: application/octet-stream");//.checkMimeType($contents));
			$contents = str_replace("www.roblox.com", "arl.lambda.cam",$contents);
			$contents = str_replace("api.roblox.com", "arl.lambda.cam",$contents);

			$contents = str_replace("arl.lambda.cam", $_SERVER['SERVER_NAME'], $contents);

			if(in_array($id, $sign_ids)) {
				$contents = "%$id%\r\n" . $contents;
				openssl_sign($contents, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
				$signature = base64_encode($signature);
				echo "%$signature%";
			}
			
			echo $contents;
		} else {
			die(http_response_code(404));
		}
	}

		/*else {
		error_reporting(0);
		$url = 'https://assetdelivery.roblox.com/v1/asset/?id='.$id.'';
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: .ROBLOSECURITY="));
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

		file_put_contents($_SERVER['DOCUMENT_ROOT']."/../assets/".$id, $contents);
		
		echo $contents;	
		//die(http_response_code(404));
	}*/
?>