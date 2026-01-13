<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	file_put_contents($_SERVER['DOCUMENT_ROOT']."/test.txt", print_r($_POST));

	$user = UserUtils::RetrieveUser();

	if(isset($_POST['followedUserId']) && $user != null) {
		$toFollowUser = User::FromID(intval($_POST['followedUserId']));

		if($toFollowUser != null) {
			$user->Follow($toFollowUser);
		}
	}
	http_response_code(500);
	die("Invalid shit or something");
?>