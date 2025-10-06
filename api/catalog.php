<?php
	header("Content-Type: application/json");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();

	if($user != null) {

		
		$type = AssetType::HAT->ordinal();
		if(isset($_GET['c'])) {
			$type = intval($_GET['c']);
		}

		$asset_type = AssetType::index($type);

		$query = "";

		if(isset($_GET['q'])) {
			$query = $_GET['q'];
		}

		$page = 1;
		if(isset($_GET['p'])) {
			$page = intval($_GET['p']);
		}

		if($page < 1) {
			die(header("Location: /api/catalog?c=$type&q=$query&p=1"));
		}		

		$total_pages = floor(count(Asset::GetAssetsOfType($query, $asset_type))/12)+1;

		if($total_pages < $page) {
			die(header("Location: /api/stuff?c=$type&q=$query&p=1"));
		}

		$assets = Asset::GetAssetsOfTypePaged($query, $asset_type, $page, 12);

		$assets_raw = [];

		if(count($assets) != 0) {
			foreach($assets as $asset) {
				if($asset instanceof Asset) {
					array_push($assets_raw, [
						"id" => $asset->id,
						"name" => $asset->name,
						"creator" => [
							"id" => $asset->creator->id,
							"name" => $asset->creator->name
						],
						"cost" => [
							"cones" => $asset->cost_cones,
							"lights" => $asset->cost_lights
						],
						"onsale" => $asset->onsale
					]);
				}
			}
		}
		
		die(json_encode(["assets" => $assets_raw, "page" => $page, "total_pages" => $total_pages]));
	} else {
		die(json_encode(["error" => true, "reason" => "User not logged in."]));
	}
	
?>