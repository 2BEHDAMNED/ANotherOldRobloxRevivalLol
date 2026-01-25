<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = User::FromID(1);

	if(isset($_GET['userid'])) {
		$user = User::FromID(intval($_GET['userid']));
	}

	if($user == null) {
		$user = User::FromID(1);
	}

	if($user->setprofilepicture) {
		$id = $user->id;
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/../users/profile_$id.png")) {
			$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../users/profile_$id.png");
		} else {
			$pictures = array_diff(scandir($_SERVER['DOCUMENT_ROOT']."/images/profile_pictures/"), array("..", "."));
				
			$rand_pic = 1+rand(0, count($pictures) - 1);
			
			$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/profile_pictures/pfp_$rand_pic.png");
		}
	} else {
		$md5hash = $user->GetCharacterAppearanceHash();

		if(file_exists($_SERVER['DOCUMENT_ROOT']."/../renders/$md5hash.png")) {
			$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../renders/$md5hash.png");
		} else {
			$contents = file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg");
		}	
	}

	ob_clean();

	$file_info = new finfo(FILEINFO_MIME_TYPE);
	$mime = $file_info->buffer($contents);

	header("Content-Type: $mime");
	ob_clean();
	echo $contents;

?>