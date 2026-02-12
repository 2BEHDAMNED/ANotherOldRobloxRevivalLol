<?php

	/**
	 * I dont fucking know bruh this is like cancer in a way
	 * 
	 * GOOD THING THIS IS FRIENDS ONLY OTHER WISE I WOULD BE COOKED. 
	 * I CANNOT BE ASKED TO DO SECURITY STUFF AS MUCH AS I WANT TO.
	 */

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/renderer.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";


	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	$rcc_settings = $settings['renderer'];

	$access = $settings['asset']['ACCESSKEY'];
	$rcc_ip = $rcc_settings['RCCGAMEIP'];
	$rcc_port = 64898;
	$rcc_teamcreate_port = 64888;
	$ARBITER_HOSTS = [
		"37.114.46.52:7000",
	];
	$ARBITER_BEARER_TOKEN = "427803B4BD7DE917C017D5B7D9DC49CDF9E2B8BF547D1E28FC5C965FA3B3D285";
	$ARBITER_RAM_THRESHOLD = 80;
	$fakeahserver = '86.20.118.158';

	header("Content-Type: application/json");

	function getRandomString(int $length = 11): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}

		return $randomString;
	}

	function httpGetJson(string $url, array $headers = [], int $timeout = 5): ?array {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$res = curl_exec($ch);
		$errno = curl_errno($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($errno || $res === false || $res === '') return null;
		$decoded = json_decode($res, true);
		if (json_last_error() !== JSON_ERROR_NONE) return null;
		return $decoded;
	}

	function httpPostJson(string $url, $data, array $headers = [], int $timeout = 5): ?array {
		$ch = curl_init();
		$payload = json_encode($data);
		$defaultHeaders = [
			'Content-Type: application/json',
			'Content-Length: '.strlen($payload)
		];
		$allHeaders = array_merge($defaultHeaders, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
		$res = curl_exec($ch);
		$errno = curl_errno($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($errno || $res === false || $res === '') return null;
		$decoded = json_decode($res, true);
		if (json_last_error() !== JSON_ERROR_NONE) return null;
		return $decoded;
	}

	function parseHealth(array $health): array {
		$out = ['ram' => null, 'status' => null];

		foreach ($health as $k => $v) {
			$lk = strtolower($k);

			if (strpos($lk, 'ram') !== false) {
				if (is_numeric($v)) {
					$out['ram'] = (int)$v;
				} elseif (is_string($v) && preg_match('/\d+/', $v, $m)) {
					$out['ram'] = (int)$m[0];
				}
			}

			if ($lk === 'status' && is_string($v)) {
				$out['status'] = strtolower($v);
			}
		}

		return $out;
	}

	function tryProvisionViaArbiter(int $placeId, array $arbiterHosts, string $bearerToken, int $ramThreshold, bool $teamcreate): ?array {
		foreach ($arbiterHosts as $host) {
			/*
			$healthUrl = rtrim($host, '/') . "/api/v1/health";
			if (strpos($healthUrl, 'http') !== 0) $healthUrl = "http://".$healthUrl;

			$health = httpGetJson($healthUrl, [], 3);
			if ($health !== null) {
				$parsed = parseHealth($health);
				$ram_ok = true;
				if ($parsed['ram'] !== null && $parsed['ram'] > $ramThreshold) {
					$ram_ok = false;
				}
				if ($parsed['status'] === 'stressed') $ram_ok = false;

				if (!$ram_ok) {
					continue;
				}
			}
			*/

			$gameserverUrl = rtrim($host, '/') . "/api/v1/gameserver";
			if (strpos($gameserverUrl, 'http') !== 0) $gameserverUrl = "http://".$gameserverUrl;

			$headers = [
				"Authorization: Bearer $bearerToken"
			];

			$resp = httpPostJson($gameserverUrl, ['placeId' => $placeId, 'TeamCreate' => $teamcreate], $headers, 6);

			if ($resp !== null) {
				$jobId = $resp['jobId'] ?? null;
				$fakePort = $resp['fakeahport'] ?? null;
				if ($fakePort == null) {
					$fakePort = $resp['port'] ?? null;
				}

				if ($jobId !== null && $fakePort !== null) {
					return [
						'host' => $host,
						'jobId' => strval($jobId),
						'port' => intval($fakePort),
						'raw' => $resp
					];
				}
			}
		}
		return null;
	}

	function getActiveServersCount(int $placeID, bool $teamcreate = false): bool {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_teamcreate = $teamcreate ? 1 : 0;

		$stmt_getactiveservers = $con->prepare("SELECT * FROM `active_servers` WHERE `server_placeid` = ? AND `server_playercount` != `server_maxcount` AND `server_teamcreate` = ?");
		$stmt_getactiveservers->bind_param("ii", $placeID, $stmt_teamcreate);
		$stmt_getactiveservers->execute();

		$result_getactiveservers = $stmt_getactiveservers->get_result();

		return $result_getactiveservers->num_rows;
	}

	function getAnActiveServer(int $placeID, bool $teamcreate = false): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_teamcreate = $teamcreate ? 1 : 0;

		$stmt_getactiveservers = $con->prepare("SELECT * FROM `active_servers` WHERE `server_placeid` = ? AND `server_playercount` != `server_maxcount` AND `server_teamcreate` = ?");
		$stmt_getactiveservers->bind_param("ii", $placeID, $stmt_teamcreate);
		$stmt_getactiveservers->execute();

		$result_getactiveservers = $stmt_getactiveservers->get_result();

		if($result_getactiveservers->num_rows != 0) {
			return $result_getactiveservers->fetch_assoc();
		}

		return null;
	}

	function isUserInAGame(int $userID, bool $teamcreate = false): bool {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_teamcreate = $teamcreate ? 1 : 0;

		$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_players` WHERE `session_playerid` = ? AND `session_teamcreate` = ?");
		$stmt_getsessiondetails->bind_param("ii", $userID, $stmt_teamcreate);
		$stmt_getsessiondetails->execute();

		$result_getsessiondetails = $stmt_getsessiondetails->get_result();

		return $result_getsessiondetails->num_rows != 0;
	}

	function getSessionDetails(string $sessionID, bool $teamcreate = false): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_teamcreate = $teamcreate ? 1 : 0;

		$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_players` WHERE `session_id` = ? AND `session_teamcreate` = ?");
		$stmt_getsessiondetails->bind_param("si", $sessionID, $stmt_teamcreate);
		$stmt_getsessiondetails->execute();

		$result_getsessiondetails = $stmt_getsessiondetails->get_result();

		if($result_getsessiondetails->num_rows != 0) {
			return $result_getsessiondetails->fetch_assoc();
		}

		return null;
	}

	function updatePlaceOfSession(string $sessionID, string $placeID, bool $teamcreate = false): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_teamcreate = $teamcreate ? 1 : 0;

		$stmt_getsessiondetails = $con->prepare("UPDATE `active_players` SET `session_serverid` = ? WHERE `session_id` = ? AND `session_teamcreate` = ?");
		$stmt_getsessiondetails->bind_param("ssi", $placeID, $sessionID, $stmt_teamcreate);
		$stmt_getsessiondetails->execute();

		return null;
	}

	function getServerDetails(string $serverID, bool $teamcreate = false): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_teamcreate = $teamcreate ? 1 : 0;

		$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_servers` WHERE `server_id` = ? AND `server_teamcreate` = ?");
		$stmt_getsessiondetails->bind_param("si", $serverID, $stmt_teamcreate);
		$stmt_getsessiondetails->execute();

		$result_getsessiondetails = $stmt_getsessiondetails->get_result();

		if($result_getsessiondetails->num_rows != 0) {
			return $result_getsessiondetails->fetch_assoc();
		}

		return null;
	}

	function provisionServerWithArbiterFallback(int $placeId, string $sessionID, bool $teamcreate = false): ?array {
		global $ARBITER_HOSTS, $ARBITER_BEARER_TOKEN, $ARBITER_RAM_THRESHOLD, $access, $rcc_ip, $rcc_port, $rcc_teamcreate_port;

		$arb = tryProvisionViaArbiter($placeId, $ARBITER_HOSTS, $ARBITER_BEARER_TOKEN, $ARBITER_RAM_THRESHOLD, $teamcreate);
		if ($arb !== null) {
			$jobId = $arb['jobId'];
			$port = $arb['port'];
			$serverid = "arbiter-".$jobId;
			$strPort = strval($port);

			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt_createnewserver = $con->prepare("INSERT INTO `active_servers`(`server_id`, `server_jobid`, `server_placeid`, `server_maxcount`, `server_port`, `server_teamcreate`) VALUES (?,?,?,?,?,?)");
			$stmt_team = $teamcreate ? 1 : 0;
			$maxcount = 0;
			try {
				$placeObj = @Place::FromID(intval($placeId));
				$maxcount = ($placeObj && isset($placeObj->server_size)) ? $placeObj->server_size : 0;
			} catch (Exception $e) {
				$maxcount = 0;
			}
			$stmt_createnewserver->bind_param("ssiisi", $serverid, $jobId, $placeId, $maxcount, $strPort, $stmt_team);
			$stmt_createnewserver->execute();

			updatePlaceOfSession($sessionID, $serverid, $teamcreate);

			return [
				'server_id' => $serverid,
				'jobId' => $jobId,
				'port' => $port,
				'host' => $arb['host'],
				'source' => 'arbiter'
			];
		}

		return null;
	}

	//
	// request=RequestGame
	// placeId=1818
	// isPartyLeader=false
	// gender=
	// isTeleport=false

	if(
		isset($_GET['request'])
	) {
		if(isset($_GET['placeId']) &&
		isset($_GET['isPartyLeader']) &&
		isset($_GET['gender']) &&
		isset($_GET['isTeleport']) &&
		$_GET['request'] == "RequestGame" &&
		$_GET['gender'] == "") {
			$place = Place::FromID(intval($_GET['placeId']));
			$user = UserUtils::RetrieveUser();

			if($place != null && $user != null) {
				$playerID = $user->id;
				if(isUserInAGame($user->id)) {
					include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
					$stmt_deletesession = $con->prepare("DELETE FROM `active_players` WHERE `session_playerid` = ?");
					$stmt_deletesession->bind_param("i", $playerID);
					$stmt_deletesession->execute();
				}

				$server = getAnActiveServer($place->id);

				if($server != null) {
					$serverID = $server['server_id'];
				} else {
					$serverID = strval($place->id);
				}
				$sessionID = getRandomString(25);
				
				include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
				$stmt_createnewsession = $con->prepare("INSERT INTO `active_players`(`session_id`, `session_serverid`, `session_playerid`, `session_status`) VALUES (?,?,?,0)");
				$stmt_createnewsession->bind_param("ssi", $sessionID, $serverID, $playerID);
				$stmt_createnewsession->execute();

				$dont_load = false;
				if(getActiveServersCount($place->id) == 0) {
					$provision = provisionServerWithArbiterFallback($place->id, $sessionID, false);
					if ($provision !== null) {
						$serverid = $provision['server_id'];
						$jobId = $provision['jobId'];
						$port = $provision['port'];
						$fakeahserver = $provision['host'];
						$dont_load = false;
					} else {
						try {
							$serverid = getRandomString();
							$placeId = $place->id;
							$port = rand(50000, 60000);
							$strPort = strval($port);

							$rcc = new Roblox\Grid\Rcc\RCCServiceSoap($rcc_ip, $rcc_port);
							$jobId = md5(rand());
							$job = new Roblox\Grid\Rcc\Job($jobId);
							$script = new Roblox\Grid\Rcc\ScriptExecution($jobId,
							<<<EOT
							loadfile("http://arl.lambda.cam/game/maingameserver.ashx")($placeId, $port, "http://arl.lambda.cam", "$access", "$jobId")
							EOT);
							$base64data = $rcc->OpenJob($job, $script);
							$rcc->RenewLease($jobId, 60 * 60 * 12); // 12 HOURS

							include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
							$stmt_createnewserver = $con->prepare("INSERT INTO `active_servers`(`server_id`, `server_jobid`, `server_placeid`, `server_maxcount`, `server_port`) VALUES (?,?,?,?,?)");
							$stmt_createnewserver->bind_param("ssiis", $serverid, $jobId, $placeId, $place->server_size, $strPort);
							$stmt_createnewserver->execute();

							updatePlaceOfSession($sessionID, $serverid);

						} catch(SoapFault $e) {
							include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
							$stmt_createnewserver = $con->prepare("DELETE FROM `active_players` WHERE `session_id` = ?;");
							$stmt_createnewserver->bind_param("s", $sessionID);
							$stmt_createnewserver->execute();
							die(json_encode([
								"status" => 1,
								"message" => "Wow so much errors!"
							]));
						}
					}
				} else {
					$server_data = getAnActiveServer($place->id);

					if($server_data != null) {
						$serverid = $server_data['server_id'];
					} else {
						$dont_load = true;
					}
				}

				if(!$dont_load) {
					$jobIDThingy = md5(rand());
					$json = json_encode(
						[
							"jobId" => "$jobIDThingy",
							"status" => 2,
							"joinScriptUrl" => "http://arl.lambda.cam/game/join.ashx?serverToken=$serverid&sessionToken=$sessionID&server=$fakeahserver",
							"authenticationUrl" => "https://arl.lambda.cam/Login/Negotiate.ashx",
							"authenticationTicket" => "$sessionID",
							"message" => "HELLOOOOOOOO!!!!!"
						]
					);
					$json = str_replace("\\\\", "", $json);
					$json = str_replace("\\", "", $json); 
					die($json);
				}

			}
		} else if($_GET['request'] == "CloudEdit" && isset($_GET['placeId'])) {
			
			$place = Place::FromID(intval($_GET['placeId']));
			$user = UserUtils::RetrieveUser();

			if($place != null && $user != null) {
				$playerID = $user->id;
				if(isUserInAGame($user->id, true)) {
					include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
					$stmt_deletesession = $con->prepare("DELETE FROM `active_players` WHERE `session_playerid` = ? AND `session_teamcreate` = 1");
					$stmt_deletesession->bind_param("i", $playerID);
					$stmt_deletesession->execute();
				}

				$server = getAnActiveServer($place->id, true);

				if($server != null) {
					$serverID = $server['server_id'];
				} else {
					$serverID = strval($place->id);
				}
				$sessionID = getRandomString(25);
				
				include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
				$stmt_createnewsession = $con->prepare("INSERT INTO `active_players`(`session_id`, `session_serverid`, `session_playerid`, `session_status`, `session_teamcreate`) VALUES (?,?,?,0,1)");
				$stmt_createnewsession->bind_param("ssi", $sessionID, $serverID, $playerID);
				$stmt_createnewsession->execute();

				$dont_load = false;
				if(getActiveServersCount($place->id, true) == 0) {
					$provision = provisionServerWithArbiterFallback($place->id, $sessionID, true);
					if ($provision !== null) {
						$fakeahserver = $provision['host'];
						$jobId = $provision['jobId'];
						$port = $provision['port'];
						$dont_load = false;
					} else {
						try {
							$serverid = getRandomString();
							$placeId = $place->id;
							$port = rand(50000, 60000);
							$strPort = strval($port);

							$rcc = new Roblox\Grid\Rcc\RCCServiceSoap($rcc_ip, $rcc_teamcreate_port);
							$jobId = md5(rand());
							$job = new Roblox\Grid\Rcc\Job($jobId);
							$script = new Roblox\Grid\Rcc\ScriptExecution($jobId,
							<<<EOT
								loadfile("http://arl.lambda.cam/game/maingameserver.ashx")($placeId, $port, "http://arl.lambda.cam", "$access", "$jobId", true, "http://arl.lambda.cam/Data/Upload.ashx?assetid=$placeId&access=$access")
							EOT);
							$base64data = $rcc->OpenJob($job, $script);
							$rcc->RenewLease($jobId, 60 * 60 * 12); // 12 HOURS

							include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
							$stmt_createnewserver = $con->prepare("INSERT INTO `active_servers`(`server_id`, `server_jobid`, `server_placeid`, `server_maxcount`, `server_port`, `server_teamcreate`) VALUES (?,?,?,?,?,1)");
							$stmt_createnewserver->bind_param("ssiis", $serverid, $jobId, $placeId, $place->server_size, $strPort);
							$stmt_createnewserver->execute();

							updatePlaceOfSession($sessionID, $serverid, true);

						} catch(SoapFault $e) {
							
							include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
							$stmt_createnewserver = $con->prepare("DELETE FROM `active_players` WHERE `session_id` = ? AND `session_teamcreate` = 1;");
							$stmt_createnewserver->bind_param("s", $sessionID);
							$stmt_createnewserver->execute();
							
							die(json_encode([
								"status" => 0,
								"error" => "Wow so much errors!"
							]));
						}
					}
				} else {
					$server_data = getAnActiveServer($place->id, true);

					if($server_data != null) {
						$serverid = $server_data['server_id'];
						$port = $server_data['server_port'];
					} else {
						$dont_load = true;
					}
				}

				if(!$dont_load) {
					$jobIDThingy = md5(rand());
					$json = json_encode(
						[
							"status" => 2,
							"settings" => [
									"ClientPort" => 0,
									"MachineAddress" => $fakeahserver,
									"ServerPort" => intval($port),
									"PingUrl" => "",
									"PingInterval" => 120,
									"UserName" => $user->name,
									"SeleniumTestMode" => false,
									"UserId" => $user->id,
									"SuperSafeChat" => false,
									"CharacterAppearance" => "http://arl.lambda.cam/Asset/CharacterFetch.ashx?userId=".$user->id,
									"ClientTicket" => $sessionID,
									"GameId" =>"00000000-0000-0000-0000-000000000000",
									"PlaceId" => $place->id,
									"MeasurementUrl" => "",
									"WaitingForCharacterGuid" => "16be1dd8-5462-4ca5-a997-0725d997708b",
									"BaseUrl" => "http://arl.lambda.cam/",
									"ChatStyle" => "ClassicAndBubble",
									"VendorId" => 0,
									"ScreenShotInfo" => "",
									"VideoInfo" => "",
									"CreatorId" => $place->creator->id,
									"CreatorTypeEnum" => "User",
									"MembershipType" => "None",
									"AccountAge" => 256,
									"SessionId" => "blehhh".rand(),
									"UniverseId" => $place->id,
							]
						]
					);
					$json = str_replace("\\\\", "",$json);
					$json = str_replace("\\", "", $json); 
					die($json);

				}
			}
		}
	} else if(isset($_GET['sessionID'])) {

		$sessionToken = $_GET['sessionID'];
		$session_data = getSessionDetails($sessionToken);

		if($session_data != null) {

			$place = Place::FromID(intval($session_data['session_serverid']));
			
			if($place == null) {
				$server_details = getServerDetails($session_data['session_serverid']);
				$place = Place::FromID(intval($server_details['server_placeid']));
			}
			
			$user = User::FromID(intval($session_data['session_playerid']));

			

			if($place != null && $user != null && !$user->IsBanned()) {
				if(UserUtils::RetrieveUser() == null) {
					UserUtils::SetCookies($user->security_key);
				}
				$dont_load = false;
				if(getActiveServersCount($place->id) == 0) {
					$provision = provisionServerWithArbiterFallback($place->id, $sessionToken, false);
					if ($provision !== null) {
						$fakeahserver = $provision['host'];
						$serverid = $provision['server_id'];
						$jobId = $provision['jobId'];
						$port = $provision['port'];
						$dont_load = false;
					} else {
						try {
							$serverid = getRandomString();
							$placeId = $place->id;
							$port = rand(50000, 60000);
							$strPort = strval($port);

							$rcc = new Roblox\Grid\Rcc\RCCServiceSoap($rcc_ip, $rcc_port);
							$jobId = md5(rand());
							$job = new Roblox\Grid\Rcc\Job($jobId);
							$script = new Roblox\Grid\Rcc\ScriptExecution($jobId,
							<<<EOT
							loadfile("http://arl.lambda.cam/game/maingameserver.ashx")($placeId, $port, "http://arl.lambda.cam", "$access", "$jobId")
							EOT);
							$base64data = $rcc->OpenJob($job, $script);
							$rcc->RenewLease($jobId, 60 * 60 * 12); // 12 HOURS

							include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
							$stmt_createnewserver = $con->prepare("INSERT INTO `active_servers`(`server_id`, `server_jobid`, `server_placeid`, `server_maxcount`, `server_port`) VALUES (?,?,?,?,?)");
							$stmt_createnewserver->bind_param("ssiis", $serverid, $jobId, $placeId, $place->server_size, $strPort);
							$stmt_createnewserver->execute();

							updatePlaceOfSession($sessionToken, $serverid);

						} catch(SoapFault $e) {
							include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
							$stmt_createnewserver = $con->prepare("DELETE FROM `active_players` WHERE `session_id` = ?;");
							$stmt_createnewserver->bind_param("s", $sessionToken);
							$stmt_createnewserver->execute();
							die(json_encode([
								"status" => 0,
								"error" => "Wow so much errors!"
							]));
						}
					}
				} else {
					$server_data = getAnActiveServer($place->id);

					if($server_data != null) {
						$serverid = $server_data['server_id'];
					} else {
						$dont_load = true;
					}
				}

				if(!$dont_load) {
					$jobIDThingy = md5(rand());
					die(json_encode(
						[
							"jobId" => "$jobIDThingy",
							"status" => 2,
							"joinScriptUrl" => "http://arl.lambda.cam/game/join.ashx?serverToken=$serverid&sessionToken=$sessionToken&server=$fakeahserver",
							"authenticationUrl" => "https://arl.lambda.cam/Login/Negotiate.ashx",
							"authenticationTicket" => "$sessionToken",
							"message" => "HELLOOOOOOOO!!!!!"
						]
					));
				}
				
			}
		}
	}

	die(json_encode([
		"status" => 0,
		"error" => "Wow so much errors!"
	]));
	

?>