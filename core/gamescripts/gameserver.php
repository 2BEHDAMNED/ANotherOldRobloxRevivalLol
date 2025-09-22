<?php ob_start(); ?>
local sleeptime, port = ...

game:GetService("RunService"):Run()

pcall(function() settings().Network.UseInstancePacketCache = true end)
pcall(function() settings().Network.UsePhysicsPacketCache = true end)
pcall(function() settings()["Task Scheduler"].PriorityMethod = Enum.PriorityMethod.AccumulatedError end)

settings().Network.PhysicsSend = Enum.PhysicsSendMethod.ErrorComputation2
settings().Network.ExperimentalPhysicsEnabled = true
settings().Network.WaitingForCharacterLogRate = 100
pcall(function() settings().Diagnostics:LegacyScriptMode() end)

local scriptContext = game:GetService('ScriptContext')
scriptContext.ScriptsDisabled = true

game:SetPlaceID(0, false)
game:GetService("ChangeHistoryService"):SetEnabled(false)

local ns = game:GetService("NetworkServer")

pcall(function() game:GetService("NetworkServer"):SetIsPlayerAuthenticationRequired(false) end)
settings().Diagnostics.LuaRamLimit = 0

game:GetService("Players").PlayerAdded:connect(function(player)    print("Player " .. player.userId .. " added")   end)
game:GetService("Players").PlayerRemoving:connect(function(player) print("Player " .. player.userId .. " leaving") end)

ns:Start(port, sleeptime) 

scriptContext.ScriptsDisabled = false
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

    echo "%". $signature . "%" . $script;
?>