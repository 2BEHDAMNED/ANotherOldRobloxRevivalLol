<?php ob_start(); ?>
local placeId, port, maxPlayers, serverId = ...

loadfile('http://arl.lambda.cam/game/maingameserver.ashx')(placeId, port, 0, '8169b38d-abbc-480f-8971-14d8fd560aad', 'http://arl.lambda.cam', 5, maxPlayers, serverId)
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

    echo "%". $signature . "%" . $script;
?>