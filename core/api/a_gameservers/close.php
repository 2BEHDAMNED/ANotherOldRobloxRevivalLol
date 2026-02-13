<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/renderer.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/rcclib.php";

	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	
	$rcc_settings = $settings['renderer'];

	$access = $settings['asset']['ACCESSKEY'];
	$rcc_ip = $rcc_settings['RCCGAMEIP'];
	$rcc_gameserver_port = 64898;
	$rcc_teamcreate_port = 64888;

	if(isset($_GET['access']) && isset($_GET['jobID'])) {
		if($_GET['access'] == $access) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			
			$stmt_getactiveservers = $con->prepare("SELECT * FROM `active_servers` WHERE `server_jobid` = ?");
			$stmt_getactiveservers->bind_param("s", $_GET['jobID']);
			$stmt_getactiveservers->execute();

			$result_getactiveservers = $stmt_getactiveservers->get_result();

			if($result_getactiveservers->num_rows != 0) {
				$row = $result_getactiveservers->fetch_assoc();

				if(!isset($_GET['dontcall'])) {
					if($row['server_year'] != "2016") {
						$rcc_ip = "192.168.0.220";
					}
					if($row['server_year'] == "2016") {
						$rcc_port = $row['server_teamcreate'] == 1 ? $rcc_teamcreate_port : $rcc_gameserver_port;

						$rcc = new Roblox\Grid\Rcc\RCCServiceSoap($rcc_ip, $rcc_port);
						$rcc->CloseJob(trim($_GET['jobID']));

						$rcc2 = new RCCServiceSoap($rcc_ip, $rcc_port);
						$rcc2->closeJob(trim($_GET['jobID']));
					} else if($row['server_year'] == "2013") {
						file_get_contents("http://$rcc_ip:64209/2013/StopServer?serverId=".$row['server_id']."&placeId=".$row['server_placeid']);
					} else {
						file_get_contents("http://$rcc_ip:64209/2010/StopServer?serverId=".$row['server_id']."&placeId=".$row['server_placeid']);
					}
				}

				$stmt_createnewserver = $con->prepare("DELETE FROM `active_servers` WHERE `server_jobid` = ?;");
				$stmt_createnewserver->bind_param("s", $_GET['jobID']);
				$stmt_createnewserver->execute();
			}


		}
		
	}
?>