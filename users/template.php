<?php
	function IsRewrite() {
		if(!empty($_SERVER['IIS_WasUrlRewritten']))
			return true;
		else if(array_key_exists('HTTP_MOD_REWRITE',$_SERVER))
			return true;
		else if( array_key_exists('REDIRECT_URL', $_SERVER))
			return true;
		else
			return false;
	}

	if(!IsRewrite()) {
		die(header("Location: /my/home"));
	}

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';

	// No id parameter? GET OUT!
	if(!isset($_GET['id'])) {
		die(header("Location: /my/home"));
	}

	$get_user = User::FromID(intval($_GET['id']));

	if($get_user == null) {
		die(header("Location: /my/home"));
	}
?>
{"Success":true,"CanManage":true}