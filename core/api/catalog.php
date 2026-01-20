<?php
	header("Content-Type: application/json");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();

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

	$total_pages = floor((count(Asset::GetAssetsOfType($query, $asset_type))/16) + 0.5)+1;

	if(count(Asset::GetAssetsOfTypePaged($query, $asset_type, $total_pages, 16)) == 0) {
		$total_pages--;
	}

	if($total_pages < $page && $page != 1) {
		die(header("Location: /api/catalog?c=$type&q=$query&p=1"));
	}

	$assets = Asset::GetAssetsOfTypePaged($query, $asset_type, $page, 16);

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
					"onsale" => $asset->onsale,
					"favourites" => $asset->favourites_count,
					"sales_count" => $asset->sales_count
				]);
			}
		}
	}
		
	die(json_encode(["assets" => $assets_raw, "page" => $page, "total_pages" => $total_pages]));

	
?>