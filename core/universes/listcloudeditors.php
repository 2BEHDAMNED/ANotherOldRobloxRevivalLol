<?php 
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/classes/asset.php";
	header("Content-Type: application/json");

	if(isset($_GET['universeId'])) {
		$place = Place::FromID(intval($_GET['universeId']));

		if($place != null && $place->teamcreate_enabled) {
			$editorusers = $place->GetCloudEditors();

			$editors = [];

			foreach($editorusers as $user) {
				if($user instanceof User) {
					if(!$user->IsBanned()) {
						array_push($editors, [
							"userId" => $user->id,
							"isAdmin" => $user->id == $place->creator->id
						]);
					}
				}
			}

			die(json_encode([
				"finalPage" => true,
				"users" => $editors
			]));
		}
	}

	echo "{}";
?>