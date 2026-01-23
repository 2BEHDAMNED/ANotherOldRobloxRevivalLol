<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	if(isset($_GET['id']) || isset($_GET['userId'])) {
		if(isset($_GET['id'])) {
			$id = intval($_GET['id']);
		} else {
			$id = intval($_GET['userId']);
		}
		

		$specialcase = false;

		$user = User::FromID($id);
		if($user != null) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			
			//base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));

			$md5hash = $user->GetCharacterAppearanceHash();

			if(file_exists($_SERVER['DOCUMENT_ROOT']."/../renders/$md5hash.png")) {
				$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../renders/$md5hash.png");
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
				imagesavealpha($image, true);
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