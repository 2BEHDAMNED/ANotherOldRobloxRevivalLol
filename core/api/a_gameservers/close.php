<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/rcclib.php";

	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	
	$rcc_settings = $settings['renderer'];

	$access = $settings['asset']['ACCESSKEY'];
	$rcc_ip = $rcc_settings['RCCGAMEIP'];
	$rcc_port = 64898;
	
	if(isset($_GET['access']) && isset($_GET['jobID'])) {
		if($_GET['access'] == $access) {
			$rcc= new RCCServiceSoap($rcc_ip, $rcc_port);
			$rcc->CloseJob($_GET['jobID']."-GameScript");
		}
		
	}
?>