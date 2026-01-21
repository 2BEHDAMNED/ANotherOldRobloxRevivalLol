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

	$header_data = $get_user;

	$games = $get_user->GetAllOwnedAssetsOfType(AssetType::PLACE);

	$comments = Comment::GetCommentsOn($get_user);
	$com_count = count($comments);

	if($user != null) {
		if(
			isset($_POST['ANORRL$Comment$Post$Contents']) &&
			isset($_POST['ANORRL$Comment$Post$Submit'])
		) {
			$result = Comment::Post($get_user, $_POST['ANORRL$Comment$Post$Contents']);
			$comment_post_error = $result['error'];
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?= $get_user->name ?> - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/placelauncher.js?t=<?= time() ?>"></script>
		<script src="/js/user.js?t=<?= time() ?>"></script>
		<script>
			$(function(){
				ANORRL.User.GrabFeed(<?= $get_user->id ?>);
			});
		</script>
		<style>
			#UserStatsContainer, #UserInfoContainer {
				margin: 0 auto;
				width: 900px;
				height:fit-content;
			}

			#UserInfoContainer #PaddingContainer {
				padding: 10px;
			}

			#UserInfoContainer #ProfileInfo, #UserInfoContainer #ProfileImage {
				float: left;
			}

			#UserInfoContainer #ProfileInfo {
				width: 660px;
				
			}

			#UserInfoContainer #ProfileImage {
				text-align: center;
			}

			#UserInfoContainer #ProfileImage #ImageContainer > img {
				display: block;
				width: 200px;
				height: 200px;
				background: #222;
				position:absolute;
			}

			#UserInfoContainer #ProfileImage #ImageContainer {
				border: 2px solid black;
				background: #222;
				margin-right:5px;
				position: relative;
				width: 200px;
				height: 200px;
			}

			#UserInfoContainer #ProfileImage button {
				border: 2px solid black;
				background: #111;
				color: white;
				padding: 3px 10px;
				font-size: 12px;
			}

			#UserInfoContainer #ProfileImage button:hover {
				border: 2px solid white;
				cursor: pointer;
				font-weight: bold;
			}

			#UserStatsContainer #LeftContainer,
			#UserStatsContainer #RightContainer {
				width: 429px;
				float: left;
				padding: 10px;
			}

			#UserStatsContainer #RightContainer {
				
			}

			#UserInfoContainer #Stats #Blurb {
				border: 2px solid black;
				background: #212121;
				padding: 10px;
				margin-top: 5px;
				height: 110px;
				overflow: auto;
			}

			#UserInfoContainer #Stats div > a {
				text-decoration: none;
			}

			#UserInfoContainer #Stats #FollowFriendsWhatever {
				background: black;
				
				width: unset;
				padding: 10px;
			}

			#UserInfoContainer #Stats #FollowFriendsWhatever a {
				color: white;
			}

			#UserInfoContainer #Stats #FollowFriendsWhatever a #Numbers {
				font-family: Arial;
				font-size: 14px;
			}

			#UserInfoContainer #Stats #OnlineStatusArea {
				background: black;
				width: unset;
				padding: 0px 10px;
				padding-bottom: 10px;
			}

			#UserInfoContainer #Stats #OnlineStatusArea .Online {
				color: #73e71e;
			}

			#UserInfoContainer #Stats #OnlineStatusArea .Online a {
				color: #73e71e;
				font-weight: bold;
				*text-decoration: underline;
			}

			#UserInfoContainer #Stats div > a:hover span {
				text-decoration: underline;
			}

			#UserStatsContainer #ProfileBadgesContainer,
			#UserStatsContainer #PlayerBadgesContainer {
				width: 100%;
			}

			#UserStatsContainer #ProfileBadgesContainer h3,
			#UserStatsContainer #PlayerBadgesContainer h3 {
				width: 100%;
				padding: 5px 0px;
				padding-left: 5px;
				margin-bottom: 0px;
			}

			.Badge {
				margin: 1px;
				text-align: center;
				background: #222;
				width: 85px;
				height: 112px;
				padding: 5px;
				border: 2px solid black;
			}

			.Badge img {
				height: 75px;
				width: 75px;
				border: 2px solid black;
				background: #555;
			}

			.Badge span {
				font-size: 12px;
				margin: 4px;
				margin-top: 10px;
				display: block;
			}

			#BadgesPane {
				background: #111;
				width: 434px;
				min-height: 260px;
				border: 2px solid black;
			}

			#BadgesPane td {
				vertical-align: top;
			}

			#BadgesPane .Loading {
				text-align: center;
				font-size: 16px;
				vertical-align: middle;
			}

			.Badge[template] {
				display: none;
			}

			#UserGamesContainer h3 {
				margin-bottom: 0px;
				margin-left: 15px;
				width: 830px;
			}

			#UserGamesContainer	#PopularGamesBox {
				border: 2px solid #090909;
				margin: 0 auto;
				background: #171717;
				padding: 10px;
				padding-left: 15px;
				width: 870px;
			}

			.PopularGame img {
				width: 300px;
				height: 169px;
				border: 2px solid #090909;
			}

			.PopularGame #Play {
				width: 212px;
				height: 54px;
				background: url(/images/btn_play_54h.png);
				display: inline-block;
			}

			.PopularGame #Play:hover {
				background-position: 0 54px;
			}

			.PopularGame #ShowcaseBigImages {
				display: table-cell;
				vertical-align: top;
				width:304px;
				text-align:center;
			}

			.PopularGame #ShowcaseBigImages img {
				background:	#282828
			}

			.PopularGame #ShowcaseDetails {
				display: table-cell;
				vertical-align: top;

				width: 282px;
				height: 225px;
			}

			.PopularGame #ShowcaseBigImages #NameAndCreator {
				margin-left: 0px;
				margin-top: 0px;
				font-size:15px;
				background: black;
				font-family: punk;
				font-weight: bold;
				padding: 5px;
				padding-left: 15px;
				padding-top: 10px;
			}

			.PopularGame #ShowcaseBigImages #NameAndCreator a {
				display: block;
				color: white;
			}

			.PopularGame #ShowcaseBigImages #NameAndCreator #Creator {
				font-size: 11px;
			}

			.PopularGame #ShowcaseDetails code {
				border: 2px solid black;
				padding: 10px;
				background: #282828;
				height: 240px;
				display: block;
				width: 230px;
				overflow-x: hidden;
				overflow-y: auto;
				margin-bottom: -5px;
			}

			.PopularGame #ShowcaseDetails #AllowedStuff {
				border: 2px solid black;
				padding: 10px;
				background: #181818;
				display: block;
				width: 249px;
				border-top: 0;
			}

			#PopularGames {
				text-align: center;
				margin-left: 10px;
				height: 195px;
				background: #0f0f0f;
			}

			#PopularGames img {
				width: 227px;
				height: 128px;
				border: 1px solid gray;
			}

			#PopularGames img:hover {
				cursor: pointer;
				border: 1px solid white;
				filter: brightness(1.25) contrast(0.5);
			}

			#PopularGames #Filler {
				display: block;
				width: 240px;
				height: 128px;
			}

			#PopularGames > div > a:hover > img {
				background: #333;
			}
		</style>
		<script>
			var render = <?= $get_user->setprofilepicture ? "false" : "true" ?>;
			function flipRenders(element) {
				render = !render;

				if(render) {
					$("#ProfilePictureYeah").attr("src", "/thumbs/player?id=<?= $get_user->id ?>");
				} else {
					$("#ProfilePictureYeah").attr("src", "/thumbs/profile?id=<?= $get_user->id ?>");
				}
			}
		</script>
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
									<a href="javascript:flipRenders(this)" style="position: absolute;z-index: 2;bottom: 5px;right: 5px;"><img src="/images/icons/switch.png" style="width: 30px;image-rendering: pixelated;"></a>
									<img id="ProfilePictureYeah" src="/thumbs/<?= $get_user->setprofilepicture ? "profile" : "player" ?>?id=<?= $get_user->id ?>">
								</div>
								
								<div id="Controls">
									<style>
										#Controls button {
											margin-top: 4px;
										}
									</style>
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
					<?php if(count($games) != 0): ?>
					<hr style="margin: 5px 10px;border-color:#b0b0b0;border-style: solid;">
					<div id="UserGamesContainer">
						<h3><?= $get_user->name ?>'s Games</h3>
						<table id="PopularGamesBox">
							<td class="PopularGame">
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
							<td id="PopularGames">
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
					<hr style="margin: 5px 10px;border-color:#b0b0b0;border-style: solid;">
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
								<textarea placeholder="Write a wonderful comment about this place!" name="ANORRL$Comment$Post$Contents" maxlength="256" minlength="4"></textarea>
								<input type="submit" value="Submit!" name="ANORRL$Comment$Post$Submit">
							</form>
						</div>
						<?php endif ?>
						<div id="CommentSection">
							

							<?php if($user == null): ?>
								<div id="CommentsDisabled">You need to be logged in to comment on this item!</div>
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
													<a href="/user/$user_id/profile">
														<img src="/thumbs/$profileurl?id=$user_id">
													</a>
												</div>
												<div id="CommentPartArea">
													<div id="CommentInfoArea">
														<a href="/user/$user_id/profile">$user_name</a>&nbsp;<span>Posted on $formatted_datetime</span>
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