<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/imageutils.php";

	$user = UserUtils::RetrieveUser();

	if(isset($_GET['id'])) {
		$id = intval($_GET['id']);

		$specialcase = false;

		$nocompress = isset($_GET['nocompress']);

		$asset = Asset::FromID($id);
		if($asset != null) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

				
			$stmt = $con->prepare('SELECT * FROM `assetversions` WHERE `version_assetid` = ? ORDER BY `version_id` DESC');
			$stmt->bind_param('i', $id);
			$stmt->execute();

			$stmt_result = $stmt->get_result();

			if($stmt_result->num_rows == 0 && $asset->type == AssetType::PLACE) {
				$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/noassets.png");
			} else {
				$md5hash = $stmt_result->fetch_assoc()['version_md5thumb'];

				if($md5hash == "sound" && $asset->type == AssetType::AUDIO) {
					$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/audio.png");
				} else if($asset->type == AssetType::LUA) {
					$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/script.png");
				} else if($asset->type == AssetType::ANIMATION) {
					$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/animation.png");
				} else if($md5hash == "placeholder" || ($asset->type == AssetType::PLACE && Place::FromID($asset->id) != null && !Place::FromID($asset->id)->IsUsable())) {
					$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/noassets.png");
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
				
				if($asset->type == AssetType::FACE) {
					// whatever lmfao
					$sizeoffsetfactor = 15 * ((420-($size == 420 ? 0 : $size))/420);
					imagefilledrectangle($resizedimage, $sizeoffsetfactor, $sizeoffsetfactor, $size-$sizeoffsetfactor, $size-$sizeoffsetfactor, 0xafafaf);
				}

				imagecopyresampled($resizedimage, $image, 0, 0, 0, 0, $size, $size, $width, $height);
				imagesavealpha($resizedimage, true);
				
				ob_clean();
				if(!$nocompress) {
					header("Content-Type: image/webp");
					ob_start("ob_gzhandler");
					header("Content-Encoding: gzip");
					imagewebp($resizedimage, null, 35);
					ob_end_flush();
				} else {
					header("Content-Type: image/png");
					imagepng($resizedimage, null, 9);
				}
				
				
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
				$width = imagesx($image);
				$height = imagesy($image);

				if($width != $height && $asset->type != AssetType::PLACE) {
					if($width > $height) {
						$cropSize = $height;
					}

					if($width < $height) {
						$cropSize = $width;
					}

					$image = ImageUtils::cropAlign($image,$cropSize, $cropSize);
					$width = $cropSize;
					$height = $cropSize;
				}

				imagesavealpha($image, true);

				$resizedimage = imagecreatetruecolor($sizex, $sizey);
				imagesavealpha($resizedimage, true);
				$trans_colour = imagecolorallocatealpha($resizedimage, 0, 0, 0, 127);
				imagefill($resizedimage, 0, 0, $trans_colour);
				imagecopyresampled($resizedimage, $image, 0, 0, 0, 0, $sizex, $sizey, $width, $height);

				ob_clean();
				if(!$nocompress) {
					header("Content-Type: image/webp");
					ob_start("ob_gzhandler");
					header("Content-Encoding: gzip");
					imagewebp($resizedimage, null, 35);
					ob_end_flush();
				} else {
					header("Content-Type: image/png");
					imagepng($resizedimage, null, 9);
				}
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
