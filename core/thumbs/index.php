<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/imageutils.php";

	$user = UserUtils::RetrieveUser();

	if(isset($_GET['id'])) {
		$id = intval($_GET['id']);

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

			if(isset($_GET['sxy'])) {
				$size = intval($_GET['sxy']);
				if($size < 16 || $size > 420) {
					$size = 420;
				}

				$image = imagecreatefromstring($contents);
				$width = imagesx($image);
				$height = imagesy($image);
				
				// Mostly just used for places in stuff/create pages
				if($width != $height) {
					if($width > $height) {
						$cropSize = $height;
					}

					if($width < $height) {
						$cropSize = $width;
					}

					$image = ImageUtils::cropAlign($image,$cropSize, $cropSize);
				}

				$width = imagesx($image);
				$height = imagesy($image);

				$resizedimage = imagecreatetruecolor($size, $size);
				imagesavealpha($resizedimage, true);
				$trans_colour = imagecolorallocatealpha($resizedimage, 0, 0, 0, 127);
				imagefill($resizedimage, 0, 0, $trans_colour);
				imagecopyresampled($resizedimage, $image, 0, 0, 0, 0, $size, $size, $width, $height);

				imagesavealpha($resizedimage, true);
				header("Content-Type: image/png");
				ob_clean();
				imagepng($resizedimage);
				
			} else if(isset($_GET['sx']) && isset($_GET['sy'])) {
				$sizex = intval($_GET['sx']);
				if($sizex < 16 || $sizex > 1080) {
					$sizex = 420;
				}

				$sizey = intval($_GET['sy']);
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

?>
