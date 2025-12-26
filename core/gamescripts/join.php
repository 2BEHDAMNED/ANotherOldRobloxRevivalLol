<?php
	ob_start();
?>
{
	"ClientPort":0,
	"MachineAddress":"localhost",
	"ServerPort":53640,
	"PingUrl":"",
	"PingInterval":120,
	"UserName":"PenisCore<?= rand(1, 256) ?>",
	"SeleniumTestMode":true,
	"UserId":<?= rand(1, 256) ?>,
	"SuperSafeChat":true,
	"CharacterAppearance":"http://arl.lambda.cam/Asset/CharacterFetch.ashx?userId=1&placeId=0",
	"ClientTicket":"",
	"GameId":"00000000-0000-0000-0000-000000000000",
	"PlaceId":11,
	"MeasurementUrl":"",
	"WaitingForCharacterGuid":
	"16be1dd8-5462-4ca5-a997-0725d997708b",
	"BaseUrl":"http://arl.lambda.cam/",
	"ChatStyle":"ClassicAndBubble",
	"VendorId":0,
	"ScreenShotInfo":"",
	"VideoInfo":"",
	"CreatorId":0,
	"CreatorTypeEnum":"User",
	"MembershipType":"None",
	"AccountAge":0,
	"CookieStoreFirstTimePlayKey":"rbx_evt_ftp",
	"CookieStoreFiveMinutePlayKey":"rbx_evt_fmp",
	"CookieStoreEnabled":true,
	"IsRobloxPlace":true,
	"GenerateTeleportJoin":false,
	"IsUnknownOrUnder13":false,
	"SessionId":"",
	"DataCenterId":0,
	"UniverseId":0,
	"BrowserTrackerId":0,
	"UsePortraitMode":false,
	"FollowUserId":0,
	"characterAppearanceId":1
}
<?php
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