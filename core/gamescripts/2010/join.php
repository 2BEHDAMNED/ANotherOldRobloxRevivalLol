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
?>-- MultiplayerSharedScript.lua inserted here ------ Prepended to GroupBuild.lua and Join.lua --

pcall(function() settings().Rendering.EnableFRM = true end)

-- arguments ---------------------------------------
local threadSleepTime = ...

if threadSleepTime==nil then
	threadSleepTime = 15
end

local test = {test}

print("! Joining game '' place 0 at localhost")

game:GetService("ChangeHistoryService"):SetEnabled(false)
game:GetService("ContentProvider"):SetThreadPool(16)
game:GetService("InsertService"):SetBaseCategoryUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?nsets=10&type=base")
game:GetService("InsertService"):SetUserCategoryUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d")
game:GetService("InsertService"):SetCollectionUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?sid=%d")
game:GetService("InsertService"):SetAssetUrl("http://arl.lambda.cam/Asset/?id=%d")
game:GetService("InsertService"):SetAssetVersionUrl("http://arl.lambda.cam/Asset/?assetversionid=%d")
game:GetService("InsertService"):SetAdvancedResults(true)

-- Bubble chat.  This is all-encapsulated to allow us to turn it off with a config setting
pcall(function() game:GetService("Players"):SetChatStyle(Enum.ChatStyle.ClassicAndBubble) end)

pcall( function()
	if settings().Network.MtuOverride == 0 then
	  settings().Network.MtuOverride = 1400
	end
end)


-- globals -----------------------------------------

client = game:GetService("NetworkClient")
visit = game:GetService("Visit")

-- functions ---------------------------------------
function setMessage(message)
	-- todo: animated "..."
	game:SetMessage(message)
end

function showErrorWindow(message)
	game:SetMessage(message)
end

function reportError(err)
	print("***ERROR*** " .. err)
	--if not test then visit:SetUploadUrl("") end
	client:Disconnect()
	wait(4)
	showErrorWindow("Error: " .. err)
end

-- called when the client connection closes
function onDisconnection(peer, lostConnection)
	if lostConnection then
		showErrorWindow("You have lost the connection to the game")
	else
		showErrorWindow("This game has shut down")
	end
end

function requestCharacter(replicator)
	
	-- prepare code for when the Character appears
	local connection
	connection = player.Changed:connect(function (property)
		if property=="Character" then
			game:ClearMessage()
			
			connection:disconnect()
		end
	end)
	
	setMessage("Requesting character")

	local success, err = pcall(function()	
		replicator:RequestCharacter()
		setMessage("Waiting for character")
	end)
	if not success then
		reportError(err)
		return
	end
end

-- called when the client connection is established
function onConnectionAccepted(url, replicator)

	local waitingForMarker = true
	
	local success, err = pcall(function()	
		if not test then 
		    visit:SetPing("", 300) 
		end
		
		game:SetMessageBrickCount()
		replicator.Disconnection:connect(onDisconnection)
		
		-- Wait for a marker to return before creating the Player
		local marker = replicator:SendMarker()
		
		marker.Received:connect(function()
			waitingForMarker = false
			requestCharacter(replicator)
		end)
	end)
	
	if not success then
		reportError(err)
		return
	end
	
	-- TODO: report marker progress
	
	while waitingForMarker do
		workspace:ZoomToExtents()
		wait(0.5)
	end
end

-- called when the client connection fails
function onConnectionFailed(_, error)
	showErrorWindow("Failed to connect to the Game. (ID=" .. error .. ")")
end

-- called when the client connection is rejected
function onConnectionRejected()
	connectionFailed:disconnect()
	showErrorWindow("This game is not available. Please try another")
end

function onPlayerIdled(time)
	if time > 20*60 then
		showErrorWindow(string.format("You were disconnected for being idle %d minutes", time/60))
		client:Disconnect()	
	end
end


-- main ------------------------------------------------------------

pcall(function() settings().Diagnostics:LegacyScriptMode() end)
local success, err = pcall(function()	

	game:SetRemoteBuildMode(true)
	setMessage("Creating Player")
	player = game:GetService("Players"):CreateLocalPlayer({playerID})
	player:SetSuperSafeChat(false)
	player.Idled:connect(onPlayerIdled)
	
	pcall(function() player.Name = [========[{playerName}]========] end)
	player.CharacterAppearance = "{playerAppearance}"	
	--if not test then visit:SetUploadUrl("")end

	setMessage("Connecting to Server")
	client.ConnectionAccepted:connect(onConnectionAccepted)
	client.ConnectionRejected:connect(onConnectionRejected)
	connectionFailed = client.ConnectionFailed:connect(onConnectionFailed)
	client.Ticket = ""
	client:Connect("{server}", {serverPort}, 0, threadSleepTime)
end)

if not success then
	reportError(err)
end
<?php
	function get_signature($script)
	{
		$signature = "";
		openssl_sign($script, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
		return base64_encode($signature);
	}    
	header("Content-Type: text/plain");

	$testMode = true;
	$playerID = 0;
	$playerName = "Player";
	$playerAppearance = "";
	$playerAge = 0;
	$server = "localhost";
	$serverPort = 53640;

	if(
		isset($_GET['serverToken']) && 
		isset($_GET['sessionToken']) && 
		isset($_GET['server'])
	) {
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

				$testMode = false;
				$playerID = $player->id;
				$playerName = $player->name;
				$playerAppearance = "http://arl.lambda.cam/Asset/CharacterFetch.ashx?userId=".$playerID;
				$playerAge = $player->GetAccountAge();
				$server = $_GET['server'] ?? "86.20.118.158";
				$serverPort = $serverDetails['server_port'];
			}
		}
	}

	$script = "\r\n" . ob_get_clean();

	$test  = $testMode ? "true" : "false";

	$script = str_replace("arl.lambda.cam",$_SERVER['SERVER_NAME'], $script);
	$script = str_replace("{test}"            , $test            , $script);
	$script = str_replace("{playerID}"        , $playerID        , $script);
	$script = str_replace("{playerName}"      , $playerName      , $script);
	$script = str_replace("{playerAge}"       , $playerAge       , $script);
	$script = str_replace("{playerAppearance}", $playerAppearance, $script);
	$script = str_replace("{server}"          , $server          , $script);
	$script = str_replace("{serverPort}"      , $serverPort      , $script);
	$signature = get_signature($script);

	die("%". $signature . "%" . $script);
?>