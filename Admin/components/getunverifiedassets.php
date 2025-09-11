<?php
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/asset.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/utilities/userutils.php";
	
	$user = UserUtils::RetrieveUser();

	if($user->IsAdmin()) {
		$message = strval(count(Asset::GetAllUncheckedAssets()));
	} else {
		$message = "You are not authorised to use this.";
	}

	die($message);
?>