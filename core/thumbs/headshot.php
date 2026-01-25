<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = User::FromID(1);

	if(isset($_GET['userid'])) {
		$user = User::FromID(intval($_GET['userid']));
	}

	if($user == null) {
		$user = User::FromID(1);
	}

	if($user->setprofilepicture) {
		die(header("Location: /thumbs/profile?id=".$user->id));
	} else {
		die(header("Location: /thumbs/players?id=".$user->id));
	}

?>