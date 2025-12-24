<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

	function getRandomString($length = 11): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}

		return $randomString;
	}

	if(
		isset($_GET['action']) &&
		isset($_GET['access']) &&
		isset($_GET['PlaceID']) &&
		isset($_GET['UserID']) &&
		isset($_GET['serverId']) &&

		$_GET['access'] == "8169b38d-abbc-480f-8971-14d8fd560aad"
	) {
		$action = trim($_GET['action']);
		$place = Place::FromID(intval($_GET['PlaceID']));
		$user = User::FromID(intval($_GET['UserID']));
		$serverId = trim($_GET['serverId']);

		$stmt_checkserver = $con->prepare("SELECT * FROM `active_servers` WHERE `server_id` = ?;");
		$stmt_checkserver->bind_param("s", $serverId);
		$stmt_checkserver->execute();

		$result_checkserver = $stmt_checkserver->get_result();
		$row = $result_checkserver->fetch_assoc();

		if($place != null && $user != null && $result_checkserver->num_rows == 1) {
			

			if($action == "connect") {
				$sessionid = getRandomString();
				$stmt_insertplayer = $con->prepare("INSERT INTO `active_players`(`session_id`, `session_serverid`, `session_playerid`) VALUES (?, ?, ?)");
				$stmt_insertplayer->bind_param("ssi", $sessionid, $serverId, $user->id);
				$stmt_insertplayer->execute();
			} else if($action == "disconnect" && isset($_GET['shouldClose'])) {

				$stmt_removeplayer = $con->prepare("DELETE FROM `active_players` WHERE `session_serverid` = ? AND `session_playerid` = ?");
				$stmt_removeplayer->bind_param("si", $serverId, $user->id);
				$stmt_removeplayer->execute();

				$placeId = $place->id;
				$year = $row['server_year'];

				if($_GET['shouldClose'] == "true") {
					echo "http://localhost:64209/$year/StopServer?serverId=$serverId&placeId=$placeId";
					file_get_contents("http://localhost:64209/$year/StopServer?serverId=$serverId&placeId=$placeId");
				}
			}
		}

		
	}

?>