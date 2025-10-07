<?php 

$name = $_GET['name'];
$id = intval($_GET['id']);

require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

$asset = Asset::FromID($id);
$user = UserUtils::RetrieveUser();

if($user == null) {
	die(header("Location: /"));
}

if($asset != null) {

	if($asset->notcatalogueable && $asset->type == AssetType::AUDIO) {
		$urlname = $asset->relatedasset->GetURLTitle();
		$id = $asset->relatedasset->id;
		die(header("Location: /$urlname-item?id=$id"));
	}

	$urlname = $asset->GetURLTitle();
	if($asset->GetURLTitle() != $name) {
		if($asset->type == AssetType::PLACE) {
			die(header("Location: /$urlname-place?id=$id"));
		}
		die(header("Location: /$urlname-item?id=$id"));
	} else {
		if($asset->type == AssetType::PLACE) {
			die(header("Location: /$urlname-place?id=$id"));
		}
	}

	if($asset->type == AssetType::AUDIO) {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
		$stmt = $con->prepare('SELECT * FROM `assets` WHERE `asset_relatedid` = ? AND `asset_type` = ?;');
		$type = AssetType::AUDIO->ordinal();
		$stmt->bind_param("ii", $id, $type);
		$stmt->execute();
		$audio_asset_id = $stmt->get_result()->fetch_assoc()['asset_id'];
	}

	$is_creator = ($user != null && ($user->id == $asset->creator->id || $user->IsAdmin()));
	$is_favourited = $user != null && $asset->HasUserFavourited($user);

	$user_bought = $user != null && $user->Owns($asset);

	$favourites_count = $asset->favourites_count . " times";
	if($asset->favourites_count == 1) {
		$favourites_count = $asset->favourites_count . " time";
	}
	$asset_creator_name = $asset->creator->name;

	$asset_description = $asset->description;
	if(trim($asset_description) == "") {
		$asset_description = "<b>Seems like $asset_creator_name hasn't put anything here...</b>";
	} else {
		$asset_description = str_replace(PHP_EOL, "<br>", $asset_description);
	}
} else {
	die(header("Location: /my/stuff"));
}

$rendering_types = [
	AssetType::PLACE,
	AssetType::SHIRT,
	AssetType::PANTS,
	AssetType::MODEL,
	AssetType::HAT,
	AssetType::MESH,
	AssetType::HEAD,
	AssetType::PACKAGE,
	AssetType::LEFTARM,
	AssetType::RIGHTARM,
	AssetType::LEFTLEG,
	AssetType::RIGHTLEG,
];

$header_data = $asset;
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= htmlspecialchars($asset->name, ENT_QUOTES) ?> - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">

		<meta name="title" content="<?= htmlspecialchars($asset->name, ENT_QUOTES) ?>">
		<meta name="description" content="<?= htmlspecialchars(substr($asset->description, 0, 128), ENT_QUOTES) ?>"><!-- Max 128 chars -->

		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/item.js?t=<?= time() ?>"></script>
		<style>
			h1, h2, h3, h4 {
				margin: 0;
			}

			h2 {
				padding: 5px 30px;
			}

			#ItemContainer {
				padding: 10px;
			}

			#ItemContainer #ItemDetails {
				background: #2c2c2c;
				border: 2px solid black;
				padding: 10px;
				display: table;
			}

			#ItemDetails > div {
				display: table-cell;
				vertical-align: top;
			}


			#ItemDetails #Content {
				padding: 5px;
				border: 2px solid black;
				background: #212121;
				text-align: center;
			}

			#ItemDetails #Content audio {
				width: 229px;border:2px solid black;
			}

			#ItemDetails #Information {
				width: 338px;
			}

			#ItemDetails #Information #UserCard {
				display: table;
				border: 2px solid black;
				background: #222;
				margin-left: 10px;
				padding: 10px;
				width: 309px;
			}

			#ItemDetails #Information #UserCard #AssetInfoStuff {
				display: inline-block;
				vertical-align: top;
				margin-top: 15px;
				
			}

			#ItemDetails #Information #UserCard span{
				display: block;
				vertical-align: top;
				
			}

			#ItemDetails #Information #ItemDescription {
				border: 2px solid black;
				background: #212121;
				padding: 10px;
				margin-top: 10px;
				margin-left: 10px;
				height: 96px;
				overflow: auto;
			}

			#ItemDetails #Purchasing {
				display: inline-block;
				border: 2px solid black;
				background: #212121;
				padding: 10px;
				margin-left: 10px;
				text-align: center;
			}

			#ItemDetails #Purchasing #NotOnSale {
				border: 2px solid gray;
				font-weight: bold;
				font-style: italic;
				color: lightgray;
				padding: 15px;
			}

			.PurchaseButton {
				display:block;
				font-size: 14px;
				padding: 7px 50px;
				text-align: center;
				margin-bottom: 5px;
				color: white;
				background: #e5962eff;
				border: 2px solid #666;
				width: 220px;
				
			}

			hr {
				border-color: #aaa;
			}
			

			.PurchaseButton:hover {
				background: #e4b139ff;
				cursor: pointer;
			}

			.PurchaseButton img {
				width: 20px;
				image-rendering: pixelated;
				margin-bottom: -5px;
			}

			#ManageOptions {

			}

			#ManageOptions a {
				display: block;
				padding: 5px;
				width: 210px;
			}

			#CommentsContainer {
				margin-top: 10px;
			}

			#CommentsContainer #CommentSection {
				padding: 5px;
				background: #222;
				border: 2px solid black;
			}

			#ItemContainer .FavouriteButton {
				width: 32px;
				height: 32px;
				margin-bottom: -7px;
				margin-left: -16px;
				margin-right: 10px;
				background-image: url("/images/favourite_star.gif");
				background-size: 32px;
				display: inline-block;
			}

			#ItemContainer .FavouriteButton[favourited=true]{
				background-image: url("/images/favourited_star.gif");
			}

			#ItemContainer .FavouriteButton[favourited=true]:hover {
				background-image: url("/images/favourited_hover_star.gif");
			}

			#ItemContainer .FavouriteButton:hover {
				background-image: url("/images/favourite_hover_star.gif");
			}

			#CommentsContainer #CommentSection #CommentsDisabled {
				text-align: center;
				padding: 20px;
				font-size: 14px;
			}

			#PurchasePanel {
				position: absolute;
				width: 100vw;
				height: 100vh;
				background: #000a;
				z-index: 999;
				margin-top: -55px;
			}

			#PurchasePanel #ModalPopup {
				width: 360px;
				height: fit-content;
				border: 3px gray solid;
				background: #333;
				inset: 0;
				position: absolute;
				margin: auto;
				padding: 10px;
				color: white;
			}

			#PurchasePanel #ModalPopup h3 {
				font-family:Arial, Helvetica, sans-serif;
				background: none;
				padding: 0;
				margin: 10px 0px;
				margin-top: 2px;
				font-weight: bold;
			}

			#PurchasePanel #ModalPopup .MediumButton {
				background: #777;
				border: 2px solid black;
				padding: 5px 0px;
				color: white;
			}

			#PurchasePanel #ModalPopup .MediumButton:hover {
				background: #999;
				border-color: white;
				cursor: pointer;
			}
		</style>
		<?php if($user != null && $user->IsAdmin()): ?>
		<script>
			function Render() {
				$.post( "/Admin/components/assetstuff", { id: <?= $asset->id ?>, type: "render" }).done(function( data ) {
					window.location.reload();
				});
			}
		</script>
		<?php endif ?>
	</head>
	<body>
		<?php if($asset->onsale): ?>
		<div id="PurchasePanel" style="display: none">
			<div id="ModalPopup" data-assetid="<?= $asset->id ?>">
				<h3>Purchase this <?= strtolower($asset->type->label()) ?>??</h3>
				<?php if($asset->cost_lights == 0 && $asset->cost_cones == 0): ?>
				<div id="PurchaseFreeItem">
					<p>
						The item "<?= $asset->name ?>" from <a target="__blank" href="/users/<?= $asset->creator->id ?>/profile"><?= $asset->creator->name ?></a> is available in the <b><i>Public Domain</i></b>. 
					</p>
					<p>
						Would you like to add it to your inventory for <b><i>free</i></b>?
					</p>
					<p>
						<input type="submit" value="Add it now!" onclick="ANORRL.Item.Purchasing.PurchaseItem(); return false;" class="MediumButton" style="width:100%;" />
					</p>
					<p>
						<input type="submit" value="Cancel" onclick="ANORRL.Item.Purchasing.ClosePurchasePanel(); return false;" class="MediumButton" style="width:100%;" />
					</p>
				</div>
				<?php else: ?>
				<?php if($asset->cost_cones != 0): ?>
				<div id="PurchaseCones" style="display: none;">
					<p>
						Would you like to purchase "<?= $asset->name ?>" from <a target="__blank" href="/users/<?= $asset->creator->id ?>/profile"><?= $asset->creator->name ?></a> for <b><?= $asset->cost_cones ?></b> traffic cones? 
					</p>
					<p>
						Your balance after this transaction will be Traffic Cones: <?= $user->GetNetCones() - $asset->cost_cones ?>.
					</p>
					<p>
						<input type="submit" value="Buy now!" onclick="ANORRL.Item.Purchasing.PurchaseItem(); return false;" class="MediumButton" style="width:100%;" />
					</p>
					<p>
						<input type="submit" value="Cancel" onclick="ANORRL.Item.Purchasing.ClosePurchasePanel(); return false;" class="MediumButton" style="width:100%;" />
					</p>
				</div>
				<?php endif ?>
				<?php if($asset->cost_lights != 0): ?>
				<div id="PurchaseLights" style="display: none;">
					<p>
						Would you like to purchase "<?= $asset->name ?>" from <a target="__blank" href="/users/<?= $asset->creator->id ?>/profile"><?= $asset->creator->name ?></a> for <b><?= $asset->cost_lights ?></b> traffic lights? 
					</p>
					<p>
						Your balance after this transaction will be Traffic Lights: <?= $user->GetNetLights() - $asset->cost_lights ?>.
					</p>
					<p>
						<input type="submit" value="Buy now!" onclick="ANORRL.Item.Purchasing.PurchaseItem(); return false;" class="MediumButton" style="width:100%;" />
					</p>
					<p>
						<input type="submit" value="Cancel" onclick="ANORRL.Item.Purchasing.ClosePurchasePanel(); return false;" class="MediumButton" style="width:100%;" />
					</p>
				</div>
				<?php endif ?>
				<?php endif ?>
				<div id="PurchaseProcessing" style="display: none;">
					<p style="text-align: center;margin: 25px;">
						<img src="/images/ProgressIndicator4White.gif" style="margin-bottom: -20px;margin-left: -25px;"> <span style="font-size: 15px;padding-left: 15px;">Processing...</span>
					</p>
				</div>
				<div id="PurchaseError" style="display: none">
					<p>
						Ok wait... There's been an issue... an error...
					</p>
					<p style="font-weight: bold; color: red" id="Error">
						
					</p>
					<p>
						<input type="submit" value="Cancel" onclick="ANORRL.Item.Purchasing.ClosePurchasePanel(); return false;" class="MediumButton" style="width:100%;" />
					</p>
				</div>
				<div id="PurchaseSuccess" style="display: none">
					<p>
						Awesome sauce! You just bought "<?= $asset->name ?>" from <a target="__blank" href="/users/<?= $asset->creator->id ?>/profile"><?= $asset->creator->name ?></a>!
					</p>
					<p>
						<input type="submit" value="Check it out in your inventory!" onclick="window.location.href='/my/stuff#<?= strtolower($asset->type->label()) ?>'; return false;" class="MediumButton" style="width:100%;" />
					</p>
					<p>
						<input type="submit" value="Continue Shopping" onclick="window.location.href='/catalog'; return false;" class="MediumButton" style="width:100%;" />
					</p>
					<p>
						<input type="submit" value="Close" onclick="window.location.reload(); return false;" class="MediumButton" style="width:100%;" />
					</p>
				</div>
			</div>
		</div>
		<?php endif ?>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="ItemContainer">
						<h4>ANORRL <?= $asset->type->label(); ?></h4>
						<h2><?php if($user != null): ?><a class="FavouriteButton" href="#" data-assetid="<?= $asset->id ?>" <?= $is_favourited ? 'favourited="true"' : "" ?>></a><?php endif ?><?= $asset->name ?></h2>
						<div id="ItemDetails">
							<div id="Content">
								<?php if($asset->type == AssetType::AUDIO): ?>
								<img src="/thumbs/?id=<?= $asset->id ?>&sxy=190">
								<audio src="/asset/?id=<?= $audio_asset_id ?>" controls>Your browser does not support HTML5 Audio</audio>
								<?php else: ?>
								<img src="/thumbs/?id=<?= $asset->id ?>&sxy=240">
								<?php endif ?>
							</div>
							<div id="Information">
								<div id="UserCard">
									<a href="/users/<?= $asset->creator->id ?>/profile"><img src="/images/avatar.png" style="width: 100px;"></a>
									<div id="AssetInfoStuff">
										<span>Created by <a href="/users/<?= $asset->creator->id ?>/profile"><?= $asset_creator_name ?></a></span>
										<span><b>Created on</b>: <?= $asset->created_at->format('d/m/Y H:i'); ?></span>
										<span><b>Last updated</b>: <?= $asset->last_updatetime->format('d/m/Y H:i'); ?></span>
										<span><b>Favourited</b>: <?= $favourites_count ?></span>
									</div>
								</div>
								<div id="ItemDescription">
									<?= $asset_description ?>
								</div>
							</div>
							<div id="Purchasing">
								<span>Sales: </span><b><?= $asset->sales_count ?></b><br>
								<hr>
								<?php if($asset->status == AssetStatus::ACCEPTED && $asset->onsale): ?>
									<?php if(!$user_bought): ?>
										<?php if($asset->cost_cones != 0 || $asset->cost_lights != 0): ?>
											<?php if($asset->cost_cones != 0):  ?><button class="PurchaseButton" onclick="ANORRL.Item.Purchasing.OpenPurchasePanel('cones')"><img src="/images/icons/traffic_cone.png"> <span><?= $asset->cost_cones ?></span></button><?php endif ?>
											<?php if($asset->cost_lights != 0): ?><button class="PurchaseButton" onclick="ANORRL.Item.Purchasing.OpenPurchasePanel('lights')"><img src="/images/icons/traffic_light.png"> <span><?= $asset->cost_lights ?></span></button><?php endif ?>
										<?php else: ?>
											<button class="PurchaseButton" onclick="ANORRL.Item.Purchasing.OpenPurchasePanel()"><span>Free for grabs!</span></button>
										<?php endif ?>
									<?php else: ?>
										<div id="NotOnSale">Hey! You already own this item??</div>
									<?php endif ?>
								<?php else: ?>
									<?php if($user_bought): ?>
										<div id="NotOnSale">Item not on sale and besides you own this.</div>
									<?php else: ?>
										<div id="NotOnSale">Item not on sale.</div>
									<?php endif ?>
								<?php endif ?>
								<hr>
								<div id="ManageOptions">
									<?php if($is_creator): ?>
									<a href="/edit?id=<?= $asset->id ?>">Edit</a>
									<?php endif ?>
									<?php if($user != null && $user->IsAdmin() && in_array($asset->type, $rendering_types)): ?>
									<a href="javascript:Render()">Render this asset</a>
									<?php endif ?>
									<a href="">Report this item</a>
								</div>
							</div>
						</div>

						<div id="CommentsContainer">
							<h3>Comments</h3>
							<div id="CommentSection">
								<?php if(!$asset->comments_enabled): ?>
								<div id="CommentsDisabled">Comments have been disabled for this item.</div>
								<?php else: ?>
								<div id="CommentsDisabled">Comments have not been implemented yet... (sorry :[)</div>
								<?php endif ?>
							</div>
						</div>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>