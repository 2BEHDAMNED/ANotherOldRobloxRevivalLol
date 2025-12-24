<?php
	header("Content-Type: text/plain");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

	$user = UserUtils::RetrieveUser();

	if($user != null) {
		if(isset($_POST['placeId'])) {
			$place = Place::FromID(intval($_POST['placeId']));

			if($place != null) {
				$year = $place->year->label();
				$id = $place->id;
				$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
				$address = $settings['renderer']['RCCIP'];
				$fcontents = file_get_contents("http://$address:64209/$year/StartServer?id=$id");
				
				if(str_starts_with($fcontents, "{'success':true,")) {
					$stringerr = str_replace("{'success':true,", "", $fcontents);
					$stringerr = str_replace("'serverId':'", "", $stringerr);
					$stringerr = str_replace("'}", "", $stringerr);

					$stmt_checkserver = $con->prepare("SELECT * FROM `active_servers` WHERE `server_id` = ?;");
					$stmt_checkserver->bind_param("s", $stringerr);
					$stmt_checkserver->execute();

					$result_checkserver = $stmt_checkserver->get_result();

					if($result_checkserver->num_rows == 1) {
						$row = $result_checkserver->fetch_assoc();

						$data = [
							"sessionToken" => $user->security_key,
							"year" => $row['server_year'],
							"port" => $row['server_port']
						];

						die(urlencode(base64_encode(json_encode($data))));
					}
				} else {
					$stringerr = str_replace("{'success':false,", "", $fcontents);
					$stringerr = str_replace("'reason':'", "", $stringerr);
					$stringerr = str_replace("'}", "", $stringerr);

					echo "!err!".$stringerr;
				}

				/**/
			}

			
		} else if(isset($_POST['serverId'])) {
			$stmt_checkserver = $con->prepare("SELECT * FROM `active_servers` WHERE `server_id` = ?;");
			$stmt_checkserver->bind_param("s", $_POST['serverId']);
			$stmt_checkserver->execute();

			$result_checkserver = $stmt_checkserver->get_result();

			if($result_checkserver->num_rows == 1) {
				$row = $result_checkserver->fetch_assoc();

				$data = [
					"sessionToken" => $user->security_key,
					"year" => $row['server_year'],
					"port" => $row['server_port']
				];

				die(urlencode(base64_encode(json_encode($data))));
			}

			
		}
	}
?>