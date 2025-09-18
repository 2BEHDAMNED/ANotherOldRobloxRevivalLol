<?php

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

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /"));
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?= $get_user->name ?> - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/jquery.imageloader.js"></script>
		<script src="/js/main.js"></script>
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
				width: 670px;
				
			}

			#UserInfoContainer #ProfileImage {
				text-align: center;
			}

			#UserInfoContainer #ProfileImage img {
				display: block;
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
				text-align: center;margin-left: 10px;height: 195px;overflow: hidden;
				background: #0f0f0f;
				
			}

			#PopularGames img {
				width: 227px;height: 128px;
			}
		</style>
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
								<img src="/images/avatar.png" width="200">
								<div id="Controls">
									<?php if($user != null): ?>
										<?php if($user->id != $get_user->id): ?>
											<button style="width: 107px;">Add Friend</button> <button style="width: 70px;margin-left: 2px;">Follow</button><br>
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
										<span class="Online"><b>Online</b> <a href="">[In Game: Game]</a></span>
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
					<hr style="margin: 5px 10px;border-color:#b0b0b0;border-style: solid;">
					<div id="UserGamesContainer">
						<h3><?= $get_user->name ?>'s Games</h3>
						<table id="PopularGamesBox">
							<td class="PopularGame">
								<table>
									<td id="ShowcaseBigImages">
										<div id="NameAndCreator"><a href="" id="Name">Game Name</a></div>
										<img src="/images/avatar.png">
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
								<div style="height: 201px;overflow: auto;">
									<img src="/images/avatar.png">
									<img src="/images/avatar.png">
									<img src="/images/avatar.png">
								</div>
								
							</td>
						</table>
					</div>
					<hr style="margin: 5px 10px;border-color:#b0b0b0;border-style: solid;">
					<div id="UserStatsContainer">
						<div id="LeftContainer">
							<div id="ProfileBadgesContainer">
								<h3>ANORRL Badges</h3>
								<table id="BadgesPane">
									<tr>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge6">
													<img src="/images/Badges/Homestead-75x75.png" alt="The homestead badge is earned by having your personal place visited 100 times. Players who achieve this have demonstrated their ability to build cool things that other Robloxians were interested enough in to check out. Get a jump-start on earning this reward by inviting people to come visit your place.">
													<span>Homestead</span>
												</a>
											</div>
										</td>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge7">
													<img src="/images/Badges/Bricksmith-75x75.png" alt="The Bricksmith badge is earned by having a popular personal place. Once your place has been visited 1000 times, you will receive this award. Robloxians with Bricksmith badges are accomplished builders who were able to create a place that people wanted to explore a thousand times. They no doubt know a thing or two about putting bricks together.">
													<span>Bricksmith</span>
												</a>
											</div>
										</td>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge3">
													<img src="/images/Badges/CombatInitiation-75x75.png" alt="This badge is given to any player who has proven his or her combat abilities by accumulating 10 victories in battle. Players who have this badge are not complete newbies and probably know how to handle their weapons.">
													<span style="margin-top: 5px;">Combat Initiation</span>
												</a>
											</div>
										</td>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge12">
													<img src="/images/Badges/Veteran-75x75.png" alt="This decoration is awarded to all citizens who have played ROBLOX for at least a year. It recognizes stalwart community members who have stuck with us over countless releases and have helped shape ROBLOX into the game that it is today. These medalists are the true steel, the core of the Robloxian history ... and its future.">
													<span>Veteran</span>
												</a>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge4">
													<img src="/images/Badges/Warrior-75x75.png" alt="This badge is given to the warriors of Robloxia, who have time and time again overwhelmed their foes in battle. To earn this badge, you must rack up 100 knockouts. Anyone with this badge knows what to do in a fight!">
													<span>Warrior</span>
												</a>
											</div>
										</td>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge2">
													<img src="/images/Badges/Friendship-75x75.png" alt="This badge is given to players who have embraced the Roblox community and have made at least 20 friends. People who have this badge are good people to know and can probably help you out if you are having trouble.">
													<span>Friendship</span>
												</a>
											</div>
										</td>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge5">
													<img src="/images/Badges/Bloxxer-75x75.png" alt="Anyone who has earned this badge is a very dangerous player indeed. Those Robloxians who excel at combat can one day hope to achieve this honor, the Bloxxer Badge. It is given to the warrior who has bloxxed at least 250 enemies and who has tasted victory more times than he or she has suffered defeat. Salute!">
													<span>Bloxxer</span>
												</a>
											</div>
										</td>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge8">
													<img src="/images/Badges/Inviter-75x75.png" alt="Robloxia is a vast uncharted realm, as large as the imagination. Individuals who invite others to join in the effort of mapping this mysterious region are honored in Robloxian society. Citizens who successfully recruit three or more fellow explorers via the Share Roblox with a Friend mechanism are awarded with this badge.">
													<span>Inviter</span>
												</a>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge1">
													<img src="/images/Badges/Administrator-75x75.png" alt="This badge identifies an account as belonging to a Roblox administrator. Only official Roblox administrators will possess this badge. If someone claims to be an admin, but does not have this badge, they are potentially trying to mislead you. If this happens, please report abuse and we will delete the imposter's account.">
													<span>Administrator</span>
												</a>
											</div>
										</td>
										<td>
											<div class="Badge">
												<a href="Badges.aspx#Badge15">
													<img src="/images/Badges/BuildersClub-75x75.png" alt="Members of the exclusive Turbo Builders Club are some of the most dedicated ROBLOXians. The Turbo Builders Club is a paid premium service. Members receive many of the benefits received in the regular Builders Club, in addition to a few more exclusive upgrades: they get twenty-five places on their account instead of ten from regular Builders Club, they earn a daily income of 35 ROBUX, they can sell their creations to others in the ROBLOX Catalog, they get the ability to browse the web site without external ads, they receive the exclusive Turbo Builders Club red site managers hat, and they receive an exclusive gear item.">
													<span>Builders Club</span>
												</a>
											</div>
										</td>
										<td></td>
										<td></td>
									</tr>
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