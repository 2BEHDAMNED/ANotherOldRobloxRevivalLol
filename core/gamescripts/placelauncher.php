<?php

	/**
	 * I dont fucking know bruh this is like cancer in a way
	 * 
	 * GOOD THING THIS IS FRIENDS ONLY OTHER WISE I WOULD BE COOKED. 
	 * I CANNOT BE ASKED TO DO SECURITY STUFF AS MUCH AS I WANT TO.
	 */

	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/renderer.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";


	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	$rcc_settings = $settings['renderer'];

	$access = $settings['asset']['ACCESSKEY'];
	$rcc_ip = $rcc_settings['RCCGAMEIP'];
	$rcc_port = 64898;

	header("Content-Type: application/json");

	function getRandomString(): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		
		for ($i = 0; $i < 11; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}

		return $randomString;
	}

	function getActiveServersCount(int $placeID): bool {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getactiveservers = $con->prepare("SELECT * FROM `active_servers` WHERE `server_placeid` = ? AND `server_playercount` != `server_maxcount`");
		$stmt_getactiveservers->bind_param("i", $placeID);
		$stmt_getactiveservers->execute();

		$result_getactiveservers = $stmt_getactiveservers->get_result();

		return $result_getactiveservers->num_rows;
	}

	function getAnActiveServer(int $placeID): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getactiveservers = $con->prepare("SELECT * FROM `active_servers` WHERE `server_placeid` = ? AND `server_playercount` != `server_maxcount`");
		$stmt_getactiveservers->bind_param("i", $placeID);
		$stmt_getactiveservers->execute();

		$result_getactiveservers = $stmt_getactiveservers->get_result();

		if($result_getactiveservers->num_rows != 0) {
			return $result_getactiveservers->fetch_assoc();
		}

		return null;
	}

	function isUserInAGame(int $userID): bool {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_players` WHERE `session_playerid` = ?");
		$stmt_getsessiondetails->bind_param("i", $userID);
		$stmt_getsessiondetails->execute();

		$result_getsessiondetails = $stmt_getsessiondetails->get_result();

		return $result_getsessiondetails->num_rows != 0;
	}

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

	function updatePlaceOfSession(string $sessionID, string $placeID): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getsessiondetails = $con->prepare("UPDATE `active_players` SET `session_serverid` = ? WHERE `session_id` = ?");
		$stmt_getsessiondetails->bind_param("ss", $placeID, $sessionID);
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

	if(isset($_GET['sessionID'])) {

		$sessionToken = $_GET['sessionID'];
		$session_data = getSessionDetails($sessionToken);

		if($session_data != null) {

			
			if(intval($session_data['session_serverid']) == 0) {
				$server_details = getServerDetails($session_data['session_serverid']);
				$place = Place::FromID(intval($server_details['server_placeid']));

			} else {
				$place = Place::FromID(intval($session_data['session_serverid']));
			}
			
			$user = User::FromID(intval($session_data['session_playerid']));

			if($place != null && $user != null && !$user->IsBanned()) {
				$dont_load = false;
				if(getActiveServersCount($place->id) == 0) {
					try {
						$serverid = getRandomString();
						$placeId = $place->id;
						$port = rand(50000, 60000);
						$strPort = strval($port);

						$rcc = new Roblox\Grid\Rcc\RCCServiceSoap($rcc_ip, $rcc_port);
						$jobId = md5(rand());
						$job = new Roblox\Grid\Rcc\Job($jobId);
						$script = new Roblox\Grid\Rcc\ScriptExecution($jobId."-GameScript",
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
							"error" => "Wow so much errors!"
						]));
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
							"joinScriptUrl" => "http://arl.lambda.cam/game/join.ashx?serverToken=$serverid&sessionToken=$sessionToken",
							"authenticationUrl" => "https://arl.lambda.cam/Login/Negotiate.ashx",
							"authenticationTicket" => "$sessionToken",
							"message" => "HELLOOOOOOOO!!!!!"
						]
					));
				}
				
			}
		}
	}

	die(json_encode(
	[
			"error" => "Wow so much errors!"
		]
	));
	

?>