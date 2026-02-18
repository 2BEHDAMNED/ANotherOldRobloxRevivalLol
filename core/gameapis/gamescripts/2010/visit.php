<?php ob_start(); ?>
visit = game:GetService("Visit")

local message = Instance.new("Message")
message.Parent = workspace

game:GetService("ScriptInformationProvider"):SetAssetUrl("http://arl.lambda.cam/Asset/")
game:GetService("ContentProvider"):SetThreadPool(16)

settings().Diagnostics:LegacyScriptMode()

game:GetService("InsertService"):SetBaseCategoryUrl("http://arl.lambda.cam//Game/Tools/InsertAsset.ashx?nsets=10&type=base")
game:GetService("InsertService"):SetUserCategoryUrl("http://arl.lambda.cam//Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d")
game:GetService("InsertService"):SetCollectionUrl("http://arl.lambda.cam//Game/Tools/InsertAsset.ashx?sid=%d")
game:GetService("InsertService"):SetAssetUrl("http://arl.lambda.cam//Asset/?id=%d")
game:GetService("InsertService"):SetAssetVersionUrl("http://arl.lambda.cam//Asset/?assetversionid=%d")
game:GetService("InsertService"):SetAdvancedResults(true)
-- SingleplayerSharedScript.lua inserted here --
game:GetService("ChangeHistoryService"):SetEnabled(false)
pcall(function() game:GetService("Players"):SetBuildToolsUrl("http://arl.lambda.cam//Game/GetUserBuildToolSet.ashx?assetId=0&userId=%d&isSolo=true") end)
pcall(function() game:GetService("Players"):SetBuildUserPermissionsUrl("http://arl.lambda.cam//Game/BuildActionPermissionCheck.ashx?assetId=0&userId=%d&isSolo=true") end)

workspace:SetPhysicsThrottleEnabled(true)

-- This code might move to C++
function characterRessurection(player)
	if player.Character then
		local humanoid = player.Character.Humanoid
		humanoid.Died:connect(function() wait(5) player:LoadCharacter() end)
	end
end
game:GetService("Players").PlayerAdded:connect(function(player)
	characterRessurection(player)
	player.Changed:connect(function(name)
		if name=="Character" then
			characterRessurection(player)
		end
	end)
end)

function doVisit()
	message.Text = "Loading Game"
	pcall(function() visit:SetUploadUrl("") end)

	message.Text = "Running"
	game:GetService("RunService"):Run()

	message.Text = "Creating Player"
	player = game:GetService("Players"):CreateLocalPlayer(0)
	player.CharacterAppearance = "http://arl.lambda.cam/Asset/CharacterFetch.ashx?userId={userid}&placeId=0"
	player:LoadCharacter()

	message.Text = "Setting GUI"
	player:SetSuperSafeChat(false)
end

success, err = pcall(doVisit)

if success then
	message.Parent = nil
else
	print(err)
	pcall(function() visit:SetUploadUrl("") end)
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

	echo "%". $signature . "%" . $script;
?>