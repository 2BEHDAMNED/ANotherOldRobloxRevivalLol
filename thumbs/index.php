<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";

	if(isset($_GET['id'])) {
		$id = intval($_GET['id']);

		$asset = Asset::FromID($id);
		if($asset != null) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

			$stmt = $con->prepare('SELECT * FROM `assetversions` WHERE `version_assetid` = ? ORDER BY `version_id` DESC');
			$stmt->bind_param('i', $id);
			$stmt->execute();

			$stmt_result = $stmt->get_result();

			$md5hash = $stmt_result->fetch_assoc()['version_md5thumb'];

			if($md5hash == "sound") {
				$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/audio.png");
			} else {
				$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$md5hash");
			}

			ob_clean();

			if(isset($_GET['sxy'])) {
				$size = intval($_GET['sxy']);
				if($size < 16 || $size > 420) {
					$size = 420;
				}

				$image = imagecreatefromstring($contents);
				$image = imagescale($image, $size, $size);
				imagesavealpha($image, true);
				header("Content-Type: image/png");
				imagepng($image);
				
			} else if(isset($_GET['sx']) && isset($_GET['sy'])) {
				$sizex = intval($_GET['sx']);
				if($sizex < 16 || $sizex > 420) {
					$sizex = 420;
				}

				$sizey = intval($_GET['sy']);
				if($sizey < 16 || $sizey > 420) {
					$sizey = 420;
				}

				$image = imagecreatefromstring($contents);
				$image = imagescale($image, $sizex, $sizey);
				imagesavealpha($image, true);
				
				header("Content-Type: image/png");
				imagepng($image);
			} else {
				$file_info = new finfo(FILEINFO_MIME_TYPE);
				$mime = $file_info->buffer($contents);

				header("Content-Type: $mime");
				echo $contents;
			}

			
		}
	}

?>