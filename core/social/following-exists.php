<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	header("Content-Type: application/json");
	if(isset($_GET['userId']) && isset($_GET['followerUserId'])) {
		$user = User::FromID(intval($_GET['userId']));
		$userToCheck = User::FromID(intval($_GET['followerUserId']));

		if($user != null && $userToCheck != null) {
			die(json_encode(
				[
					"success" => true,
					"isFollowing" => $user->IsFollowing($userToCheck)
				]
			));
		}
	}

	die(json_encode(
		[
			"success" => false
		]
	));
?>