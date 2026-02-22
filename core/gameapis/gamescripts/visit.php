<?php ob_start(); ?>
-- Prepended to Edit.lua and Visit.lua and Studio.lua and PlaySolo.lua--

function ifSeleniumThenSetCookie(key, value)
	
end

ifSeleniumThenSetCookie("SeleniumTest1", "Inside the visit lua script")

if true then
	pcall(function() game:SetPlaceID(0) end)
else
	if 0>0 then
		pcall(function() game:SetPlaceID(0) end)
	end
end

visit = game:GetService("Visit")

local message = Instance.new("Message")
message.Parent = workspace
message.archivable = false

game:GetService("ScriptInformationProvider"):SetAssetUrl("http://arl.lambda.cam/Asset/")
game:GetService("ContentProvider"):SetThreadPool(16)
pcall(function() game:GetService("InsertService"):SetFreeModelUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?type=fm&q=%s&pg=%d&rs=%d") end) -- Used for free model search (insert tool)
pcall(function() game:GetService("InsertService"):SetFreeDecalUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?type=fd&q=%s&pg=%d&rs=%d") end) -- Used for free decal search (insert tool)

ifSeleniumThenSetCookie("SeleniumTest2", "Set URL service")

settings().Diagnostics:LegacyScriptMode()

game:GetService("InsertService"):SetBaseSetsUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?nsets=10&type=base")
game:GetService("InsertService"):SetUserSetsUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d")
game:GetService("InsertService"):SetCollectionUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?sid=%d")
game:GetService("InsertService"):SetAssetUrl("http://arl.lambda.cam/Asset/?id=%d")
game:GetService("InsertService"):SetAssetVersionUrl("http://arl.lambda.cam/Asset/?assetversionid=%d")

pcall(function() game:GetService("SocialService"):SetFriendUrl("http://arl.lambda.cam/Game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetBestFriendUrl("http://arl.lambda.cam/Game/LuaWebService/HandleSocialRequest.ashx?method=IsBestFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupUrl("http://arl.lambda.cam/Game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=%d&groupid=%d") end)
pcall(function() game:SetCreatorID(0, Enum.CreatorType.User) end)

ifSeleniumThenSetCookie("SeleniumTest3", "Set creator ID")

pcall(function() game:SetScreenshotInfo("") end)
pcall(function() game:SetVideoInfo("") end)

function registerPlay(key)
	if true and game:GetService("CookiesService"):GetCookieValue(key) == "" then
		game:GetService("CookiesService"):SetCookieValue(key, "{ \"userId\" : 0, \"placeId\" : 0, \"os\" : \"" .. settings().Diagnostics.OsPlatform .. "\"}")
	end
end

pcall(function()
	registerPlay("rbx_evt_ftp")
	delay(60*5, function() registerPlay("rbx_evt_fmp") end)
end)

ifSeleniumThenSetCookie("SeleniumTest4", "Exiting SingleplayerSharedScript")-- SingleplayerSharedScript.lua inserted here --

pcall(function() settings().Rendering.EnableFRM = true end)
pcall(function() settings()["Task Scheduler"].PriorityMethod = Enum.PriorityMethod.AccumulatedError end)

game:GetService("ChangeHistoryService"):SetEnabled(false)
pcall(function() game:GetService("Players"):SetBuildUserPermissionsUrl("http://arl.lambda.cam//Game/BuildActionPermissionCheck.ashx?assetId=0&userId=%d&isSolo=true") end)

workspace:SetPhysicsThrottleEnabled(true)

local addedBuildTools = false
local screenGui = game:GetService("CoreGui"):FindFirstChild("RobloxGui")

function doVisit()
	message.Text = "Loading Game"
	pcall(function() visit:SetUploadUrl("") end)

	game:GetService("NetworkServer"):Start(2048)

	message.Text = "Running"
	game:GetService("RunService"):Run()

	message.Text = "Creating Player"

	local client = game:GetService("NetworkClient")

	client.Ticket = ""	
	
	playerConnectSuccess, player = pcall(function() return client:PlayerConnect(0, "localhost", 2048) end)
	if not playerConnectSuccess then
		warn("FAILED TO CONNECT!")
		return false, "Failed to connect"
	end

	player:SetSuperSafeChat(false)
	pcall(function() player:SetUnder13(false) end)
	pcall(function() player:SetMembershipType(Enum.MembershipType.None) end)
	pcall(function() player:SetAccountAge(0) end)

	message.Text = "Setting GUI"
	player:SetSuperSafeChat(true)
	pcall(function() player:SetMembershipType(Enum.MembershipType.None) end)
	pcall(function() player:SetAccountAge(0) end)
end

success, err = pcall(doVisit)

if not addedBuildTools and success then
	local playerName = Instance.new("StringValue")
	playerName.Name = "PlayerName"
	playerName.Value = player.Name
	playerName.RobloxLocked = true
	playerName.Parent = screenGui
				
	pcall(function() game:GetService("ScriptContext"):AddCoreScript(59431535,screenGui,"BuildToolsScript") end)
	addedBuildTools = true
end

if success then
	message.Parent = nil
else
	print(err)
	wait(5)
	message.Text = "Error on visit: " .. err
end
<?php
	function get_signature($script)
	{
		$signature = "";
		openssl_sign($script, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
		return base64_encode($signature);
	}    
	header("Content-Type: text/plain");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();
	$userid = 1;
	if($user != null) {
		$userid = $user->id;
	}

	$script = "\r\n" . ob_get_clean();
	$script = str_replace("{userid}", strval($userid), $script);
	$script = str_replace("arl.lambda.cam",$_SERVER['SERVER_NAME'], $script);
	$signature = get_signature($script);

	echo "--rbxsig%". $signature . "%" . $script;
?>