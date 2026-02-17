<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/classes/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/classes/user.php";
	include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

	header("Content-Type: application/json");

	if (isset($_SERVER['HTTP_USER_AGENT']) && str_contains($_SERVER['HTTP_USER_AGENT'],"Studio")) { // what the fuck am i even doing
		http_response_code(406);
		exit(json_encode(["error" => "Not Acceptable"], JSON_NUMERIC_CHECK));
	}

	function startsWith ($string, $startString)  { 
		$len = strlen($startString); 
		return (substr($string, 0, $len) === $startString); 
	} 

	function endsWith($string, $endString)  { 
		$len = strlen($endString); 
		if ($len == 0) { 
			return true; 
		} 
		return (substr($string, -$len) === $endString); 
	} 

	function stripQuotes($text) {
		return preg_replace('/^(\'[^\']*\'|"[^"]*")$/', '$2$3', $text);
	} 

	if(isset($_POST["value"])&&isset($_GET["key"])&&isset($_GET["placeId"])&&isset($_GET["scope"])&&isset($_GET["type"])&&isset($_GET["target"])){
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

		// parses json in this horrible mess
        if (startsWith($_POST["value"], "[{") && endsWith($_POST["value"], "}]")){
            $postData = json_decode($_POST["value"]);

            if (count($postData) == 1){
                if (isset($postData['0']->Scope) && isset($postData['0']->Key) && isset($postData['0']->Value)){
                    $_POST["value"] = $postData['0']->Value;
                }
            }
        }
		
		$stmt = $con->prepare("SELECT * FROM `datastores` WHERE `scope`= ? AND `type`= ? AND `dkey`= ? AND `target`= ? AND `universeId` != ?");
		$stmt->bind_param("ssssi", $scope, $type, $dkey, $target, $pid);
		$stmt->execute();
		
		if($stmt->get_result()->num_rows > 0){
			http_response_code(409);
			exit(json_encode(["error" => "Conflict"]));
		}

		$query = "INSERT INTO `datastores`(`dkey`, `universeId`, `type`, `scope`, `target`, `value`) VALUES (?, ?, ?, ?, ?, ?)";
		$where = "WHERE `universeId`= ? AND `scope`= ? AND `type`= ? AND `dkey`= ? AND `target`= ?";
		
		$stmt = $con->prepare("SELECT * FROM `datastores` $where");
		$stmt->bind_param("issss", $pid, $scope, $type, $dkey, $target);
		$stmt->execute();

		$stmt_result = $stmt->get_result();
		if($stmt_result->num_rows > 0){
			$existingRecord = $stmt_result->fetch_assoc();
			
			if (intval($existingRecord['universeId']) == $pid) { // well that's pretty bad.. i gotta make something soon
				$query = "UPDATE `datastores` SET `value` = ? $where";
			} else {
				http_response_code(405);
				exit(json_encode(["error"=>"Method Not Allowed"]));
			}
		}
		
		$stmt = $con->prepare($query);
		$stmt->bind_param("siissss",  $_POST["value"], $pid, $scope, $type, $dkey, $target);
		$stmt->execute();
		
		$values = [array("Value"=>$_POST["value"],"Scope"=>$scope,"Key"=>$key,"Target"=>$target)];
		
		exit(json_encode(["data"=>$_POST["value"]], JSON_NUMERIC_CHECK));
	}

exit(json_encode(["error"=>"Method Not Allowed"]));
?>