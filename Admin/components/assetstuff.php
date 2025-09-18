<?php
	include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/asset.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/renderer.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/utilities/userutils.php";
	
	$user = UserUtils::RetrieveUser();

	$directory = $_SERVER['DOCUMENT_ROOT'];
	$assetsdir = "$directory/../assets/";

	function CheckAndDeleteAsset(int $aid) {
		include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
		$asset = Asset::FromID($aid);
		if($asset != null) {
			$stmt = $con->prepare("SELECT * FROM `assets` WHERE `asset_id` = ? OR `asset_relatedid` = ?;");
			$stmt->bind_param("ii", $aid, $aid);
			$stmt->execute();

			$result = $stmt->get_result();

			$ids = [];
			while($row = $result->fetch_assoc()) {
				array_push($ids, $row['asset_id']);
			}

			$md5s = [];

			foreach($ids as $key => $value) {
				$stmt = $con->prepare("SELECT * FROM `assetversions` WHERE `version_assetid` = ? ORDER BY `version_id` DESC;");
				$stmt->bind_param("i", $value);
				$stmt->execute();

				$result = $stmt->get_result();
				if($result->num_rows != 0) {
					$row = $result->fetch_assoc();

					$md5s["$value"] = $row['version_md5sig'];
				}
			}

			foreach($md5s as $key => $value) {
				$stmt = $con->prepare("SELECT * FROM `assetversions` WHERE `version_md5sig` = ? AND `version_assetid` != ? ORDER BY `version_id` DESC;");
				$stmt->bind_param("si", $value, $key);
				$stmt->execute();

				$result = $stmt->get_result();
				if($result->num_rows == 0) {
					$row = $result->fetch_assoc();

					if(file_exists("$assetsdir/$value")){
						unlink("$assetsdir/$value");
					}

					if(file_exists("$assetsdir/thumbs/$value")){
						unlink("$assetsdir/thumbs/$value");
					}
				}
			}
		}
	}

	if($user->IsAdmin()) {
		if(isset($_POST['type'])) {
			if(isset($_POST['id'])) {
				$id = intval($_POST['id']);

				if($_POST['type'] == "render") {
					$asset = Asset::FromID($id);
					$type = $asset->type;

					if($type == AssetType::SHIRT || $type == AssetType::PANTS) {
						$render = TheFuckingRenderer::RenderPlayer($id);	
					} else if($type == AssetType::PLACE) {
						$render = TheFuckingRenderer::RenderPlace($id);
					} else if($type == AssetType::MESH) {
						$render = TheFuckingRenderer::RenderMesh($id);
					}

					$data = base64_decode($render);

					file_put_contents($_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/".AssetVersion::GetLatestVersionOf($asset)->md5thumb, $data);

					$message = "Success!";
				}

				if($_POST['type'] == "accept") {
					$stmt = $con->prepare('UPDATE `assets` SET `asset_status`= ?  WHERE `asset_id` = ? OR `asset_relatedid` = ?');
					$status = AssetStatus::ACCEPTED->ordinal();
					$stmt -> bind_param("iii",  $status, $id, $id);
					$stmt->execute();
					$message = "Success!";
				}  else if($_POST['type'] == "deny") {
					$stmt = $con->prepare('UPDATE `assets` SET `asset_status`= -1  WHERE `asset_id` = ?');
					$stmt -> bind_param("i", $id);
					$stmt->execute();

					$stmt = $con->prepare('UPDATE `assets` SET `asset_name`= "[ Content Deleted ]", `asset_description`= "[ Content Deleted ]" WHERE `asset_id` = ?');
					$stmt -> bind_param("i", $id);
					$stmt->execute();

					CheckAndDeleteAsset($id);
					
					// delete

					$message = "Success!";
				} else if($_POST['type'] == "delete") {
					$stmt = $con->prepare('UPDATE `assets` SET `asset_status`= -1  WHERE `asset_id` = ?');
					$stmt -> bind_param("i", $id);
					$stmt->execute();

					$stmt = $con->prepare('UPDATE `assets` SET `asset_name`= "[ Content Deleted ]", `asset_description`= "[ Content Deleted ]" WHERE `asset_id` = ?');
					$stmt -> bind_param("i", $id);
					$stmt->execute();

					
					
					// delete
					CheckAndDeleteAsset($id);

					$message = "Success!";
				}
			}
		}
	} else {
		$message = "You are not authorised to use this.";
	}

	die($message);
?>