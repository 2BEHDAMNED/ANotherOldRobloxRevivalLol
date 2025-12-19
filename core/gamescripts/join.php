<?php
	ob_start();
?>
{
        "ClientPort": 0,
        "MachineAddress": "127.0.0.1",
        "ServerPort": 53640,
        "PingUrl": "",
        "PingInterval": 120,
        "UserName": "PenisCore<?= rand(1, 512) ?>",
        "SeleniumTestMode": false,
        "UserId": <?= rand(1, 512) ?>,
        "SuperSafeChat": false,
        "CharacterAppearance": "http://arl.lambda.cam/v1.1/avatar-fetch/?userId=1",
        "ClientTicket": "asdklasfkdhlfaoiuyaiodjasjfgksajhlsdhsajdaksjhdk",
        "GameId": 2,
        "PlaceId": 2,
        "MeasurementUrl": "",
        "WaitingForCharacterGuid": "16be1dd8-5462-4ca5-a997-0725d997708b",
        "BaseUrl": "http://arl.lambda.cam/",
        "ChatStyle": "ClassicAndBubble",
        "VendorId": 0,
        "ScreenShotInfo": "",
        "VideoInfo": "",
        "CreatorId": 1,
        "CreatorTypeEnum": "User",
        "MembershipType": "None",
        "AccountAge": 256,
        "CookieStoreEnabled": false,
        "IsRobloxPlace": false,
        "GenerateTeleportJoin": false,
        "IsUnknownOrUnder13": false,
        "SessionId": "",
        "DataCenterId": 0,
        "UniverseId": 0,
        "BrowserTrackerId": 0,
        "UsePortraitMode": false,
        "FollowUserId": 0,
        "characterAppearanceId": 1,
        "GameChatType":"AllUsers"
}<?php
	function get_signature($script)
	{
		$signature = "";
		openssl_sign($script, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
		return base64_encode($signature);
	}    
	header("Content-Type: text/plain");

	$script = "\r\n" . ob_get_clean();
	$script = str_replace("arl.lambda.cam",$_SERVER['SERVER_NAME'], $script);
	$signature = get_signature($script);

	echo "--rbxsig%". $signature . "%" . $script;
?>