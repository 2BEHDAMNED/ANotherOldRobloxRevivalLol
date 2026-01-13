<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	ob_clean();

	print_r($_POST);
	print_r($_COOKIE);

	$data = ob_get_clean();

	file_put_contents($_SERVER['DOCUMENT_ROOT']."/test.txt", $data);

	$user = UserUtils::RetrieveUser();

	if(isset($_POST['followedUserId']) && $user != null) {
		$toFollowUser = User::FromID(intval($_POST['followedUserId']));

		if($toFollowUser != null) {
			$user->Follow($toFollowUser);
		}
	}
	http_response_code(405);
	die("Invalid shit or something");
?>