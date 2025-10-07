<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';

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

	if($user == null) {
		die(header("Location: /"));
	}

	$games = $get_user->GetAllOwnedAssetsOfType(AssetType::PLACE);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?= $get_user->name ?> - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/user.js"></script>
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
				height: 182px;
				display: block;
				width: 249px;
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
				overflow: hidden;
				background: #0f0f0f;
				overflow-y: scroll;
			}

			#PopularGames img {
				width: 227px;
				height: 128px;
			}

			#PopularGames img:hover {
				cursor: pointer;
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
			function flipRenders(element) {

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
									<img src="/images/avatar.png">
								</div>
								
								<div id="Controls">
									<?php if($user != null): ?>
										<?php if($user->id != $get_user->id): ?>
											<button style="margin-top: 4px;width: 107px;">Add Friend</button> <button style="margin-top: 4px;width: 70px;margin-left: 2px;">Follow</button><br>
										<?php endif ?>
										<button style="width: 74px;margin-top: 4px;">Message</button>
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
										<?php 
											//<a href="">[In Game: Game]</a>
										if($get_user->IsOnline()): ?>
										
										<span class="Online"><b>Online</b> - <?= $get_user->GetOnlineActivity() ?></span>
										<?php else: ?>
										<span class="Offline"><b>Offline</b></span>
										<?php endif ?>
									</div>
									<div id="OnlineStatusArea" style="padding-top:0px; margin-top:-5px;">
										<span><b>Joined</b>: <?= $get_user->join_date->format('F dS, Y') ?></span>
									</div>
									<div id="Blurb">
										<?php 
											if(strlen($get_user->blurb) == 0) {
												echo "<b>This user has no blurb!</b>";
											} else {
												echo str_replace(PHP_EOL, "<br>", $get_user->blurb);
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
										<a id="Play" href=""></a>
									</td>
									<td id="ShowcaseDetails">
										<code>
											Description hi hihi
										</code>
										<div id="AllowedStuff"></div>
									</td>
								</table>
							</td>
							<td id="PopularGames">
								<div style="height: 201px;overflow: scroll;width:244px">
									<?php
										foreach($games as $game) {
											$game_id = $game->id;

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
					<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
				</div>
				
			</div>
		</div>
	</body>
</html>