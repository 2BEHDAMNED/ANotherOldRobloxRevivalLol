<?php ob_start(); ?>
local placeId, port, url, access, jobID, cloudEditEnabled = ...

pcall(function() settings().Network.UseInstancePacketCache = true end)
pcall(function() settings().Network.UsePhysicsPacketCache = true end)
pcall(function() settings()["Task Scheduler"].PriorityMethod = Enum.PriorityMethod.AccumulatedError end)
settings().Network.PhysicsSend = Enum.PhysicsSendMethod.TopNErrors
settings().Network.ExperimentalPhysicsEnabled = true
settings().Network.WaitingForCharacterLogRate = 100
pcall(function() settings().Diagnostics:LegacyScriptMode() end)

local scriptContext = game:GetService('ScriptContext')
scriptContext.ScriptsDisabled = true
game:SetPlaceID(placeId, true)
game:GetService("ChangeHistoryService"):SetEnabled(false)
local ns = game:GetService("NetworkServer")

ns:ConfigureAsCloudEditServer()

if url~=nil then
	pcall(function() game:GetService("Players"):SetAbuseReportUrl(url .. "/AbuseReport/InGameChatHandler.ashx") end)
	pcall(function() game:GetService("ScriptInformationProvider"):SetAssetUrl(url .. "/Asset/") end)
	pcall(function() game:GetService("ContentProvider"):SetBaseUrl(url .. "") end)
	pcall(function() game:GetService("Players"):SetChatFilterUrl(url .. "/Game/ChatFilter.ashx") end)
	game:GetService("BadgeService"):SetPlaceId(placeId)
	game:GetService("BadgeService"):SetHasBadgeUrl(url .. "/game/Badge/HasBadge.ashx?UserID=%d&BadgeID=%d")
	game:GetService("BadgeService"):SetIsBadgeLegalUrl("")
	game:GetService("BadgeService"):SetAwardBadgeUrl(url .. "assets/award-badge?userId=%d&badgeId=%d&placeId=%d")
	game:GetService("InsertService"):SetBaseSetsUrl(url .. "/game/Tools/InsertAsset.ashx?nsets=10&type=base")
	game:GetService("InsertService"):SetUserSetsUrl(url .. "/game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d")
	game:GetService("InsertService"):SetCollectionUrl(url .. "/game/Tools/InsertAsset.ashx?sid=%d")
	game:GetService("InsertService"):SetAssetUrl(url .. "/Asset/?id=%d")
	game:GetService("InsertService"):SetAssetVersionUrl(url .. "/Asset/?assetversionid=%d")
	pcall(function() game:GetService("SocialService"):SetFriendUrl(url .. "/game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerid=%d&userid=%d") end)
	pcall(function() game:GetService("SocialService"):SetBestFriendUrl(url .. "/game/LuaWebService/HandleSocialRequest.ashx?method=IsBestFriendsWith&playerid=%d&userid=%d") end)
	pcall(function() game:GetService("SocialService"):SetGroupUrl(url .. "/game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=%d&groupid=%d") end)
	pcall(function() game:GetService("SocialService"):SetGroupRankUrl(url .. "/game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRank&playerid=%d&groupid=%d") end)
	pcall(function() game:GetService("SocialService"):SetGroupRoleUrl(url .. "/game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRole&playerid=%d&groupid=%d") end)
	pcall(function() game:GetService("GamePassService"):SetPlayerHasPassUrl(url .. "/game/GamePass/GamePassHandler.ashx?Action=HasPass&UserID=%d&PassID=%d") end)
	pcall(function() game:GetService("MarketplaceService"):SetProductInfoUrl(url .. "/marketplace/productinfo?assetId=%d") end)
	pcall(function() game:GetService("MarketplaceService"):SetDevProductInfoUrl(url .. "/marketplace/productDetails?productId=%d") end)
	pcall(function() game:GetService("MarketplaceService"):SetPlayerOwnsAssetUrl(url .. "/ownership/hasasset?userId=%d&assetId=%d") end)
	--pcall(function() game:SetPlaceVersion(1) end)
	--pcall(function() game:SetVIPServerOwnerId(1) end)
	--pcall(function() game:SetCreatorID(1, Enum.CreatorType.User) end)
	pcall(function() game:GetService("NetworkServer"):SetIsPlayerAuthenticationRequired(true) end)
	game:GetService("Players").MaxPlayersInternal = 12
end
settings().Diagnostics.LuaRamLimit = 0
if placeId~=nil and url~=nil then
	-- yield so that file load happens in the heartbeat thread
	wait()
	
	-- load the game
	game:Load(url .. "/asset/?id=" .. placeId .. "&access=" .. access .. "&t=<?= time() ?>")
end
ns:Start(port)
scriptContext:SetTimeout(0)
scriptContext.ScriptsDisabled = false
game:GetService("RunService"):Run()
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