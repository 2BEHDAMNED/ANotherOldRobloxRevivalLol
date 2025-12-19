<?php ob_start(); ?>
loadfile('http://arl.lambda.cam/game/gameserver.ashx')(2,53640,2, 20, "accesssss", false, 10, nil, nil, "http://arl.lambda.cam", 12, 1, nil, nil, nil, nil, nil, 1, 12, 1, "accesssssssskeeyyy", nil, nil)

--[[
placeId
port
gameId
sleeptime
access
deprecated
timeout
machineAddress
gsmInterval
baseUrl
maxPlayers
maxGameInstances
injectScriptAssetID
apiKey
libraryRegistrationScriptAssetID
deprecated_pingTimesReportInterval
gameCod
universeId
preferredPlayerCapacity
matchmakingContextId
placeVisitAccessKey
assetGameSubdomain
protocol = ...
]]

<?php
	function get_signature($script)
	{
		$signature = "";
		openssl_sign($script, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
		return base64_encode($signature);
	}

	header("Content-Type: text/plain");

	$script = "\r\n" . ob_get_clean();
	$signature = get_signature($script);

	echo "--rbxsig%". $signature . "%" . $script;
?>