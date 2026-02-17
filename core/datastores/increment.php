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
	if ($_SERVER['SERVER_NAME'] != "gamepersistence" && $_SERVER['SERVER_NAME'] != "persistence") {
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

		$query = "INSERT INTO `datastores`(`id`, `dkey`, `universeId`, `type`, `scope`, `target`, `value`) VALUES (NULL,:dkey,:pid,:type,:scope,:target,:val)";
		$queryChanged=false;
		
		$where = "WHERE `universeId`=:pid AND `scope`=:scope AND `type`=:type AND `dkey`=:dkey AND `target`=:target";
	
		$stmt = $conn->prepare("SELECT * FROM `datastores` WHERE `scope`=:scope AND `type`=:type AND `dkey`=:dkey AND `target`=:target AND `universeId` != :pid");
		$stmt->bindParam(':dkey', $key, PDO::PARAM_STR); 
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT); 
		$stmt->bindParam(':scope', $scope, PDO::PARAM_STR); 
		$stmt->bindParam(':type', $type, PDO::PARAM_STR); 
		$stmt->bindParam(':target', $target, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $conn->prepare("SELECT * FROM `datastores` $where");
		$stmt->bindParam(':dkey', $key, PDO::PARAM_STR); 
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT); 
		$stmt->bindParam(':scope', $scope, PDO::PARAM_STR); 
		$stmt->bindParam(':type', $type, PDO::PARAM_STR); 
		$stmt->bindParam(':target', $target, PDO::PARAM_STR);
		if($stmt->rowCount() > 0){
			$existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if ($existingRecord['universeId'] == $pid) {
				$query = "UPDATE `datastores` SET `value`= value + :val $where";
			} else {
				http_response_code(405);
				exit(json_encode(["error"=>"Method Not Allowed"]));
			}
		}
		
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':dkey', $key, PDO::PARAM_STR); 
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT); 
		$stmt->bindParam(':scope', $scope, PDO::PARAM_STR); 
		$stmt->bindParam(':type', $type, PDO::PARAM_STR); 
		$stmt->bindParam(':target', $target, PDO::PARAM_STR);
		$stmt->bindParam(':val', $_GET["value"], PDO::PARAM_INT);	
		$stmt->execute();
		
		$stmt = $conn->prepare("SELECT value FROM `datastores` $where");
		$stmt->bindParam(':dkey', $key, PDO::PARAM_STR); 
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT); 
		$stmt->bindParam(':scope', $scope, PDO::PARAM_STR); 
		$stmt->bindParam(':type', $type, PDO::PARAM_STR); 
		$stmt->bindParam(':target', $target, PDO::PARAM_STR);
		$stmt->execute();
		$value = $stmt->fetchColumn();
		
		$values = [array("Value"=>$value,"Scope"=>$scope,"Key"=>$key,"Target"=>$target)];
		
		exit(json_encode(["data"=>$values], JSON_NUMERIC_CHECK));
	}

	exit(json_encode(["error"=>"Method Not Allowed"]));
?>