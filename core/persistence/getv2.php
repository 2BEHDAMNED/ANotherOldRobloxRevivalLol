<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/classes/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/classes/user.php";
	include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

	header("Content-Type: application/json");

	if (isset($_SERVER['HTTP_USER_AGENT']) && str_contains($_SERVER['HTTP_USER_AGENT'],"Studio")) { // what the fuck am i even doing
		http_response_code(406);
		exit(json_encode(["error" => "Not Acceptable"], JSON_NUMERIC_CHECK));
	}

	// only allow on domain of gamepersistence because FUCK YOU!
	error_log($_SERVER['SERVER_NAME']);
	if ($_SERVER['SERVER_NAME'] != "gamepersistence.lambda.cam" && $_SERVER['SERVER_NAME'] != "persistence.lambda.cam") {
		http_response_code(406);
		exit(json_encode(["error" => "Not Acceptable Domain..."], JSON_NUMERIC_CHECK));
	}

	function removeEverythingBefore($in, $before) {
		$pos = strpos($in, $before);
		return $pos !== FALSE
			? substr($in, $pos + strlen($before), strlen($in))
			: "";
	}

	function removeEverythingAfter($in, $before) {
		$pos = strpos($in, $before);
		return $pos !== FALSE
			? substr($in, $pos - strlen($before), strlen($in))
			: "";
	}

	if(isset($_GET["placeId"])&&isset($_GET["scope"])&&isset($_GET["type"])){
		$values=[];
		$input = file_get_contents('php://input');
		$qkeys = explode("&",substr($input, 1));
		$tempTable = array();
		foreach($qkeys as &$val){
			$after = substr($val, 0, strpos($val, "="));
			$tempTable[$after]=removeEverythingBefore($val,"=");
		}
		$qkeys = $tempTable;
		$tempTable = null;
		
		if(isset($qkeys['qkeys[0].key'])&&isset($qkeys['qkeys[0].target'])){
			$key = (string)urldecode($qkeys['qkeys[0].key']);
			$pid = intval($_GET["placeId"]);
			$scope = (string)urldecode($_GET["scope"]);
			$type = (string)urldecode($_GET["type"]);
			$target = (string)urldecode($qkeys['qkeys[0].target']);
			
			$asset = Place::FromID($pid);
		
			if ($asset == null) {
				http_response_code(404);
				exit(json_encode(["error" => "Not Found"], JSON_NUMERIC_CHECK));
			}
			
			if ($asset->type != AssetType::PLACE) {
				http_response_code(406);
				exit(json_encode(["error" => "Not Acceptable"], JSON_NUMERIC_CHECK));
			}
			
			$stmt = $con->prepare("SELECT * FROM `datastores` WHERE `universeId`= ? AND `scope`= ? AND `type`= ? AND `dkey`= ? AND `target`= ?");
			$stmt->bind_param('sisss', $key, $scope, $type, $target); 
			$stmt->execute();
			
			$result = $stmt->get_result()->fetch_all();
			foreach($result as &$data){
				array_push($values,array("Value"=>$data["value"],"Scope"=>$data["scope"],"Key"=>$data["key"],"Target"=>$data["target"]));
			}
			exit(json_encode(["data"=>$values], JSON_NUMERIC_CHECK));
		} else {
			http_response_code(400);
			exit(json_encode(["error"=>"Bad Request"]));
		}
	}
	http_response_code(405);
	exit(json_encode(["error"=>"Method Not Allowed"]));
?>