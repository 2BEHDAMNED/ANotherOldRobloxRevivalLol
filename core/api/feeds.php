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
		return $string ? implode(', ', $string) . '' : 'just now';
	}

	if($user != null) {

		$page = 1;
		if(isset($_GET['p'])) {
			$page = intval($_GET['p']);
		}

		if($page < 1) {
			die(header("Location: /api/feeds?p=1"));
		}

		$statuses = Status::GetLatestFeedsPaged($page, 5);

		$statuses_raw = [];

		if(count($statuses) != 0) {
			foreach($statuses as $status) {
				if($status instanceof Status) {
					array_push($statuses_raw, [
						"id" => $status->id,
						"poster" => [
							"id" => $status->poster->id,
							"name" => $status->poster->name
						],
						"content" => $status->content,
						"time_posted" => $status->time_posted->getTimestamp() - 3600,
						"time_posted_label" => time_elapsed_string('@'.($status->time_posted->getTimestamp() - 3600))
					]);
				}
			}
		}

		die(json_encode(["feed" => $statuses_raw, "page" => $page, "total_pages" => floor(Status::GetLatestFeedsCount()/5)+1]));
	} else {
		die(json_encode(["error" => true, "reason" => "User not logged in."]));
	}
?>