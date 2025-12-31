<?php
header('Content-type: application/json');
if(isset($_GET['productId'])) {
	$assetid = intval($_GET['productId']);
} else if(isset($_GET['assetId'])) {
	$assetid = intval($_GET['assetId']);
} else {
	die();
}
?>
{
	"TargetId": <?php echo($assetid); ?>,
	"ProductType": "User Product",
	"AssetId": <?php echo($assetid); ?>,
	"ProductId": <?php echo($assetid); ?>,
	"Name":"test",
	"Description":"testing product",
	"AssetTypeId":9,
	"Creator": {
		"Id": 1,
		"Name": "Roblox",
		"CreatorType": "User",
		"CreatorTargetId": 1
	},
	"IconImageAssetId": <?php echo($assetid); ?>,
	"Created": "2007-05-30T07:05:24.057Z",
	"Updated": "2013-08-06T17:49:26.167Z",
	"PriceInRobux": 0,
	"PremiumPriceInRobux": 0,
	"PriceInTickets": null,
	"IsNew": false,
	"IsForSale": false,
	"IsPublicDomain": true,
	"IsLimited": false,
	"IsLimitedUnique": false,
	"Remaining": null,
	"Sales": null,
	"MinimumMembershipLevel": 0
}