<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";

    class AssetUtils {
        public static function GetPaged(string $query, AssetType $type, int $pagenum, int $count, User|null $input_user = null) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$page = (($pagenum-1)*$count);
			$q = "%$query%";
			$ordinal = $type->ordinal();

            $userline = $input_user == null ? "" : "AND `asset_creator` = ?";

            $stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_name` LIKE ? AND `asset_type` = ? AND `asset_public` = 1 $userline ORDER BY `asset_created` DESC LIMIT ?, ?");

			if($input_user == null) {
				$stmt_getuser->bind_param('siii', $q, $ordinal, $page, $count);
			} else {
				$stmt_getuser->bind_param('siiii', $q, $ordinal, $input_user->id, $page, $count);
			}

            $stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					$asset = new Asset($row);
					if($row['asset_type'] == AssetType::PLACE->ordinal()) {
						$asset = Place::FromID($row['asset_id']);
					} else {
						$asset = new Asset($row);
					}

					if(!$asset->notcatalogueable && $asset->public) {
						array_push($result_array, $asset);
					}
					
				}
				return $result_array;
			}

			return [];
		}

		public static function Get(string $query, AssetType $type, User|null $input_user = null) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$q = "%$query%";
			$ordinal = $type->ordinal();

			if($input_user == null) {
				$stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_name` LIKE ? AND `asset_type` = ? AND `asset_public` = 1");
				$stmt_getuser->bind_param('si', $q, $ordinal);
				$stmt_getuser->execute();
			} else {
				$stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_name` LIKE ? AND `asset_type` = ? AND `asset_creator` = ? AND `asset_public` = 1");
				$stmt_getuser->bind_param('sii', $q, $ordinal, $input_user->id);
				$stmt_getuser->execute();
			}

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					if($row['asset_type'] == AssetType::PLACE->ordinal()) {
						$asset = Place::FromID($row['asset_id']);
					} else {
						$asset = new Asset($row);
					}

					if(!$asset->notcatalogueable && $asset->public) {
						array_push($result_array, $asset);
					}
				}
				return $result_array;
			}

			return [];
		}
    }
?>