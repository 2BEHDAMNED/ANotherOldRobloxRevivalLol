<?php
	header("Content-Type: application/json");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/transactionutils.php";

	$user = UserUtils::RetrieveUser();

	if($user != null) {
		if(!$user->IsBanned() && $user->PendingStipend()) {
			TransactionUtils::StipendCheckToUser($user->id);
			die(json_encode(["error" => false, "reason" => "Successfully given!"]));
		} else {
			die(json_encode(["error" => true, "reason" => "Haven't you already gotten this?"]));
		}
	} else {
		die(json_encode(["error" => true, "reason" => "User not logged in."]));
	}
	
?>