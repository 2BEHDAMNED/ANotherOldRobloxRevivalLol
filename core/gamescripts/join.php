<?php
	ob_start();
?>
{
	"ClientPort":0,
	"MachineAddress":"localhost",
	"ServerPort":"53640",
	"PingUrl":"",
	"PingInterval":20,
	"UserName":"PenisCore<?= rand(1, 256) ?>",
	"SeleniumTestMode":true,
	"UserId":<?= rand(1, 256) ?>,
	"SuperSafeChat":false,
	"CharacterAppearance":"http://arl.lambda.cam/v1.1/avatar-fetch/?placeId=&userId=<?= 10 ?>",
	"ClientTicket":"",
	"GameId":2,
	"PlaceId":2,
	"MeasurementUrl":"",
	"WaitingForCharacterGuid":"26eb3e21-aa80-475b-a777-b43c3ea5f7d2",
	"BaseUrl":"http://arl.lambda.cam",
	"ChatStyle":"ClassicAndBubble",
	"GameChatType":"AllUsers",
	"VendorId":0,
	"ScreenShotInfo":"",
	"VideoInfo":"",
	"CreatorId":1,
	"CreatorTypeEnum":"User",
	"MembershipType":"None",
	"AccountAge":3000000,
	"CookieStoreFirstTimePlayKey":"rbx_evt_ftp",
	"CookieStoreFiveMinutePlayKey":"rbx_evt_fmp",
	"CookieStoreEnabled":true,
	"IsRobloxPlace":true,
	"GenerateTeleportJoin":false,"IsUnknownOrUnder13":false,
	"SessionId":"39412c34-2f9b-436f-b19d-b8db90c2e186|00000000-0000-0000-0000-000000000000|0|190.23.103.228|8|2021-03-03T17:04:47+01:00|0|null|null",
	"DataCenterId":0,
	"UniverseId":3,
	"BrowserTrackerId":0,
	"UsePortraitMode":true,
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