<?php 

$name = $_GET['name'];
$id = intval($_GET['id']);

require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

$user = UserUtils::RetrieveUser();

if($user == null) {
	die(header("Location: /"));
}

$asset = Place::FromID($id);

if($asset != null) {
	$urlname = $asset->GetURLTitle();
	
	if($urlname != $name) {
		die(header("Location: /$urlname-place?id=$id"));
	}

	$is_creator = ($user != null && $user->id == $asset->creator->id) || ($user != null && $user->IsAdmin());
	$is_favourited = $user != null && $asset->HasUserFavourited($user);

	$user_bought = $user != null && $user->Owns($asset);

	$favourites_count = $asset->favourites_count . " times";
	if($asset->favourites_count == 1) {
		$favourites_count = $asset->favourites_count . " time";
	}
	$asset_creator_name = $asset->creator->name;

	$asset_description = $asset->description;
	if(trim($asset_description) == "") {
		$asset_description = "<span id='NoDescription'>Seems like $asset_creator_name hasn't put anything here...</span>";
	} else {
		$asset_description = str_replace(PHP_EOL, "<br>", $asset_description);
	}
} else {
	$new_asset = Asset::FromID($id);
	if($new_asset == null) {
		die(header("Location: /my/stuff"));
	} else {
		$urlname = $new_asset->GetURLTitle();
		die(header("Location: /$urlname-item?id=$id"));
	}
	
}
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
		<script src="/js/item.js"></script>
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

			#ItemContainer #PlaceDetails {
				background: #2c2c2c;
				border: 2px solid black;
				padding: 10px;
				display: table;
				width: 845px;
				text-align: center;
			}

			#PlaceDetails > div {
				display: table-cell;
				vertical-align: top;
			}


			#PlaceDetails #Content {
				padding: 5px;
				border: 2px solid black;
				background: #212121;
				text-align: left;
			}

			#PlaceDetails #Content audio {
				width: 229px;
				border:2px solid black;
			}

			#PlaceDetails #Information {
				width: 338px;
			}

			#PlaceDetails #Information #UserCard {
				display: table;
				border: 2px solid black;
				background: #222;
				margin-left: 10px;
				padding: 10px;
			}

			#PlaceDetails #Information #UserCard #AssetInfoStuff {
				display: inline-block;
				vertical-align: top;
				margin-bottom: 15px;
				width: 100%;
			}

			#PlaceDetails #Information #UserCard span{
				display: block;
				vertical-align: top;
				
			}

			#PlaceInfoArea #NoDescription {
				font-style: italic;
				color: lightgray;
			}

			#PlaceInfoArea #Description {
				padding: 10px;
			}

			#PlaceDetails #UserCard #NotOnSale {
				border: 2px solid gray;
				font-weight: bold;
				font-style: italic;
				color: lightgray;
				padding: 10px;
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
			}

			.PurchaseButton img {
				width: 20px;
				image-rendering: pixelated;
				margin-bottom: -5px;
			}

			#ManageOptions a {
				display: block;
				padding: 5px 0px;
				width: 100%;
			}

			#PlaceInfoArea {
				margin-top: 10px;
				background: #222;
				border: 2px solid black;
			}

			#PlaceInfoArea #InfoHeaders {
				background: #000;
				font-size: 16px;
				font-family: punk;
				border-bottom: 2px solid black;
			}

			#PlaceInfoArea #InfoHeaders a {
				color: white;
				width: 193px;
				display: inline-block;
				text-align: center;
				padding: 10px;
			}

			#PlaceInfoArea #InfoHeaders a:hover,
			#PlaceInfoArea #InfoHeaders a[selected] {
				background: #2b2b2b;
				text-decoration: underline;
			}

			#PlaceInfoArea #InfoBox {
				padding: 10px;
			}

			#CommentsContainer {
				margin-top: 10px;
			}

			#CommentsContainer #CommentSection {
				padding: 5px;
				background: #222;
				border: 2px solid black;
			}

			#BigNumbersArea {
				width: 100%;
			}
		
			#BigNumbersArea #Detail {
				display: inline-block;
				text-align: center;
				padding: 5px 15px;
				width: 108px;
			}

			#BigNumbersArea #Detail * {
				display:block;
			}
		</style>
		<script>
			function ChangeTab(tabName) {
				var tabToGoTo = tabName.toLowerCase();
				$("#InfoHeaders a").each(function() {
					if($(this).html().toLowerCase() != tabToGoTo) {
						$(this).removeAttr("selected");
					} else {
						$(this).attr("selected", "true");
					}
				});

				$("#InfoBox[content]").each(function() {
					if($(this).attr("content").toLowerCase() != tabToGoTo) {
						$(this).css("display", "none");
					} else {
						$(this).css("display", "block");
					}
				});

				ANORRL.ChangeUrl("", window.location.pathname+window.location.search+"#"+tabToGoTo);
			}

			$(function() {

				var tab = window.location.hash != "" ? window.location.hash.replace("#", "") : "info";
				//alert(tab);
				ChangeTab(tab);

				$("#InfoHeaders a").click(function() {
					ChangeTab($(this).html());
					return false;
				});
			})
		</script>
		<?php if($user != null && $user->IsAdmin()): ?>
		<script>
			var rendering = false;
			function Render() {
				if(rendering) {
					return;
				}

				rendering = true;
				$("#RenderButton").html("Rendering...");
				$.post( "/Admin/components/assetstuff", { id: <?= $asset->id ?>, type: "render" }).done(function( data ) {
					window.location.reload();
				});
			}
		</script>
		<?php endif ?>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="ItemContainer">
						<h4>ANORRL <?= $asset->type->label(); ?></h4>
						<h2><?php if($user != null): ?><a class="FavouriteButton" href="#" data-assetid="<?= $asset->id ?>" <?= $is_favourited ? 'favourited="true"' : "" ?>></a><?php endif ?><?= $asset->name ?></h2>
						<div id="PlaceDetails">
							<div id="Content">
								<img src="/thumbs/?id=<?= $asset->id ?>&sx=623&sy=350">
								<span>Allowed gears:</span> <span>(icons here)</span>
							</div>
							<div id="Information">
								<div id="UserCard">
									<a href="/users/<?= $asset->creator->id ?>/profile"><img src="/images/avatar.png" style="width: 110px;display:block;margin:0 auto;"></a>
									<div id="AssetInfoStuff" style="margin: 3px 0px;">
										<span>Created by <a href="/users/<?= $asset->creator->id ?>/profile"><?= $asset_creator_name ?></a></span>
										<span><b>Favourited</b>: <?= $favourites_count ?></span>
									</div>
									<hr>
									<div id="AssetInfoStuff" style="margin: 3px 0px;">
										<span><b>Copylocked:</b> <?= $asset->copylocked ? "Yes" : "No" ?></span>
										<span><b>Friends-Only:</b> <?= $asset->public ? "No" : "Yes" ?></span>
										<span><b>Chat Type:</b> <?= $asset->chattype->label() ?></span>
									</div>
									<hr>
									<div id="NotOnSale">Place is not open for you to join!</div>
									<hr>
									<div id="ManageOptions">
										<?php if($is_creator || !$asset->copylocked): ?>
										<a href="/edit?id=<?= $asset->id ?>">Edit</a>
										<a href="">Open in Studio</a>
										<?php endif ?>
										<?php if($user != null && $user->IsAdmin()): ?>
										<a href="javascript:Render()" id="RenderButton">Render this asset</a>
										<?php endif ?>
										<a href="">Report this item</a>
									</div>
								</div>
							</div>
						</div>

						<div id="PlaceInfoArea">
							<div id="InfoHeaders">
								<a>Info</a>
								<a>Badges</a>
								<a>Gamepasses</a>
								<a>Servers</a>
							</div>
							<div id="InfoBox" content="Info" style="display:none">
								<b>Description</b>
								<hr>
								<div id="Description">
									<?= $asset_description ?>
								</div>
								<hr>
								<div id="BigNumbersArea">
									<div id="Detail">
										<b>Created</b>
										<span><?= $asset->created_at->format('d/m/Y H:i'); ?></span>
									</div>
									<div id="Detail">
										<b>Updated</b>
										<span><?= $asset->last_updatetime->format('d/m/Y H:i'); ?></span>
									</div>
									<div id="Detail">
										<b>Visits</b>
										<span><?= $asset->visit_count ?></span>
									</div>
									<div id="Detail">
										<b>Active</b>
										<span><?= $asset->current_playing_count ?></span>
									</div>
									<div id="Detail">
										<b>Server Size</b>
										<span><?= $asset->server_size ?></span>
									</div>
									<div id="Detail">
										<b>Genre</b>
										<span><?= $asset->genre->label() ?></span>
									</div>
								</div>
							</div>
							<div id="InfoBox" content="Badges" style="display:none">
								<b>Badges</b><br>
								Badges content in here
							</div>
							<div id="InfoBox" content="Gamepasses" style="display:none">
								<b>Gamepasses</b><br>
								Gamepasses content in here
							</div>
							<div id="InfoBox" content="Servers" style="display:none">
								<b>Servers</b><br>
								Servers content in here
							</div>

						</div>

						<div id="CommentsContainer">
							<h3>Comments</h3>
							<div id="CommentSection">
								<?php if($asset->comments_enabled): ?>
								<div id="CommentsDisabled">Comments have not been implemented yet... (sorry :[)</div>
								<?php else: ?>
								<div id="CommentsDisabled">Comments have been disabled for this item.</div>
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