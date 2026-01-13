<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	
	$user = UserUtils::RetrieveUser();

	if(isset($_POST['followedUserId']) && $user != null) {
		$toFollowUser = User::FromID(intval($_POST['followedUserId']));

		if($toFollowUser != null) {
			if(!$user->IsFollowing($toFollowUser)) {
				$user->Follow($toFollowUser);

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
	}

	die(json_encode(
		[
			"success" => false
		]
	));
?>