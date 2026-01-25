<?php
	if(isset($_GET['username'])) {
		$user = User::FromName($_GET['username']);
		if($user != null) {
			die(json_encode([
				"Id" => $user->id
			]));
		}
		
	}
	echo "{}";
?>