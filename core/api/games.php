<?php
	header("Content-Type: application/json");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();
	error_reporting(E_ALL ^ E_DEPRECATED);
	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	if($user != null) {
		if(!isset($_GET['placeid'])) {
			$page = 1;
			if(isset($_GET['p'])) {
				$page = intval($_GET['p']);
			}

			$query = "";

			if(isset($_GET['q'])) {
				$query = $_GET['q'];
			}

			if($page < 1) {
				die(header("Location: /api/games?q=$query&p=1"));
			}

			$retrievedassets = Asset::GetAssetsOfTypePaged($query, AssetType::PLACE, $page, 9, false);

			$assets = [];

			if(count($retrievedassets) != 0) {
				foreach($retrievedassets as $asset) {
					if($asset instanceof Place) {
						array_push($assets, [
							"id" => $asset->id,
							"creator" => [
								"id" => $asset->creator->id,
								"name" => $asset->creator->name
							],
							"name" => $asset->name,
							"favourites" => $asset->favourites_count
						]);
					}
				}
			}

			$total_pages = floor(count(Asset::GetAssetsOfType($query, AssetType::PLACE, false))/9)+1;

			if($total_pages < $page) {
				die(header("Location: /api/games?q=$query&p=1"));
			}

			die(json_encode(["games" => $assets, "page" => $page, "total_pages" => $total_pages]));
		} else {
			$placeid = intval($_GET['placeid']);

			$place = Place::FromID($placeid);

			die(json_encode(
				[
					"error" => false,
					"place" => [
						"id" => $place->id,
						"name" => $place->name,
						"description" => $place->description
					]
				]
			));
		}

	} else {
		die(json_encode(["error" => true, "reason" => "User not logged in."]));
	}
?>