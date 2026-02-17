<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/classes/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/classes/user.php";
	include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

	header("Content-Type: application/json");

	if (isset($_SERVER['HTTP_USER_AGENT']) && str_contains($_SERVER['HTTP_USER_AGENT'],"Studio")) { // what the fuck am i even doing
		http_response_code(406);
		exit(json_encode(["error" => "Not Acceptable"], JSON_NUMERIC_CHECK));
	}

	if(isset($_GET["value"]) && isset($_GET["key"]) && isset($_GET["placeId"]) && isset($_GET["scope"]) && isset($_GET["type"]) && isset($_GET["target"])){
		$values=[];
		$key = (string)$_GET["key"];
		$pid = intval($_GET["placeId"]);
		$scope = (string)$_GET["scope"];
		$type = (string)$_GET["type"];
		$target = (string)$_GET["target"];
		
		$asset = Place::FromID($pid);
		
		if ($asset == null) {
			http_response_code(404);
			exit(json_encode(["error" => "Not Found"], JSON_NUMERIC_CHECK));
		}
		
		if ($asset->type != AssetType::PLACE) {
			http_response_code(406);
			exit(json_encode(["error" => "Not Acceptable"], JSON_NUMERIC_CHECK));
		}

		$query = "INSERT INTO `datastores`(`dkey`, `universeId`, `type`, `scope`, `target`, `value`) VALUES (?,?,?,?,?,?)";
		
		$where = "WHERE `universeId`= ? AND `scope`= ? AND `type`= ? AND `dkey`= ? AND `target`= ?";
	
		$stmt = $con->prepare("SELECT * FROM `datastores` WHERE `scope` = ? AND `type`= ? AND `dkey`= ? AND `target`= ? AND `universeId` != ?");
		$stmt->bind_param("ssssi", $scope, $type, $key, $target, $pid);
		$stmt->execute();
		
		$stmt = $con->prepare("SELECT * FROM `datastores` $where");
		$stmt->bind_param("issss", $pid, $scope, $type, $key, $target);
		$stmt->execute();
		$stmt_result = $stmt->get_result();
		if($stmt_result->num_rows > 0){
			$existingRecord = $stmt_result->fetch_assoc();
			
			if ($existingRecord['universeId'] == $pid) {
				$query = "UPDATE `datastores` SET `value`= value + ? $where";
			} else {
				http_response_code(405);
				exit(json_encode(["error"=>"Method Not Allowed"]));
			}
		}
		
		$stmt = $con->prepare($query);
		$stmt->bind_param("sissss", $_GET['value'], $pid, $scope, $type, $dkey, $target);
		$stmt->execute();
		
		$stmt = $con->prepare("SELECT value FROM `datastores` $where");
		$stmt->bind_param("issss", $pid, $scope, $type, $dkey, $target);
		$stmt->execute();
		
		$values = [array("Value"=>$_GET['value'],"Scope"=>$scope,"Key"=>$key,"Target"=>$target)];
		
		exit(json_encode(["data"=>$values], JSON_NUMERIC_CHECK));
	}

	exit(json_encode(["error"=>"Method Not Allowed"]));
?>