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
		37801172, // StarterScript
		59002209, // Sections
		60595695, // Library Registration
		45284430, // RbxGui
		45374389, // RbxGear
		52177566, // RbxStatus
		60595411, // RbxUtility
		73157242, // RbxStamper
		36868950, // Tooltip
		46295863, // Settings
		39250920, // DialogScript / MainBotChatScript
		48488235, // Playerlist
		48488451, // PopupScript
		48488398, // Friend NotificationScript
		52177626, // RBXStatusBuffsGUIScript
		52177590, // HealthScript v4.0
		53878047, // BackpackBuilder
		53878057, // LoadoutScript
		53878053, // BackpackResizer
		97188756, // ChatScript
	];

	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);

	$access = $settings['asset']['ACCESSKEY'];

	

	include $_SERVER["DOCUMENT_ROOT"] . "/core/asset.php";

	$asset = Asset::FromID($id);
	if($asset != null) {
		$filename = $_SERVER['DOCUMENT_ROOT']."/../assets/".$asset->GetMD5HashCurrent();
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