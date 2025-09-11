<?php 

	include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/asset.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/utilities/userutils.php";

	$directory = $_SERVER['DOCUMENT_ROOT'];
	$assetsdir = "$directory/../assets/";

	header("Content-Type: text/plain");

	

	CheckAndDeleteAsset(6);
?>