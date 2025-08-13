<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /"));
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Home - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<style>

			#HomePage {
				clear: both;
			}

			h1, h2, h3, h4 {
				width: initial\9;
			}

			#HelloStuff h1 {
				margin-bottom: 0px;
				margin-top: 5px;

			}
			#HelloStuff #UserProfile > a img {
				border: 2px solid black;
				background: #222;
				width:200px;
			}

			#HelloStuff #UserProfile > a {
				display: inline-block;
				vertical-align: top;
			}

			#HelloStuff #UserProfile {
				float: left;
			}

			#StatusContainer {
				display: inline-block;
				vertical-align: top;
				border: 2px solid black;
				background: #222;
				padding:10px;
				font-weight: bold;
				font-size: 12px;
				width: 180px;
				height: 94px;
				text-align: center;
				word-wrap: anywhere;
				position: relative;
				display: table;
			}

			#StatusContainer #Status {
				vertical-align: middle;
				height: 100%;
				display: table-cell;
				width: 100%;
			}

			#StatusContainer #Quotation {
				position: absolute;
				font-size: 32px;
			}

			#FriendsContainer {
				display: inline-block;
				margin-top: 3px;
				float: left;
				margin-left: 3px;
			}

			#FriendsContainer #Friends {
				padding: 5px;
				border: 2px solid black;
				height: 157px;
				width: 666px;
				background: #222;
				vertical-align: middle;

				overflow:hidden;
				overflow-x: scroll;
				list-style: none;
				white-space: nowrap;
			}

			#FriendsContainer #NoFriends {
				display: table-cell;
				width: 100%;
				height: 100%;
				vertical-align: middle;
				text-align: center;
				font-weight: bold;
				font-size: 16px;
			}

			#FriendsContainer #Friends .Friend {
				border: 2px solid #090909;
				background: #282828;
				width: 114px;
				height: 135px;
				padding: 5px;
				text-align: center;
				margin-right: 5px;
				display: inline-block;
			}

			#FriendsContainer #Friends .Friend a:hover #Name {
				text-decoration: underline;
			}

			#FriendsContainer #Friends .Friend img {
				width: 100px;
				border: 2px solid #090909;
				background: #1a1919;
			}

			#FriendsContainer #Friends .Friend #Name {
				vertical-align: middle;
				text-align: center;
				width: 124px;
				height: 30px;
				
				word-break: break-all;
				font-weight: bold;
			}
			#RecentlyPlayed h3,
			#Favourites h3,
			#FeedsContainer h2,
			#FriendsContainer h3,
			#FriendsContainer ul {
				margin:0px;
			}

			#RecentlyPlayed h3,
			#Favourites h3 {
				margin-top: 10px;
				font-size: 15px;
			}

			#FeedsContainer {
				display: inline-block;
				width: 680px;
				vertical-align: top;
				margin-top: -117px;
				float: left;
				margin-left: 3px;
			}

			#FeedsContainer #Feeds {
				border: 2px solid black;
				background: #222;
			}

			#FeedsContainer #Submit {
				border: 2px solid black;
				background: #222;
				width: 656px;
				padding: 10px;
				white-space: nowrap;
				border-bottom: none;
			}

			#FeedsContainer #Submit input[type=text] {
				border: 2px solid black;
				background: #444;
				padding: 2px 4px;
				width: 514px;
				margin-right: 9px;
				color: white;
			}

			#FeedsContainer #Submit input[type=submit] {
				border: 2px solid black;
				background: black;
				color: white;
				padding: 4px 8px;
				padding: 2px 4px\9;
				width: 120px\9;
				font-weight: bold;
				font-family: punk;
			}

			#FeedsContainer #Submit input[type=submit]:hover {
				text-decoration: underline;
				background: #161616;
				cursor: pointer;
			}

			#Feeds #FeedItem {
				padding: 10px;
			}

			#Feeds #FeedItem .User,
			#Feeds #FeedItem #Content {
				vertical-align: middle;
			}

			#Feeds #FeedItem .User {
				text-align: center;
			}

			#Feeds #FeedItem .User img {
				width:90px;
				border: none;
			}

			#Feeds #FeedItem #Content code {
				padding: 5px;
				display:block;
				width: 548px;
				border: 2px solid black;
				background: #111;
				margin-bottom: 5px;
			}

			#Feeds #FeedItem #Content #DatePosted {
				color: lightgray;
				font-style: italic;
			}

			#FeedItem[other] {
				background: #1a1a1a;
			}

			#FeedAndGames #ProfileGames {
				width: 204px;
				display: inline-block;
				float: left;
			}

			#FeedAndGames {
				float: left;
			}

			#RecentlyPlayed #Games,
			#Favourites #Games {
				width: 200px;
				border: 2px solid black;
				background: #222;
			}

			#Games .Game {
				padding: 5px;
			}

			#Games .Game a {
				width: 100%;
			}

			#Games .Game img {
				display: block;
				width: 180px;
				height: 101px;
				margin: 0 auto;
				border:2px solid black;
			}

			#Games .Game #Name,
			#Games .Game #Stats {
				margin-left: 6px;
				display:block;
				width: 180px;
			}

			#Clearer {
				clear: both;
				margin-bottom:2px;
				margin-bottom:20px\9;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="HomePage">
						<div id="HelloStuff">
							<h1 id="Hello">Hello, <?= $user->name ?>!</h1>
							<div id="UserProfile">
								<a href="/users/<?= $user->id ?>/profile"><img id="ProfilePicture" src="/images/avatar.png"></a>
								<div id="StatusContainer">
								<?php 
									if($user->blurb != "") {
										echo <<<EOT
											<span id="Quotation" style="top: 4px;left: 7px;">&quot;</span>
												<span id="Status">Fun status stuff here!</span>
											<span id="Quotation" style="bottom: -10px;right: 7px;">&quot;</span>								
										EOT;
									} else {
										echo <<<EOT
											<span id="NoStatus">Seems like you have no status... Try setting one <a href="/my/profile">here!</a></span>
										EOT;
									}
								?>
								
							</div>
							</div>
							<div id="FriendsContainer">
								<h3>Friends<?php if($user->GetFriendsCount() > 5): ?> <a href="/my/friends" style="font-size: 12px;">(See all)</a><?php endif ?></h3>
								<?php if($user->GetFriendsCount() != 0): ?>
								<ul id="Friends">
									<li class="Friend">
										<a id="ProfileLink" href="/users/1/profile">
											<img id="Profile" src="/images/avatar.png">
											<div id="Name">Username</div>
										</a>
									</li>
								</ul>
								<?php else: ?>
								<ul id="Friends" style="display: table">
									<div id="NoFriends">You don't have any friends!</div>
								</ul>
								<?php endif ?>
							</div>
							<br style="clear: both">
							<div id="FeedAndGames">
								<div id="ProfileGames">
									<div id="RecentlyPlayed">
										<h3>Recently Played</h3>
										<div id="Games">
											<div class="Game">
												<a href="/game/1000" title="Whats up guys im gonna rob a store">
													<img src="/images/review-pending.png">
													<span id="Name">aaaaaaaaaaaaaaaaaaaaa...</span>
												</a>
												<div id="Stats">
													<div id="OnlinePlayers">Couple ppl online</div>
													<div id="Created">Creator: <a href="/profile/1">Creator</a></div>
												</div>
											</div>
											<div class="Game">
												<a href="/game/1000" title="Whats up guys im gonna rob a store">
													<img src="/images/review-pending.png">
													<span id="Name">aaaaaaaaaaaaaaaaaaaaa...</span>
												</a>
												<div id="Stats">
													<div id="OnlinePlayers">Couple ppl online</div>
													<div id="Created">Creator: <a href="/profile/1">Creator</a></div>
												</div>
											</div>
										</div>
									</div>
									<div id="Favourites">
										<h3>Favourites</h3>
										<div id="Games">
											<div class="Game">
												<a href="/game/1000" title="Whats up guys im gonna rob a store">
													<img src="/images/review-pending.png">
													<span id="Name">aaaaaaaaaaaaaaaaaaaaa...</span>
												</a>
												<div id="Stats">
													<div id="Created">Creator: <a href="/profile/1">Creator</a></div>
												</div>
											</div>
											<div class="Game">
												<a href="/game/1000" title="Whats up guys im gonna rob a store">
													<img src="/images/review-pending.png">
													<span id="Name">aaaaaaaaaaaaaaaaaaaaa...</span>
												</a>
												<div id="Stats">
													<div id="Created">Creator: <a href="/profile/1">Creator</a></div>
												</div>
											</div>
										</div>
									</div>
									
								</div>
								<div id="FeedsContainer">
									<h2>Your feed</h2>
									<div id="Submit">
										<form method="POST">
											<input name="ANORRL$Home$Status$Text" type="text" minlength="4" maxlength="64" placeholder="What are you feeling today?">
											<input name="ANORRL$Home$Status$Submit" type="submit" value="Submit Status">
										</form>
									</div>
									<div id="Feeds">
										<table id="FeedItem">
											<td class="User">
												<a href="">
													<img src="/images/avatar.png">
													<div id="Name">Name here</div>
												</a>
											</td>
											<td id="Content">
												<code>Content content</code>
												<div id="DatePosted">Posted on: DD/MM/YYYY (x minutes ago)</div>
											</td>
										</table>
										<table id="FeedItem" other>
											<td class="User">
												<a href="">
													<img src="/images/avatar.png">
													<div id="Name">Name here</div>
												</a>
											</td>
											<td id="Content">
												<code>Content content</code>
												<div id="DatePosted">Posted on: DD/MM/YYYY (x minutes ago)</div>
											</td>
										</table>
										<table id="FeedItem">
											<td class="User">
												<a href="">
													<img src="/images/avatar.png">
													<div id="Name">Name here</div>
												</a>
											</td>
											<td id="Content">
												<code>Content content</code>
												<div id="DatePosted">Posted on: DD/MM/YYYY (x minutes ago)</div>
											</td>
										</table>
										<table id="FeedItem" other>
											<td class="User">
												<a href="">
													<img src="/images/avatar.png">
													<div id="Name">Name here</div>
												</a>
											</td>
											<td id="Content">
												<code>Content content</code>
												<div id="DatePosted">Posted on: DD/MM/YYYY (x minutes ago)</div>
											</td>
										</table>
									</div>	
								</div>
							</div>
						</div>
					</div>
					<div id="Clearer"></div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>