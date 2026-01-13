<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	if(isset($_POST['followedUserId']) && $user != null) {
		$toFollowUser = User::FromID(intval($_POST['followedUserId']));

		if($toFollowUser != null) {
			$user->Follow($toFollowUser);
		}
	}
	http_response_code(500);
	die("Invalid shit or something");
?>