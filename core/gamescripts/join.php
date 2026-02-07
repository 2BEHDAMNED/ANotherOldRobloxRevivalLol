<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	function getSessionDetails(string $sessionID): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_players` WHERE `session_id` = ?");
		$stmt_getsessiondetails->bind_param("s", $sessionID);
		$stmt_getsessiondetails->execute();

		$result_getsessiondetails = $stmt_getsessiondetails->get_result();

		if($result_getsessiondetails->num_rows != 0) {
			return $result_getsessiondetails->fetch_assoc();
		}

		return null;
	}

	function getServerDetails(string $serverID): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_servers` WHERE `server_id` = ?");
		$stmt_getsessiondetails->bind_param("s", $serverID);
		$stmt_getsessiondetails->execute();

		$result_getsessiondetails = $stmt_getsessiondetails->get_result();

		if($result_getsessiondetails->num_rows != 0) {
			return $result_getsessiondetails->fetch_assoc();
		}

		return null;
	}

	ob_start();
?>
<?php if(!isset($_GET['serverToken']) && !isset($_GET['sessionToken'])): ?>
{
	"ClientPort":0,
	"MachineAddress":"localhost",
	"ServerPort":53640,
	"PingUrl":"",
	"PingInterval":120,
	"UserName":"Player",
	"SeleniumTestMode":true,
	"UserId":0,
	"SuperSafeChat":true,
	"CharacterAppearance":"http://arl.lambda.cam/Asset/CharacterFetch.ashx?userId=1&placeId=0",
	"ClientTicket":"",
	"GameId":"00000000-0000-0000-0000-000000000000",
	"PlaceId":0,
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

	die("--rbxsig%". $signature . "%" . $script);
?>
<?php else: ?>
{
	"ClientPort":0,
	"MachineAddress":"g3d.gurdit.com",
	"ServerPort":{serverport},
	"PingUrl":"",
	"PingInterval":120,
	"UserName":"{playername}",
	"SeleniumTestMode":false,
	"UserId": {playerid},
	"SuperSafeChat":false,
	"CharacterAppearance":"http://arl.lambda.cam/Asset/CharacterFetch.ashx?userId={playerid}",
	"ClientTicket":"{sessionid}",
	"GameId":"00000000-0000-0000-0000-000000000000",
	"PlaceId":{placeid},
	"MeasurementUrl":"",
	"WaitingForCharacterGuid":
	"16be1dd8-5462-4ca5-a997-0725d997708b",
	"BaseUrl":"http://arl.lambda.cam/",
	"ChatStyle":"ClassicAndBubble",
	"VendorId":0,
	"ScreenShotInfo":"",
	"VideoInfo":"",
	"CreatorId":{placecreator},
	"CreatorTypeEnum":"User",
	"MembershipType":"None",
	"AccountAge":256,
	"CookieStoreFirstTimePlayKey":"rbx_evt_ftp",
	"CookieStoreFiveMinutePlayKey":"rbx_evt_fmp",
	"CookieStoreEnabled":true,
	"IsRobloxPlace":true,
	"GenerateTeleportJoin":false,
	"IsUnknownOrUnder13":false,
	"SessionId":"{sessionid}",
	"DataCenterId":0,
	"UniverseId":0,
	"BrowserTrackerId":0,
	"UsePortraitMode":false,
	"FollowUserId":0,
	"characterAppearanceId":{playerid}
}
<?php
	function get_signature($script)
	{
		$signature = "";
		openssl_sign($script, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
		return base64_encode($signature);
	}    
	header("Content-Type: text/plain");

	$serverToken = $_GET['serverToken'];
	$sessionToken = $_GET['sessionToken'];

	$serverDetails = getServerDetails($serverToken);
	$sessionDetails = getSessionDetails($sessionToken);

	if($serverDetails != null && $sessionDetails != null) {

		$player = User::FromID(intval($sessionDetails['session_playerid']));
		$place = Place::FromID(intval($serverDetails['server_placeid']));
		
		if($player != null && !$player->IsBanned() && $place != null) {

			if(UserUtils::RetrieveUser() == null) {
				UserUtils::SetCookies($player->security_key);
			}

			$playerid = $player->id;
			$playername = $player->name;
			$serverport = $serverDetails['server_port'];
			$placeid = $place->id;
			$placecreator = $place->creator->id;
			

			$script = "\r\n" . ob_get_clean();
			$script = str_replace("arl.lambda.cam",$_SERVER['SERVER_NAME'], $script);
			$script = str_replace("{playerid}",$playerid, $script);
			$script = str_replace("{playername}",$playername, $script);
			$script = str_replace("{serverport}",$serverport, $script);
			$script = str_replace("{placeid}",$placeid, $script);
			$script = str_replace("{placecreator}",$placecreator, $script);
			$signature = get_signature($script);

			die("--rbxsig%". $signature . "%" . $script);
		}
		
	}
?>
<?php endif ?>
