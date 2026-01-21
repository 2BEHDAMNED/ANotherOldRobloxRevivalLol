<?php 

$name = $_GET['name'];
$id = intval($_GET['id']);

require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

$user = UserUtils::RetrieveUser();

$asset = Place::FromID($id);

if(session_start() != PHP_SESSION_ACTIVE) {
	session_start();
}

if($asset != null) {
	$urlname = $asset->GetURLTitle();
	
	if($urlname != $name) {
		die(header("Location: /$urlname-place?id=$id"));
	}

	if($user != null) {
		$is_creator = ($user != null && $user->id == $asset->creator->id) || ($user != null && $user->IsAdmin());
		$is_favourited = $user != null && $asset->HasUserFavourited($user);

		$user_bought = $user != null && $user->Owns($asset);
		unset($_SESSION['ANORRL$Comment$Post$ProfileID']);
		$_SESSION['ANORRL$Comment$Post$AssetID'] = $asset->id;
	}
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

		<meta property="og:type" content="website">
		<meta property="og:title" content="<?= htmlspecialchars($asset->name, ENT_QUOTES) ?>">
		<meta property="og:description" content="<?= htmlspecialchars(substr($asset->description, 0, 128), ENT_QUOTES) ?>">
		<meta property="og:url" content="https://arl.lambda.cam/<?= $asset->GetURLTitle()?>-place?id=<?= $asset->id ?>">
		<meta property="og:site_name" content="ANORRL">
		<meta property="og:image" content="https://arl.lambda.cam/thumbs/?id=<?= $asset->id ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/item.js?t=<?= time() ?>"></script>
		<script src="/js/placelauncher.js?t=<?= time() ?>"></script>
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
				height: 348px;
				padding-top: 15px;
				padding-bottom: 0px;
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
				width: 180px;
				
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

			#ServersBox {
				padding: 10px;
				border: 2px solid black;
				background: #1d1d1d;
			}

			#ServersBox #NoGamesWarning {
				font-size: 14px;
				font-weight: bold;
				text-align: center;
			}

			#ServersBox .Server {
				width: 100%;
				background: #1a1a1a;
				border: 2px solid black;
				
			}

			.Server td { 
				padding: 10px;
			}

			.Server button {
				border: 2px solid black;
				background: black;
				color: white;
				padding: 4px 8px;
				font-weight: bold;
				font-family: punk;
				font-size: 12px
			}

			.Server button:hover {
				text-decoration: underline;
				background: #161616;
				cursor: pointer;
				font-size: 12px
			}

			.Server #PlayersBox {

			}

			.Server #PlayersBox #Player {
				float:left;
				width: 64px;
				height: 64px;
				border: 2px solid #8e8e8e;
				background: #555;
			}

			.Server #PlayersBox #Player img {
				width: 64px;
				height: 64px;
			}

			.Server #JoinBox {
				text-align: center;
				border-left: 2px solid #555;
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
						<?php if($user != null): ?>
						if($(this).attr("content") == "Servers") {
							ANORRL.PlaceLauncher.GrabGameservers(<?= $id ?>);
						}
						<?php endif ?>
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

			function Delete() {
				$.post( "/Admin/components/assetstuff", { id: <?= $asset->id ?>, type: "delete" }).done(function( data ) {
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
							</div>
							<div id="Information">
								<div id="UserCard">
									<a href="/users/<?= $asset->creator->id ?>/profile"><img src="/thumbs/player?id=<?= $asset->creator->id ?>" style="width: 110px;display:block;margin:0 auto;"></a>
									<div id="AssetInfoStuff" style="margin: 3px 0px;">
										<span>Created by <a href="/users/<?= $asset->creator->id ?>/profile"><?= $asset_creator_name ?></a></span>
										<span><b>Favourited</b>: <?= $favourites_count ?></span>
									</div>
									<hr>
									<button class="PurchaseButton" onclick="ANORRL.PlaceLauncher.LetsJoinAndPlay(<?= $id ?>)">Play</button>
									<!--<div id="NotOnSale">Place is not open for you to join!</div>-->
									<hr>
									<div id="ManageOptions">
										<?php if($is_creator || !$asset->copylocked): ?>
										<a href="/edit?id=<?= $asset->id ?>">Edit</a>
										<a href="">Open in Studio</a>
										<?php endif ?>
										<?php if($user != null && $user->IsAdmin()): ?>
										<a href="javascript:Render()">Render this asset</a>
										<?php endif ?>
										<?php if($is_creator): ?>
										<a href="javascript:Delete()">Delete this asset</a>
										<?php endif ?>
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
										<b>Copylocked</b>
										<span><?= $asset->copylocked ? "Yes" : "No" ?></span>
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
								<?php if($user == null): ?>
								<div id="ServersBox">
									<p id="NoGamesWarning">You need to be logged in to see the servers for this game!</p>
								</div>
								<?php else: ?>
								<h3>Servers <button onclick="ANORRL.PlaceLauncher.GrabGameservers(<?= $id ?>);">Refresh</button></h3>
								<div id="ServersBox">
									<p id="NoGamesWarning">There are no servers for this game!</p>
								</div>
								<?php endif ?>
							</div>

						</div>

						<style>
							.Comment {
								padding: 5px;
								border: 2px solid black;
								background: #121212;
							}

							.Comment[other] {
								background: #0a0a0a;
							}

							.Comment > div {
								float: left;
							}

							.Comment #CommenterAvatar > a > img {
								width: 115px;
								border: 2px solid black;
								margin-right: 5px;
								background: #333;
							}

							.Comment #CommentPartArea #CommentInfoArea {
								border-bottom: 1px solid gray;
								padding: 3px;
								padding-top: 0px;
							}

							.Comment #CommentPartArea #CommentInfoArea > a {
								font-weight: bold;
							}
							
							.Comment #CommentPartArea #CommentInfoArea > span {
								font-size: 10px;
								font-style: italic;
								color: #aaa;
							}

							.Comment #CommentPartArea > code {
								margin-top: 5px;
								display: block;
								border: 2px solid black;
								padding: 5px;
								background: #222;
								width: 704px;
								height: 82px;
							}

							#CommentPostArea {
								padding: 5px;
								background: #222;
								border: 2px solid black;
							}

							#CommentPostArea > form > * {
								margin: 5px;
							}

							#CommentPostArea textarea {
								border: 2px solid black;
								display: block;
								width: 830px;
								height: 80px;
								resize: none;
								background: #3a3a3a;
								padding: 5px;
								color: white;
							}

							#CommentPostArea textarea::placeholder {
								color: gray;
								font-style: italic;
							}

							#CommentPostArea input[type="submit"] {
								border: 2px solid black;
								padding: 3px 10px;
								font-family: punk;
								color: white;
								background: black;
								cursor: pointer;
							}

							#CommentPostArea input[type="submit"]:hover {
								background: #222;
							}
						</style>

						<div id="CommentsContainer">
							<h3>Comments (0)</h3>
							<div id="CommentPostArea">
								<form method="POST">
									<h4 style="margin: 0; letter-spacing: 5px;">Post a comment or something</h4>
									<textarea placeholder="Write a wonderful comment about this place!"></textarea>
									<input type="submit" value="Submit!">
								</form>
							</div>
							<div id="CommentSection">
								<div class="Comment">
									<div id="CommenterAvatar">
										<a href="">
											<img src="/thumbs/player?id=1">
										</a>
									</div>
									<div id="CommentPartArea">
										<div id="CommentInfoArea">
											<a href="">Commenter Name</a>&nbsp;<span>Posted on dd/mm/yyyy HH:MM</span>
										</div>
										<code>Contents here</code>
									</div>
									<div style="float: none; clear: both;"></div>
								</div>
								<div class="Comment" other>
									<div id="CommenterAvatar">
										<a href="">
											<img src="/thumbs/player?id=1">
										</a>
									</div>
									<div id="CommentPartArea">
										<div id="CommentInfoArea">
											<a href="">Commenter Name</a>&nbsp;<span>Posted on dd/mm/yyyy HH:MM</span>
										</div>
										<code>Contents here</code>
									</div>
									<div style="float: none; clear: both;"></div>
								</div>
								<?php if($user == null): ?>
									<div id="CommentsDisabled">You need to be logged in to comment on this item!</div>
								<?php else: ?>
									<?php if($asset->comments_enabled): ?>
									<div id="CommentsDisabled">Comments have not been implemented yet... (sorry :[)</div>
									<?php else: ?>
									<div id="CommentsDisabled">Comments have been disabled for this item.</div>
									<?php endif ?>
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
