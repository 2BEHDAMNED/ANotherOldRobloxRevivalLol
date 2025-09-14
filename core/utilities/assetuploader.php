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


		private static function GrabVersionOfAsset(int $id) {}

		private static function UploadAsset(User $user, AssetType $type, string $name, string $description, bool $public, bool $hidden_ahh, mixed $file): array {
			
			if($user != null && !$user->IsBanned()) {
				include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
				$md5 = self::GetMD5OfData($file);
				$directory = $_SERVER['DOCUMENT_ROOT'];
				$assetsdir = "$directory/../assets/";
				$filepath = $assetsdir.$md5;
				if(!file_exists($filepath)) {
					file_put_contents($filepath, $file);
				}

				$parsed_userid = $user->id;
				$parsed_type   = $type->ordinal();
				$parsed_public = intval($public);
				$parsed_hidden = intval($hidden_ahh);
				
				$status = AssetStatus::PENDING->ordinal();
				if($user->IsAdmin()) {
					$status = AssetStatus::ACCEPTED->ordinal();
				}

				$stmt = $con->prepare('INSERT INTO `assets`(`asset_creator`, `asset_type`, `asset_name`, `asset_description`, `asset_public`, `asset_nevershow`, `asset_status`) VALUES (?, ?, ?, ?, ?, ?, ?);');
				$stmt->bind_param('iissiii', $parsed_userid, $parsed_type, $name, $description, $parsed_public, $parsed_hidden, $status);
				$stmt->execute();

				$id = $con->insert_id;

				$stmt = $con->prepare('INSERT INTO `assetversions`(`version_assetid`, `version_md5sig`, `version_assettype`) VALUES (?, ?, ?)');
				$stmt->bind_param('isi', $id, $md5, $parsed_type);
				$stmt->execute();
			
				return ["error" => false, "id" => $id];
			} else {
				return ["error" => true, "reason" => "User not authorised."];
			}
			
		}

		private static function CheckMimeType($contents) {
			$file_info = new finfo(FILEINFO_MIME_TYPE);
			return $file_info->buffer($contents);
		} 

		

		public static function UploadDecal(string $name, string $description, array $file, bool $face = false) {
			$user = UserUtils::RetrieveUser();

			if($file['error'] == 0) {
				$original_image = imagecreatefromstring(file_get_contents($file['tmp_name']));
				list($width, $height) = getimagesize($file['tmp_name']);

				$image = imagecreatetruecolor($width, $height);
				$bga = imagecolorallocatealpha($image, 0, 0, 0, 127);
				imagefill($image, 0, 0, $bga);
				imagecopy($image, $original_image, 0, 0, 0, 0, $width, $height);
				imagesavealpha($image, true);

				if($width > $height) {
					$new_width = 420;
					$new_height = -1;
				} else if($width < $height) {
					$new_width = -1;
					$new_height = 420;
				} else {
					$new_width = 420;
					$new_height = 420;
				}

				$resultimage = imagescale($image, $new_width, $new_height);
				imagesavealpha($resultimage, true);

				ob_start();
				imagepng($resultimage);
				$image_data = ob_get_contents();
				ob_end_clean();

				// process singular asset
				$image_result = self::UploadAsset($user, AssetType::IMAGE, $name, "", false, true, $image_data);
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
					$decal_result = self::UploadAsset($user, $face ? AssetType::FACE : AssetType::DECAL, $name, $description, false, false, $decal_data);
					if($decal_result['error']) {
						return $decal_result;
					}

					include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
					require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/transactionutils.php";

					$ta_id = TransactionUtils::GenerateID();
					$ta_assettype = AssetType::IMAGE->ordinal();
					$stmt_processtransaction = $con->prepare("INSERT INTO `transactions`(`ta_id`, `ta_userid`, `ta_currency`, `ta_cost`, `ta_asset`, `ta_assettype`, `ta_assetcreator`, `ta_showsupatall`) VALUES (?, ?, 'none', 0, ?, ?, ?, 0)");
					$stmt_processtransaction->bind_param('siiii', $ta_id, $user->id, $image_id, $ta_assettype, $user->id);
					$stmt_processtransaction->execute();

					$ta_id = TransactionUtils::GenerateID();
					$ta_assettype = ($face ? AssetType::FACE : AssetType::DECAL)->ordinal();
					$stmt_processtransaction = $con->prepare("INSERT INTO `transactions`(`ta_id`, `ta_userid`, `ta_currency`, `ta_cost`, `ta_asset`, `ta_assettype`, `ta_assetcreator`) VALUES (?, ?, 'none', 0, ?, ?, ?)");
					$stmt_processtransaction->bind_param('siiii', $ta_id, $user->id, $decal_result['id'], $ta_assettype, $user->id);
					$stmt_processtransaction->execute();

					$directory = $_SERVER['DOCUMENT_ROOT'];
					$md5hashfile = md5($image_data);
					$assetsdir = "$directory/../assets/thumbs/$md5hashfile";
					imagepng($resultimage, $assetsdir);
					
					$stmt = $con->prepare("UPDATE `assetversions` SET `version_md5thumb` = ? WHERE `version_assetid` = ?");
					$stmt->bind_param('si', $md5hashfile, $image_id);
					$stmt->execute();

					$stmt = $con->prepare("UPDATE `assetversions` SET `version_md5thumb` = ? WHERE `version_assetid` = ?");
					$stmt->bind_param('si', $md5hashfile, $decal_result['id']);
					$stmt->execute();

					$stmt = $con->prepare("UPDATE `assets` SET `asset_relatedid` = ? WHERE `asset_id` = ?;");
					$stmt->bind_param('ii', $decal_result['id'], $image_id);
					$stmt->execute();

					return ["error" => false, "id" => $decal_result['id']];
				}
			} else {
				return ["error" => true, "reason" => "Something wrong occurred when uploading!"];
			}
		}

		public static function UploadAudio(string $name, string $description, array $file) {
			$user = UserUtils::RetrieveUser();

			if($file['error'] == 0) {

				$data = file_get_contents($file['tmp_name']);

				if(self::CheckMimeType($data) != "audio/mpeg") {
					return ["error" => true, "reason" => "Audio file was not mp3!"];
				}

				// process singular asset
				$audio_result = self::UploadAsset($user, AssetType::AUDIO, $name, "", false, true, $data);
				if($audio_result['error']) {
					return $audio_result;
				} else {
					$audio_id = $audio_result['id'];
					$audio_data = <<<EOT
					<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://arl.lambda.cam/roblox.xsd" version="4">
						<External>null</External>
						<External>nil</External>
						<Item class="Sound" referent="RBX0">
							<Properties>
								<bool name="Looped">false</bool>
								<string name="Name">Sound</string>
								<float name="Pitch">1</float>
								<bool name="PlayOnRemove">false</bool>
								<Content name="SoundId"><url>http://arl.lambda.cam/asset/?id=$audio_id</url></Content>
								<float name="Volume">0.5</float>
							</Properties>
						</Item>
					</roblox>
					EOT;
					$audiomodel_result = self::UploadAsset($user, AssetType::AUDIO, $name, $description, false, false, $audio_data);
					if($audiomodel_result['error']) {
						return $audiomodel_result;
					}

					include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
					require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/transactionutils.php";
					$ta_id = TransactionUtils::GenerateID();
					$ta_assettype = AssetType::AUDIO->ordinal();
					$stmt_processtransaction = $con->prepare("INSERT INTO `transactions`(`ta_id`, `ta_userid`, `ta_currency`, `ta_cost`, `ta_asset`, `ta_assettype`, `ta_assetcreator`) VALUES (?, ?, 'none', 0, ?, ?, ?)");
					$stmt_processtransaction->bind_param('siiii', $ta_id, $user->id, $audiomodel_result['id'], $ta_assettype, $user->id);
					$stmt_processtransaction->execute();

					$directory = $_SERVER['DOCUMENT_ROOT'];
					$md5hashfile = "sound";
					
					$stmt = $con->prepare("UPDATE `assetversions` SET `version_md5thumb` = ? WHERE `version_assetid` = ?");
					$stmt->bind_param('si', $md5hashfile, $audio_id);
					$stmt->execute();

					$stmt = $con->prepare("UPDATE `assetversions` SET `version_md5thumb` = ? WHERE `version_assetid` = ?");
					$stmt->bind_param('si', $md5hashfile, $audiomodel_result['id']);
					$stmt->execute();

					$stmt = $con->prepare("UPDATE `assets` SET `asset_relatedid` = ? WHERE `asset_id` = ?;");
					$stmt->bind_param('ii', $audiomodel_result['id'], $audio_id);
					$stmt->execute();

					return ["error" => false, "id" => $audiomodel_result['id']];
				}
			} else {
				return ["error" => true, "reason" => "Something wrong occurred when uploading!"];
			}
		}

		public static function UploadTShirt(string $name, string $description, array $file) {
			$user = UserUtils::RetrieveUser();

			if($file['error'] == 0) {
				$original_image = imagecreatefromstring(file_get_contents($file['tmp_name']));
				list($width, $height) = getimagesize($file['tmp_name']);

				$image = imagecreatetruecolor($width, $height);
				$bga = imagecolorallocatealpha($image, 0, 0, 0, 127);
				imagefill($image, 0, 0, $bga);
				imagecopy($image, $original_image, 0, 0, 0, 0, $width, $height);
				imagesavealpha($image, true);

				if($width > $height) {
					$new_width = 420;
					$new_height = -1;
				} else if($width < $height) {
					$new_width = -1;
					$new_height = 420;
				} else {
					$new_width = 420;
					$new_height = 420;
				}
				
				// calculate resized image
				$r_image = imagescale($image, $new_width, $new_height);
				// get size parameters of scaled image as for easier copying
				$r_width  = imagesx($r_image);
				$r_height = imagesy($r_image);
				
				// if the height is taller than the width then attempt to center it
				if($r_width < $r_height) {
					$dst_x = (420 - $r_width)/2;
				} else {
					$dst_x = 0;
				}
				
				$resizedimage = imagecreatetruecolor(420, 420);
				$trans_colour = imagecolorallocatealpha($resizedimage, 0, 0, 0, 127);
				imagefill($resizedimage, 0, 0, $trans_colour);
				imagecopyresampled($resizedimage, $image, $dst_x, 0, 0, 0, $r_width, $r_height, $width, $height);
				
				
				// create tshirt THUMBNAIL image
				// create base image of size 420x420 with transparent background
				$tshirt = imagecreatetruecolor(420, 420);
				$trans_colour = imagecolorallocatealpha($tshirt, 0, 0, 0, 127);
				imagefill($tshirt, 0, 0, $trans_colour);
				
				// paste tshirt (the icon thing) into image
				$bg_tshirt = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/tshirt.png");
				imagecopy($tshirt, $bg_tshirt, 0, 0, 0, 0, 420, 420);
				// and paste the processed resizedimage on top of it
				imagecopyresampled($tshirt, $resizedimage, 84, 84, 0, 0, 252, 252, 420, 420);
				
				imagesavealpha($resizedimage, true);

				ob_start();
				imagepng($resizedimage);
				$image_data = ob_get_contents();
				ob_end_clean();

				// process singular asset
				$image_result = self::UploadAsset($user, AssetType::IMAGE, $name, "", false, true, $image_data);
				if($image_result['error']) {
					return $image_result;
				} else {
					$image_id = $image_result['id'];
					$tshirt_data = <<<EOT
					<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
						<External>null</External>
						<External>nil</External>
						<Item class="ShirtGraphic" referent="RBX0">
							<Properties>
								<Content name="Graphic">
								<url>http://arl.lambda.cam/asset/?id=$image_id</url>
								</Content>
								<string name="Name">Shirt Graphic</string>
								<bool name="archivable">true</bool>
							</Properties>
						</Item>
					</roblox>
		
					EOT;
					$decal_result = self::UploadAsset($user, AssetType::TSHIRT, $name, $description, false, false, $tshirt_data);
					if($decal_result['error']) {
						return $decal_result;
					}

					include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
					require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/transactionutils.php";
					$ta_id = TransactionUtils::GenerateID();
					$ta_assettype = AssetType::IMAGE->ordinal();
					$stmt_processtransaction = $con->prepare("INSERT INTO `transactions`(`ta_id`, `ta_userid`, `ta_currency`, `ta_cost`, `ta_asset`, `ta_assettype`, `ta_assetcreator`, `ta_showsupatall`) VALUES (?, ?, 'none', 0, ?, ?, ?, 0)");
					$stmt_processtransaction->bind_param('siiii', $ta_id, $user->id, $image_id, $ta_assettype, $user->id);
					$stmt_processtransaction->execute();

					$ta_id = TransactionUtils::GenerateID();
					$ta_assettype = AssetType::TSHIRT->ordinal();
					$stmt_processtransaction = $con->prepare("INSERT INTO `transactions`(`ta_id`, `ta_userid`, `ta_currency`, `ta_cost`, `ta_asset`, `ta_assettype`, `ta_assetcreator`) VALUES (?, ?, 'none', 0, ?, ?, ?)");
					$stmt_processtransaction->bind_param('siiii', $ta_id, $user->id, $decal_result['id'], $ta_assettype, $user->id);
					$stmt_processtransaction->execute();

					$directory = $_SERVER['DOCUMENT_ROOT'];
					$md5hashfile = md5($image_data);
					$assetsdir = "$directory/../assets/thumbs/$md5hashfile";
					imagesavealpha($tshirt, true);
					imagepng($tshirt, $assetsdir);
					
					$stmt = $con->prepare("UPDATE `assetversions` SET `version_md5thumb` = ? WHERE `version_assetid` = ?");
					$stmt->bind_param('si', $md5hashfile, $image_id);
					$stmt->execute();

					$stmt = $con->prepare("UPDATE `assetversions` SET `version_md5thumb` = ? WHERE `version_assetid` = ?");
					$stmt->bind_param('si', $md5hashfile, $decal_result['id']);
					$stmt->execute();

					$stmt = $con->prepare("UPDATE `assets` SET `asset_relatedid` = ? WHERE `asset_id` = ?;");
					$stmt->bind_param('ii', $decal_result['id'], $image_id);
					$stmt->execute();

					return ["error" => false, "id" => $decal_result['id']];
				}
			} else {
				return ["error" => true, "reason" => "Something wrong occurred when uploading!"];
			}
		}

	}
?>