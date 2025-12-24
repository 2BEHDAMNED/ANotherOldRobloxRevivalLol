<?php
	$domain = $_SERVER['SERVER_NAME'];
	header("Content-Type: text/plain");
?>

local placeId, port, maxPlayers, serverId, access, sleeptime, throttleEnabled = ...

if port==nil then
	port = 53640
end

if sleeptime==nil then
	sleeptime = 15
end
if throttleEnabled==nil then
	throttleEnabled = true
end

workspace:SetPhysicsThrottleEnabled(throttleEnabled)

-- establish this peer as the Server
local ns = game:GetService("NetworkServer")

game:GetService("Players"):SetAbuseReportUrl("http://<?= $domain ?>/AbuseReport/InGameChatHandler.ashx")

-- utility
function waitForChild(parent, childName)
	while true do
		local child = parent:findFirstChild(childName)
		if child then
			return child
		end
		parent.ChildAdded:wait()
	end
end

-- returns the player object that killed this humanoid
-- returns nil if the killer is no longer in the game
function getKillerOfHumanoidIfStillInGame(humanoid)

	-- check for kill tag on humanoid - may be more than one - todo: deal with this
	local tag = humanoid:findFirstChild("creator")

	-- find player with name on tag
	if tag then
		local killer = tag.Value
		if killer.Parent then -- killer still in game
			return killer
		end
	end

	return nil
end

-- send kill and death stats when a player dies
function onDied(victim, humanoid)
	local killer = getKillerOfHumanoidIfStillInGame(humanoid)

	local victorId = 0
	if killer then
		victorId = killer.userId
		print("STAT: kill by " .. victorId .. " of " .. victim.userId)
		game:httpGet("http://<?= $domain ?>/Game/Statistics.ashx?TypeID=15&UserID=" .. victorId .. "&AssociatedUserID=" .. victim.userId .. "&AssociatedPlaceID=" .. placeId .. "&code=3544bd46-a09e-4e9f-9f4a-8cb6821ad356")
	else	
		game:httpGet("http://<?= $domain ?>/Game/Statistics.ashx?TypeID=16&UserID=" .. victim.userId .. "&AssociatedUserID=" .. victorId .. "&AssociatedPlaceID=" .. placeId .. "&code=3544bd46-a09e-4e9f-9f4a-8cb6821ad356")
	end
	print("STAT: death of " .. victim.userId .. " by " .. victorId)
	
end

-- listen for the death of a Player
function createDeathMonitor(player)
	-- we don't need to clean up old monitors or connections since the Character will be destroyed soon
	if player.Character then
		local humanoid = waitForChild(player.Character, "Humanoid")
		humanoid.Died:connect(
			function ()
				onDied(player, humanoid)
			end
		)
	end
end

-- listen to all Players' Characters
game:service("Players").ChildAdded:connect(function (player)
	createDeathMonitor(player)
	player.Changed:connect(
		function (property)
			if property=="Character" then
				createDeathMonitor(player)
			end
		end
	)
end)

-- This code might move to C++
function characterRessurection(player)
	if player.Character then
		local humanoid = player.Character.Humanoid
		humanoid.Died:connect(function() 
			wait(5)
			player:LoadCharacter()
		end)
		wait(0.5)
		if player.Character:FindFirstChild("Clothing") and not player.Character:FindFirstChild("Shirt Graphic") then
			for _, v in pairs(player.Character:children()) do
				if v.className == "Part" then
					if v.Name == "Torso" then
						for i, j in pairs(v:children()) do
							if j.className == "Decal" then
								j:Remove()
							end
						end
					end
				end
			end
		end
	end
end

ns.IncommingConnection:connect(function(peer, repl)
	repl.Name = "GenericNameFuckYou" -- prevent ip grabs
end)

game:GetService("Players").PlayerAdded:connect(function(player)
	-- its embarassing that i even need to implement this...
	if game:GetService("Players").MaxPlayers < game:GetService("Players").NumPlayers then
		print("Server too full let me go!")
		for _, v in pairs(game:GetService("NetworkServer"):children()) do
			if not v:GetPlayer() or (v:GetPlayer() and v:GetPlayer().userId == player.userId) then
				v:CloseConnection()
			end
		end
	else
		print("Player " .. player.userId .. " " .. player.Name .. " added")
		characterRessurection(player)
		player.Changed:connect(function(name)
			if name=="Character" then
				characterRessurection(player)
			end
		end)
	end

	if access and placeId and player and player.userId then
		game:httpGet("http://<?= $domain ?>/Game/ClientPresence.ashx?action=connect" .. access .. "&PlaceID=" .. placeId .. "&UserID=" .. player.userId .. "&serverId="..serverId)
	end
end)

game:GetService("Players").PlayerRemoving:connect(function(player)
print("Player " .. player.userId .. " " .. player.Name .. " leaving")

	local closingTimeRaw = #game:GetService("Players"):GetPlayers() == 0
	local closingTime = "false"
	if closingTimeRaw then
		closingTime = "true"
	end

	if access and placeId and player and player.userId then
		game:httpGet("http://<?= $domain ?>/Game/ClientPresence.ashx?action=disconnect" .. access .. "&PlaceID=" .. placeId .. "&UserID=" .. player.userId .. "&serverId="..serverId .. "&shouldClose="..closingTime)
	end
end)

if placeId~=nil and placeId~=0 then
	-- load the game
	game:Load("http://<?= $domain ?>/asset/?id=".. placeId .. access)
end
	
-- Now start the connection
ns:start(port, sleeptime) 

game:GetService("RunService"):Run()