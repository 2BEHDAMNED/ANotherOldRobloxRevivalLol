<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	class AssetUploader {

		private static string $assetsdir = $_SERVER['DOCUMENT_ROOT']."/../assets/";

		private static function GetMD5OfData(mixed $data) {
			return md5($data);
		}

		private static function GetFileAmountsForAssetType(AssetType $type): int {

			switch($type) {
				case AssetType::TSHIRT:
					return 2;
				case AssetType::AUDIO:
					return 2;
				case AssetType::MESH:
					return 1;
				case AssetType::LUA:
					return 1;
				case AssetType::HAT:
					return 1;
				case AssetType::PLACE:
					return 1;
				case AssetType::MODEL:
					return 1;
				case AssetType::SHIRT:
					return 2;
				case AssetType::PANTS:
					return 2;
				case AssetType::DECAL:
					return 2;
				
				case AssetType::BADGE:
					return 1;
				
				default:
					return 0;
			}
		}


		private static function GrabVersionOfAsset(int $id) {}

		private static function UploadAsset(int $relatedid = -1, User $user, AssetType $type, string $name, string $description, bool $public, bool $hidden_ahh, mixed $file): array {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$md5 = self::GetMD5OfData($file);
			$filepath = self::$assetsdir.$md5;
			if(!file_exists($filepath)) {
				file_put_contents($filepath, $file);
			}

			// insert asset and version
			if($relatedid != -1) {
				$stmt = $con->prepare('INSERT INTO `assets`(`asset_creator`, `asset_type`, `asset_name`, `asset_description`, `asset_public`, `asset_related`, `asset_nevershow`) VALUES (?, ?, ?, ?, ?);');
				$stmt->bind_param('', $user->id, $type->ordinal(), $name, $description, intval($public), intval($hidden_ahh);
			}
		
			return ["error" => false, "id" => 0];
		}

		private static function UploadDecal(array $files) {
			$user = UserUtils::RetrieveUser();

			if($user != null && !$user->IsBanned()) {

				// process singular asset
				$image_result = self::UploadAsset(AssetType::IMAGE, $files['...']);
				if($image_result['error']) {
					return $image_result;
				} else {
					$image_id = $image_result['id'];
					$decal_data = <<<EOT
					<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://arl.lambda.cam/roblox.xsd" version="4">
						<External>null</External>
						<External>nil</External>
						<Item class="Decal" referent="RBX0">
							<Properties>
								<token name="Face">0</token>
								<string name="Name">Decal</string>
								<float name="Shiny">20</float>
								<float name="Specular">0</float>
								<Content name="Texture">
								<url>http://arl.lambda.cam/asset/?id=$image_id</url>
								</Content>
								<bool name="archivable">true</bool>
							</Properties>
						</Item>
					</roblox>
					EOT;
					$decal_result = self::UploadAsset(AssetType::DECAL, $decal_data);
					if($decal_result['error']) {
						//unlink($image_id);
						return $decal_result;
					}
				}
				
			} else {
				return ["error" => true, "reason" => "User not authorised."];
			}
		}

	}
?>