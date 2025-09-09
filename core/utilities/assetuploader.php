<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	class AssetUploader {

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

		private static function UploadAsset(AssetType $type, mixed $file): array {
			

			return ["error" => false, "id" => 0];
		}

		private static function UploadTShirt(array $files) {
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