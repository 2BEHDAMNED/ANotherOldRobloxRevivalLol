<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/classes/comment.php';

	function IsRewrite() {
		if(!empty($_SERVER['IIS_WasUrlRewritten']))
			return true;
		else if(array_key_exists('HTTP_MOD_REWRITE',$_SERVER))
			return true;
		else if( array_key_exists('REDIRECT_URL', $_SERVER))
			return true;
		else
			return false;
	}

	if(!IsRewrite()) {
		die(header("Location: /my/home"));
	}

	// No id parameter? GET OUT!
	if(!isset($_GET['id'])) {
		die(header("Location: /my/home"));
	}

	$get_user = User::FromID(intval($_GET['id']));

	if($get_user == null) {
		die(header("Location: /my/home"));
	}

	if(isset($_GET['redirect']) && $_GET['redirect'] == "true") {
		die(header("Location: /users/".$get_user->id."/profile"));
	}

	
	$user = UserUtils::RetrieveUser($get_user);

	if($user == null) {
		die(header("Location: /login"));
	}

	$header_data = $get_user;

	$games = $get_user->GetAllOwnedAssetsOfType(AssetType::PLACE, false);

	

	if($user != null) {
		if(
			isset($_POST['ANORRL$Comment$Post$Contents']) &&
			isset($_POST['ANORRL$Comment$Post$Submit'])
		) {
			$result = Comment::Post($get_user, $_POST['ANORRL$Comment$Post$Contents']);
			$id = $get_user->id;

			//$comment_post_error = $result['error'];
			//die(header("Location: /user/$id/profile"));
			
		}
	}

	$comments = Comment::GetCommentsOn($get_user);
	$com_count = count($comments);

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $get_user->name ?> - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/new/main.css">
		<link rel="stylesheet" href="/css/new/comments.css">
		<link rel="stylesheet" href="/css/new/stuff.css?v=1">
		<link rel="stylesheet" href="/css/new/my/profile.css?v=2">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/placelauncher.js?t=<?= time() ?>"></script>
		<script src="/js/user.js?t=<?= time() ?>"></script>
		<script>
			$(function(){
				ANORRL.User.GrabFeed(<?= $get_user->id ?>);
			});
		</script>
		<?php if($get_user->setprofilepicture): ?>
		<script>
			var render = false;
			function flipRenders(element) {
				render = !render;

				if(render) {
					$("#ProfilePictureYeah").attr("src", "/thumbs/player?id=<?= $get_user->id ?>");
				} else {
					$("#ProfilePictureYeah").attr("src", "/thumbs/profile?id=<?= $get_user->id ?>");
				}
			}
		</script>
		<?php endif ?>
	</head>
	<body>
		<div class="Badge" template><a href=""><img src=""><span></span></a></div>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="WrapperBody">
				<div id="Body">
					<div id="UserInfoContainer">
						<div id="PaddingContainer">
							<h2 style="margin: 5px 0px; width: 830px;"><?= $get_user->name ?>'s Profile</h2>
							<div id="ProfileImage">
								<div id="ImageContainer">
									<?php if($get_user->setprofilepicture): ?>
									<a href="javascript:flipRenders(this)" style="position: absolute;z-index: 2;bottom: 5px;right: 5px;" title="Swap pictures!"><img src="/images/icons/switch.png" style="width: 30px;image-rendering: pixelated;" title="Swap pictures!"></a>
									<?php endif ?>
									<img id="ProfilePictureYeah" src="/thumbs/<?= $get_user->setprofilepicture ? "profile" : "player" ?>?id=<?= $get_user->id ?>">
								</div>
								
								<div id="Controls">
									<?php if($user != null): ?>
										<?php if($user->id != $get_user->id): ?>
											
											<?php
												$friend_button_label = "Add Friend";
												$follow_label = $user->IsFollowing($get_user) ? "Unfollow" : "Follow";

												if($user->IsFriendsWith($get_user)) {
													$friend_button_label = "Unfriend :[";
												}
												else {
													if($user->IsPendingFriendsReq($get_user)) {
														$friend_button_label = "Cancel Req.";
													} else {
														if($user->IsIncomingFriendsReq($get_user)) {
															$friend_button_label = "Accept Req.";
														}
													}
												}
											?>

											<button style="width: 107px;" onclick="ANORRL.User.Friend(<?= $get_user->id ?>)"><?= $friend_button_label ?></button>
											<button style="width: 70px;margin-left: 2px;" onclick="ANORRL.User.Follow(<?= $get_user->id ?>);"><?= $follow_label ?></button><br>
										<?php else: ?>
										<button style="width: 74px;">It's you.</button>
										<?php endif ?>
									<?php endif ?>
								</div>
							</div>
							<div id="ProfileInfo">
								<div id="Stats">
									<div id="FollowFriendsWhatever">
										<a href="/users/<?= $get_user->id ?>/friends">
											<b id="Numbers"><?= $get_user->GetFriendsCount() ?></b> <span>Friends</span>
										</a> | 
										<a href="/users/<?= $get_user->id ?>/followers">
											<b id="Numbers"><?= $get_user->GetFollowersCount() ?></b> <span>Followers</span>
										</a> | 
										<a href="/users/<?= $get_user->id ?>/following">
											<b id="Numbers"><?= $get_user->GetFollowingCount() ?></b> <span>Following</span>
										</a>
									</div>
									<div id="OnlineStatusArea">
										<?php $profile_status = $get_user->IsOnline() ? "Online" : "Offline"; ?>										
										<span class="<?= $profile_status ?>"><b><?= $profile_status ?></b> - <?= $get_user->GetOnlineActivity() ?></span>

									</div>
									<div id="OnlineStatusArea" style="padding-top:0px; margin-top:-5px;">
										<span><b>Joined</b>: <?= $get_user->join_date->format('F dS, Y') ?></span>
									</div>
									<div id="Blurb">
										<?php 
											if(strlen($get_user->blurb) == 0) {
												echo "<b>This user has no blurb!</b>";
											} else {
												echo str_replace(" ","&nbsp;",str_replace(PHP_EOL, "<br>", $get_user->blurb));
											}
										?>
									</div>
								</div>
							</div>
							<br clear="all">
						</div>
					</div>
					<hr>
					<div id="UserAvatarContainer">
						<h3><?= $get_user->name ?>'s Character</h3>
						<div id="UserAvatarPane">
							<ul id="AvatarItems">
								<?php if(count($get_user->GetWearingArray()) == 0): ?>
								<li>
									<div id="NoItemsOn">
										<?= $get_user->name ?> does not have any items on!
									</div>
								</li>
								<?php else: ?>
								<?php 
									$items = $get_user->GetWearingArray();
									foreach($items as $item) {
										$asset = Asset::FromID($item);

										if($asset instanceof Asset) {
											$asset_id = $asset->id;
											$asset_urlname = $asset->GetURLTitle();
											$asset_name = $asset->name;
											$asset_creator_id = $asset->creator->id;
											$asset_creator_name = $asset->creator->name;
											echo <<<EOT
											<li>
												<div class="Asset">
													<a id="NameAndThumbs" href="/$asset_urlname-item?id=$asset_id">
														<img src="/thumbs/?id=$asset_id">
														<span>$asset_name</span>
													</a>
													<a id="Creator" href="/users/$asset_creator_id/profile"><span>$asset_creator_name</span></a>
												</div>
											</li>
											EOT;
										}
									}
								?>
								
								<?php endif ?>
							</ul>
							<div id="AvatarRender">
								<img src="/thumbs/player?id=<?= $get_user->id ?>&sxy=204">
							</div>
							<br id="Clearer">
						</div>
					</div>
					<?php if(count($games) != 0): ?>
					<hr>
					<div id="UserGamesContainer">
						<h3><?= $get_user->name ?>'s Games</h3>
						<table id="ProfileGamesBox">
							<td class="ProfileGame">
								<table>
									<td id="ShowcaseBigImages">
										<div id="NameAndCreator"><a href="" id="Name">Game Name</a></div>
										<img src="">
										<a id="Play" href="javascript:ANORRL.User.JoinTheGame()" data-placejoinid=""></a>
									</td>
									<td id="ShowcaseDetails">
										<code>
											Description hi hihi
										</code>
									</td>
								</table>
							</td>
							<td id="ProfileGames">
								<div style="height: 265px;overflow-x: hidden;overflow-y: scroll;width:244px;padding: 9px;">
									<?php
										foreach($games as $game) {
											$game_id = $game->id;

											if(!$game->public) {
												continue;
											}

											echo <<<EOT
											<a data-placeid="$game_id"><img src="/thumbs/?id=$game_id&sx=227&sy=128"></a>
											EOT;
										}
									?>
								</div>
							</td>
						</table>
					</div>
					<?php endif ?>
					<hr>
					<div id="UserStatsContainer">
						<div id="LeftContainer">
							<div id="ProfileBadgesContainer">
								<h3>ANORRL Badges</h3>
								<table id="BadgesPane">
									<?php 
										$profilebadges = $get_user->GetProfileBadges();
										$count = count($profilebadges);
										$iteration_countfull = 0;
										$iteration_count = 0;
										
										if($count != 0) {
											foreach($profilebadges as $badge) {
												if($iteration_count == 0) {
													echo <<<EOT
													<tr>
													EOT;
												}

												$badgeid = $badge->id->ordinal();
												$badgename = $badge->name;
												$badgenamefile = str_replace(" ", "", $badge->name);
												$badgedesc = $badge->description;

												echo <<<EOT
												<td>
													<div class="Badge">
														<a href="/badges#badge$badgeid">
															<img src="/images/Badges/$badgenamefile.png" alt="$badgedesc">
															<span>$badgename</span>
														</a>
													</div>
												</td>
												EOT;

												$iteration_countfull++;
												$iteration_count = $iteration_countfull % 4;

												if($iteration_count == 4 || count($profilebadges) == $iteration_countfull) {
													echo <<<EOT
													</tr>
													EOT;
												}
											}
										}
										
									if($count == 0): ?>
									<tr>
										<td class="Loading"><?= $get_user->name ?> has no badges!</td>
									</tr>
									<?php endif ?>
								</table>
							</div>
						</div>
						<div id="RightContainer">
							<div id="PlayerBadgesContainer">
								<h3>Player Badges</h3>
								<table id="BadgesPane">
									<tr>
										<td class="Loading">Loading badges...</td>
									</tr>
								</table>
							</div>
						</div>
						<br clear="all">
					</div>
					<div id="CommentsContainer" style="margin: 10px;">
						<h3 style="margin: 0px">Comments (<?= $com_count ?>)</h3>
						<?php if($user != null): ?>
						<div id="CommentPostArea">
							<?php if($comment_post_error): ?>
								<div class="Error"><?= $result['reason'] ?></div>
							<?php endif ?>
							<form method="POST">
								<h4 style="margin: 0; letter-spacing: 5px;">Post a comment or something</h4>
								<textarea placeholder="Write a wonderful comment about this user!" name="ANORRL$Comment$Post$Contents" maxlength="256" minlength="4"></textarea>
								<input type="submit" value="Submit!" name="ANORRL$Comment$Post$Submit">
							</form>
						</div>
						<?php endif ?>
						<div id="CommentSection">
							

							<?php if($user == null): ?>
								<div id="CommentsDisabled">You need to be logged in to comment on this users profile!</div>
							<?php else: ?>
								<?php
									if($com_count != 0):
										foreach($comments as $comment) {
											$contents = str_replace(" ","&nbsp;",str_replace(PHP_EOL, "<br>", $comment->contents));
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
							<?php endif ?>
						</div>
					</div>
					<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
				</div>
				
			</div>
		</div>
	</body>
</html>