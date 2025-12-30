<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/renderer.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/rcclib.php";

	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	
	$rcc_settings = $settings['renderer'];

	$access = $settings['asset']['ACCESSKEY'];
	$rcc_ip = $rcc_settings['RCCGAMEIP'];
	$rcc_port = 64898;

	if(isset($_GET['access']) && isset($_GET['jobID'])) {
		if($_GET['access'] == $access) {
			$rcc = new Roblox\Grid\Rcc\RCCServiceSoap($rcc_ip, $rcc_port);
			$rcc->CloseJob(trim($_GET['jobID'])."-GameScript");

			$rcc2 = new RCCServiceSoap($rcc_ip, $rcc_port);
			$rcc2->closeJob(trim($_GET['jobID'])."-GameScript");

			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt_createnewserver = $con->prepare("DELETE FROM `active_servers` WHERE `server_jobid` = ?;");
			$stmt_createnewserver->bind_param("s", $_GET['jobID']);
			$stmt_createnewserver->execute();
		}
		
	}
?>