<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	
	$user = UserUtils::RetrieveUser();

	if(isset($_POST['followedUserId']) && $user != null) {
		$toFollowUser = User::FromID(intval($_POST['followedUserId']));

		if($toFollowUser != null) {
			$user->Unfollow($toFollowUser);
			http_response_code(200);
			die(json_encode(
				[
					"success" => true
				]
			));
		} else {
			die(json_encode(
				[
					"success" => false
				]
			));
		}
	}
	http_response_code(405);
	die("Invalid shit or something");
?>