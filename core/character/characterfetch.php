<?php 
	$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);

	$access = $settings['asset']['ACCESSKEY'];

    if(isset($_GET['assetId'])): ?>
http://arl.lambda.cam/Asset/BodyColors.ashx?clothing;http://arl.lambda.cam/asset/?id=<?= $_GET['assetId'] ?>&access=<?= $access ?>
<?php else: ?>
http://arl.lambda.cam/Asset/BodyColors.ashx?userId=1
<?php endif ?>