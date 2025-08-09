<?php
	$id = intval($_GET["id"]);

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
	];

	$filename = $_SERVER['DOCUMENT_ROOT']."/../assets/".$id;
	if(file_exists($filename)) {
		$handle = fopen($filename, "r"); 
		$contents = fread($handle, filesize($filename)); 
		fclose($handle);
		header("Content-Type: ".checkMimeType($contents));
		$contents = str_replace("www.roblox.com", "www.lambda.cam",$contents);
		$contents = str_replace("api.roblox.com", "www.lambda.cam",$contents);
		if(in_array($id, $sign_ids)) {
			$contents = "%$id%\r\n" . $contents;
			openssl_sign($contents, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
			$signature = base64_encode($signature);
			echo "%$signature%";
		}
		
		echo $contents;
	}
?>