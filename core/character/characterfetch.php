<?php 
    if(isset($_GET['assetId']) && isset($_GET['access'])): ?>
http://arl.lambda.cam/Asset/BodyColors.ashx?clothing;http://arl.lambda.cam/asset/?id=<?= $_GET['assetId'] ?>&access=<?= $_GET['access'] ?>
<?php else: 

require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

$userId = intval($_GET['userId']) ?? 1;

$user = User::FromID($userId);

if($user == null) {
    $user = User::FromID(1);
    $userId = 1;
}

$getwearing = $user->GetWearingArray();

$parsedshit= "";

foreach($getwearing as $id) {
    $parsedshit .= ";http://arl.lambda.cam/asset/?id=$id";
}

if(str_ends_with($parsedshit, ";")) {
    $parsedshit = substr($parsedshit, 0, strlen($parsedshit)-1);
}

echo "http://arl.lambda.cam/Asset/BodyColors.ashx?userId=$userId$parsedshit";

endif ?>