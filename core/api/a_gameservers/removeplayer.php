<?php
	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	
	$rcc_settings = $settings['renderer'];

	$access = $settings['asset']['ACCESSKEY'];
	$rcc_ip = $rcc_settings['RCCGAMEIP'];
	$rcc_port = 64898;

	if(isset($_GET['access']) && isset($_GET['jobID']) && isset($_GET['userID'])) {
		if($_GET['access'] == $access) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt_createnewserver = $con->prepare("DELETE FROM `active_servers` WHERE `server_jobid` = ?;");
			$stmt_createnewserver->bind_param("s", $_GET['jobID']);
			$stmt_createnewserver->execute();
		}
		
	}
?>