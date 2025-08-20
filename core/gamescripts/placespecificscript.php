<?php ob_start(); ?>
game:GetService("Players"):SetSaveDataUrl("http://arl.lambda.cam/Persistence/SetBlob.ashx?placeid={id}&userid=%d")
game:GetService("Players"):SetLoadDataUrl("http://arl.lambda.cam/Persistence/GetBlobUrl.ashx?placeid={id}&userid=%d")

game:GetService("Players").PlayerAdded:connectFirst(function(player)
    player:LoadData()	
end)

game:GetService("Players").PlayerRemoving:connectLast(function(player)
    player:SaveData()
end)
<?php
	function get_signature($script) {
        $signature = "";
        openssl_sign($script, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
        return base64_encode($signature);
    }

    header("Content-Type: text/plain");

    $script = "\r\n" . ob_get_clean();
	$script = str_replace("{id}", $_GET['PlaceId'], $script);
    $signature = get_signature($script);

    echo "%". $signature . "%" . $script;
?>