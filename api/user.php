<?php
	header("Content-Type: application/json");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	if(isset($_GET['id']) && isset($_GET['request'])) {
		$user = User::FromID(intval($_GET['id']));
		if($user == null) {
			$user = UserUtils::RetrieveUser();
		}

		if($user != null) {
			if($_GET['request'] == "getuserbadges") {
				$page = 1;
				if(isset($_GET['p'])) {
					$page = intval($_GET['p']);
				}
				
				if($page < 1) {
					$id = $user->id;
					die(header("Location: /api/user?id=$id&request=getuserbadges&p=1"));
				}
				
				$badges = $user->GetAllOwnedAssetsOfTypePaged(AssetType::BADGE,$page, 12);
				$badges_raw = [];
		
				if(count($badges) != 0) {
					foreach($badges as $asset) {
						if($asset instanceof Asset) {
							array_push($badges_raw, [
								"id" => $asset->id,
								"name" => $asset->name
							]);
						}
					}
				}
		
				die(json_encode(["badges" => $badges_raw, "page" => $page, "total_pages" => floor(count($user->GetAllOwnedAssetsOfType(AssetType::BADGE))/12)+1]));
			} else if($_GET['request'] == "isadmin") {
				die(json_encode(['error' => false, 'isadmin' => $user->IsAdmin()]));
			} else {
				die(json_encode(["error" => true, "reason" => "Invalid request"]));
			}
			
		} else {
			die(json_encode(["error" => true, "reason" => "User not found."]));
		}
	} else {
		die(json_encode(["error" => true, "reason" => "Invalid request"]));
	}
	
	
?>