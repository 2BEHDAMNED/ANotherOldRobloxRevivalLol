<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/user.php";

	$userId = (int)$_GET['userId'];

	$user = User::FromID($userId);

	$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
	$otherUserIds = array();
	$parameters = explode('&', $queryString);
	foreach ($parameters as $parameter) {
		list($key, $value) = explode('=', $parameter);
		if ($key === 'otherUserIds') {
			$otherUser = User::FromID(intval($value));
			if($otherUser != null && !$otherUser->IsBanned()) {
				$otherUserIds[] = $otherUser;
			}
		}
	}

	$friendUserIds = [];
	foreach ($otherUserIds as $otherUser) {
		if($user->IsFriendsWith($otherUser)) {
			$friendUserIds[] = $otherUser->id;
		}
	}

	echo "X" . implode(",", $friendUserIds);
?>