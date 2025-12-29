<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();

	function getRandomString(): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		
		for ($i = 0; $i < 25; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}

		return $randomString;
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

	if($user != null) {
		if(isset($_POST['placeID'])) {

			$place = Place::FromID(intval($_POST['placeID']));

			if($place != null) {

				if(isUserInAGame($user->id)) {
					include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
					$stmt_createnewsession = $con->prepare("DELETE FROM `active_players` WHERE `session_playerid` = ?");
					$stmt_createnewsession->bind_param("i", $playerID);
					$stmt_createnewsession->execute();
				}

				$server = getAnActiveServer($place->id);

				if($server != null) {
					$serverID = $server['server_id'];
				} else {
					$serverID = strval($place->id);
				}
				$sessionID = getRandomString();
				$playerID = $user->id;
				include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
				$stmt_createnewsession = $con->prepare("INSERT INTO `active_players`(`session_id`, `session_serverid`, `session_playerid`, `session_status`) VALUES (?,?,?,0)");
				$stmt_createnewsession->bind_param("ssi", $sessionID, $serverID, $playerID);
				$stmt_createnewsession->execute();

				die($sessionID);

			}

		}
	}
	