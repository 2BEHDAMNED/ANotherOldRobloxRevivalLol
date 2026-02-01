<?php

	// Width=60&Height=62&ImageFormat=png&AssetID=8
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/imageutils.php";
	

	if(isset($_GET['format']) && $_GET['format'] == "png") {
		
		if(isset($_GET['assetId'])) {
			$id = intval($_GET['assetId']);

			$specialcase = false;

			$asset = Asset::FromID($id);
			if($asset != null) {
				include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

					
				$stmt = $con->prepare('SELECT * FROM `assetversions` WHERE `version_assetid` = ? ORDER BY `version_id` DESC');
				$stmt->bind_param('i', $id);
				$stmt->execute();

				$stmt_result = $stmt->get_result();

				$md5hash = $stmt_result->fetch_assoc()['version_md5thumb'];

				if($md5hash == "sound" && $asset->type == AssetType::AUDIO) {
					$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/audio.png");
				} else if($md5hash == "script" && $asset->type == AssetType::LUA) {
					$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/script.png");
				} else if($md5hash == "animation" && $asset->type == AssetType::ANIMATION) {
					$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/animation.png");
				} else {
					if($asset->relatedasset != null || $asset->type == AssetType::IMAGE) {
						if(file_exists($_SERVER['DOCUMENT_ROOT']."/../assets/$md5hash")) {
							$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../assets/$md5hash");
							$specialcase = true;
						} else {
							$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg");
						}
					} else {
						if(file_exists($_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$id")) {
							$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$id");
						}
						else if(file_exists($_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$md5hash")) {
							$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$md5hash");
						}
						else {
							$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg");
						}
					}
					
				}

				ob_clean();
				
				if(isset($_GET['width']) && isset($_GET['height'])) {
					$sizex = intval($_GET['width']);
					if($sizex < 16 || $sizex > 1080) {
						$sizex = 420;
					}

					$sizey = intval($_GET['height']);
					if($sizey < 16 || $sizey > 1080) {
						$sizey = 420;
					}

					$image = imagecreatefromstring($contents);
					imagesavealpha($image, true);
					$width = imagesx($image);
					$height = imagesy($image);

					$resizedimage = imagecreatetruecolor($sizex, $sizey);
					imagesavealpha($resizedimage, true);
					$trans_colour = imagecolorallocatealpha($resizedimage, 0, 0, 0, 127);
					imagefill($resizedimage, 0, 0, $trans_colour);
					imagecopyresampled($resizedimage, $image, 0, 0, 0, 0, $sizex, $sizey, $width, $height);

					
					header("Content-Type: image/png");
					ob_clean();
					imagepng($resizedimage);
				} else {
					$file_info = new finfo(FILEINFO_MIME_TYPE);
					$mime = $file_info->buffer($contents);

					header("Content-Type: $mime");
					ob_clean();
					echo $contents;
				}

				
			}
		}

	} else {
		$width = $_GET['Width'];
		$height = $_GET['Height'];
		$assetid = $_GET['AssetID'];
		echo "/thumbs/?id=$assetid&sx=$width&sy=$height";
	}

	
?>