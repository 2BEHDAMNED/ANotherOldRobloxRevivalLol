<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();

	if(isset($_GET['id']) || isset($_GET['userId'])) {
		if(isset($_GET['id'])) {
			$id = intval($_GET['id']);
		} else {
			$id = intval($_GET['userId']);
		}
		

		$specialcase = false;

		$asset = User::FromID($id);
		if($asset != null) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			
			if(file_exists($_SERVER['DOCUMENT_ROOT']."/../users/$id.png")) {
				$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../users/$id.png");
			} else {
				$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/avatar.png");
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

				$image = imagescale($image, $size, $size);
				imagesavealpha($image, true);
				header("Content-Type: image/png");
				ob_clean();
				imagepng($image);
				
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
				$image = imagescale($image, $sizex, $sizey);
				imagesavealpha($image, true);
				
				header("Content-Type: image/png");
				ob_clean();
				imagepng($image);
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