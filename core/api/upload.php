<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";

	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	$access = $settings['asset']['ACCESSKEY'];
	if(
		isset($_POST['data']) &&
		isset($_POST['access']) &&
		isset($_POST['coolassetid']) &&
		$_POST['access'] == $access
	) {
		$asset = Asset::FromID(intval($_POST['coolassetid']));

		if($asset != null) {

			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt = $con->prepare('SELECT * FROM `assetversions` WHERE `version_assetid` = ? ORDER BY `version_id` DESC');
			$stmt->bind_param('i', $asset->id);
			$stmt->execute();

			$stmt_result = $stmt->get_result();

			$md5hash = $stmt_result->fetch_assoc()['version_md5thumb'];

			$mediadir = $_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$md5hash";

			$data = ($_POST['data']);
			$data = str_replace(" ", "%20", $data);

			$data = "data:image/png;base64,$data";
			echo $data;
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);

			$render_image = imagecreatefromstring($data);
			imagesavealpha($render_image, true);
			imagepng($render_image, $mediadir);
		}
	}

?>