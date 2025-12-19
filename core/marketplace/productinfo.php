<?php
header('Content-type: application/json');
$assetid = $_GET['assetId'];
?>
<?php
header('Content-type: application/json');
$assetid = (int)$_GET['productId'];
if(!$assetid){
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
	"IconImageAssetId": 0,
	"Created": "2007-05-30T07:05:24.057Z",
	"Updated": "2013-08-06T17:49:26.167Z",
	"PriceInRobux": 25,
	"PremiumPriceInRobux": 20,
	"PriceInTickets": null,
	"IsNew": false,
	"IsForSale": true,
	"IsPublicDomain": true,
	"IsLimited": false,
	"IsLimitedUnique": false,
	"Remaining": null,
	"Sales": null,
	"MinimumMembershipLevel": 0
}