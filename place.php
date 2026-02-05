<?php 

$name = $_GET['name'];
$id = intval($_GET['id']);

require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/comment.php";
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
		$is_creator = ($user != null && ($user->id == $asset->creator->id || $user->IsAdmin()));
		$is_favourited = $user != null && $asset->HasUserFavourited($user);

		$user_bought = $user != null && $user->Owns($asset);

		if(
			isset($_POST['ANORRL$Comment$Post$Contents']) &&
			isset($_POST['ANORRL$Comment$Post$Submit']) &&
			$asset->comments_enabled
		) {
			$result = Comment::Post($asset, $_POST['ANORRL$Comment$Post$Contents']);
			$comment_post_error = $result['error'];
		}
		
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
		<link rel="stylesheet" href="/css/new/main.css">
		<link rel="stylesheet" href="/css/new/comments.css?v=1">
		<link rel="stylesheet" href="/css/new/item/item.css">
		<link rel="stylesheet" href="/css/new/item/place.css">

		<meta name="title" content="<?= htmlspecialchars($asset->name, ENT_QUOTES) ?>">
		<meta name="description" content="<?= htmlspecialchars(substr($asset->description, 0, 128), ENT_QUOTES) ?>"><!-- Max 128 chars -->

		<meta property="og:type" content="website">
		<meta property="og:title" content="<?= htmlspecialchars($asset->name, ENT_QUOTES) ?>">
		<meta property="og:description" content="<?= htmlspecialchars(substr($asset->description, 0, 128), ENT_QUOTES) ?>">
		<meta property="og:url" content="https://arl.lambda.cam/<?= $asset->GetURLTitle()?>-place?id=<?= $asset->id ?>">
		<meta property="og:site_name" content="ANORRL">
		<meta property="og:image" content="https://arl.lambda.cam/thumbs/?id=<?= $asset->id ?>">

		<?php if($user == null) {
			die();
			//die(header("Location: /login"));
		}?>
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
			

			hr {
				border-color: #aaa;
			}



		</style>
		<script>
			function ChangeTab(tabName) {
				var tabToGoTo = tabName.toLowerCase();
				$("#InfoHeaders td").each(function() {
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

				$("#InfoHeaders td").click(function() {
					ChangeTab($(this).html());
					return false;
				});
			})
		</script>
		<?php if($user != null && ($user->id == $asset->creator->id || $user->IsAdmin())): ?>
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
								<div style="width:623px;height:350px;position: relative;">
									<img src="/thumbs/?id=<?= $asset->id ?>&sx=623&sy=350">
									<?php if($asset->is_original): ?>
									<div style="position: absolute;left: 0px;bottom: 0px;font-size: 20px;border: 2px solid black;padding: 4px 15px;background: #6b0388;font-family: punk;font-weight: bold;">Original</div>
									<?php endif ?>
								</div>
								
							</div>
							<div id="Information">
								<div id="UserCard">
									<a href="/users/<?= $asset->creator->id ?>/profile"><img src="/thumbs/player?id=<?= $asset->creator->id ?>" style="width: 110px;display:block;margin:0 auto;"></a>
									<div id="AssetInfoStuff" style="margin: 3px 0px;">
										<span>Created by <a href="/users/<?= $asset->creator->id ?>/profile"><?= $asset_creator_name ?></a></span>
										<span><b>Favourited</b>: <?= $favourites_count ?></span>
										<?php if($asset->gears_enabled): ?>
										<span style="font-size: 13px;margin:5px;"><b>Gears enabled!</b></span>
										<?php endif ?>
									</div>
									<hr>
									<button class="PurchaseButton" onclick="ANORRL.PlaceLauncher.LetsJoinAndPlay(<?= $id ?>)">Play</button>
									<!--<div id="NotOnSale">Place is not open for you to join!</div>-->
									<hr>
									<div id="ManageOptions">
										<?php if($is_creator): ?>
										<a href="/edit?id=<?= $asset->id ?>">Edit</a>
										<?php endif ?>
										<?php if($is_creator || !$asset->copylocked): ?>
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
							<table id="InfoHeaders">
								<td>Info</td>
								<td>Badges</td>
								<td>Gamepasses</td>
								<td>Servers</td>
							</table>
							<div id="InfoBox" content="Info" style="display:none">
								<b>Description</b>
								<hr>
								<div id="Description">
									<?= $asset_description ?>
								</div>
								<hr>
								<table id="BigNumbersArea">
									<td id="Detail">
										<b>Created</b>
										<span><?= $asset->created_at->format('d/m/Y H:i'); ?></span>
									</td>
									<td id="Detail">
										<b>Updated</b>
										<span><?= $asset->last_updatetime->format('d/m/Y H:i'); ?></span>
									</td>
									<td id="Detail">
										<b>Visits</b>
										<span><?= $asset->visit_count ?></span>
									</td>
									<td id="Detail">
										<b>Active</b>
										<span><?= $asset->current_playing_count ?></span>
									</td>
									<td id="Detail">
										<b>Server Size</b>
										<span><?= $asset->server_size ?></span>
									</td>
									<td id="Detail">
										<b>Copylocked</b>
										<span><?= $asset->copylocked ? "Yes" : "No" ?></span>
									</td>
									<td id="Detail">
										<b>Year</b>
										<span><?= $asset->year->ordinal() ?></span>
									</td>
								</table>
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

						<?php
							$comments = Comment::GetCommentsOn($asset);
							$com_count = count($comments);
						?>
						<div id="CommentsContainer">
							<h3>Comments (<?= $com_count ?>)</h3>
							<?php if($user != null && $asset->comments_enabled): ?>
							<div id="CommentPostArea">
								<?php if(isset($comment_post_error) && $comment_post_error): ?>
									<div class="Error"><?= $result['reason'] ?></div>
								<?php endif ?>
								<form method="POST">
									<h4 style="margin: 0; letter-spacing: 5px;">Post a comment or something</h4>
									<textarea placeholder="Write a wonderful comment about this place!" name="ANORRL$Comment$Post$Contents" maxlength="256" minlength="4"></textarea>
									<input type="submit" value="Submit!" name="ANORRL$Comment$Post$Submit">
								</form>
							</div>
							<?php endif ?>
							<div id="CommentSection">
								

								<?php if($user == null): ?>
									<div id="CommentsDisabled">You need to be logged in to comment on this item!</div>
								<?php else: ?>
									<?php if($asset->comments_enabled): ?>
									<?php
										if($com_count != 0):
											foreach($comments as $comment) {
												$contents = str_replace(PHP_EOL, "<br>", $comment->contents);
												$user_id = $comment->poster->id;
												$user_name = $comment->poster->name;

												$profileurl = $comment->poster->setprofilepicture ? "profile" : "player";

												$formatted_datetime = $comment->postdate->format("d/m/Y H:i a");

												echo <<<EOT
												<div class="Comment">
													<div id="CommenterAvatar">
														<a href="/users/$user_id/profile">
															<img src="/thumbs/$profileurl?id=$user_id">
														</a>
													</div>
													<div id="CommentPartArea">
														<div id="CommentInfoArea">
															<a href="/users/$user_id/profile">$user_name</a>&nbsp;<span>Posted on $formatted_datetime</span>
														</div>
														<code>$contents</code>
													</div>
													<div style="float: none; clear: both;"></div>
												</div>
												EOT;
											}
										else:
									?>
									<div id="CommentsDisabled">It's pretty empty in here... :<</div>
									<?php endif ?>
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
