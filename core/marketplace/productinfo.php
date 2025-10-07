<?php
header('Content-type: application/json');
$assetid = $_GET['assetId'];
?>
{
	"TargetId": <?= $assetid ?>,
	"ProductType": "User Product",
	"AssetId": <?= $assetid ?>,
	"ProductId": <?= $assetid ?>,
	"Name": "Test",
	"Description": "HEEELP",
	"AssetTypeId": 9,
	"CreatorId": 1,
	"CreatorName": "TEST TEST HELP!!"
	"IconImageAssetId": 1818,
	"Created": "2015-06-25T20:07:49.147Z",
	"Updated": "2015-07-11T20:07:51.863Z",
	"PriceInRobux": 1,
	"PriceInTickets": 1,
	"Sales": 0,
	"IsNew": true,
	"IsForSale": true,
	"IsPublicDomain": true,
	"IsLimited": false,
	"IsLimitedUnique": false,
	"Remaining": null,
	"MinimumMembershipLevel": 0,
	"ContentRatingTypeId": 0
}