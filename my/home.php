<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /"));
	}


	if(isset($_POST['ANORRL$Home$Status$Text']) &&
	   isset($_POST['ANORRL$Home$Status$Submit'])) {
		$result = Status::Send($user->id, trim($_POST['ANORRL$Home$Status$Text']));

		if($result['error']) {
			$_SESSION['ANORRL$Home$StatusError'] = true;
			$_SESSION['ANORRL$Home$StatusResult'] = $result['reason'];
		} else {
			$_SESSION['ANORRL$Home$StatusError'] = false;
			$_SESSION['ANORRL$Home$StatusResult'] = "Success!";
		}

		die(header("Location: /my/home"));
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
		<script src="/js/home.js"></script>
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
				display: table-cell;
				vertical-align: middle;
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
				
			}

			/*#StatusContainer #Status {
				vertical-align: middle;
				height: 100%;
				display: table-cell;
				width: 100%;
			}*/

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
				border-bottom: 0;
				background: #222;
			}

			#FeedsContainer #Pager {
				border: 2px solid black;
				background: #222;
				padding: 16px;
				text-align: center;
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

			#FeedsContainer #Submit .Error {
				border: 1px solid white;
				background: #d00;
				padding: 4px;
				font-weight: bold;
			}

			#FeedsContainer #Submit .Success {
				border: 1px solid white;
				background: #080;
				padding: 4px;
				font-weight: bold;
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
				width: 542px;
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

			.template {
				display: none;
			}
		</style>
	</head>
	<body>
		<table id="FeedItem" class="template">
			<td class="User">
				<a href="">
					<img src="/images/avatar.png">
					<div id="Name">Name here</div>
				</a>
			</td>
			<td id="Content">
				<code>Content content</code>
				<div id="DatePosted">Posted <span id="Date">DD/MM/YYYY</span> ago</div>
			</td>
		</table>
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
									if($user->GetLatestStatus() != null) {
										$status = $user->GetLatestStatus()->content;
										echo <<<EOT
											<span id="Quotation" style="top: 4px;left: 7px;">&quot;</span>
												<span id="Status">$status</span>
											<span id="Quotation" style="bottom: -10px;right: 7px;">&quot;</span>								
										EOT;
									} else {
										echo <<<EOT
											<span id="NoStatus">Seems like you have no status... Try sending one!</span>
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
										<?php if(isset($_SESSION['ANORRL$Home$StatusError'])): ?>
										<?php if($_SESSION['ANORRL$Home$StatusError']): ?>
										<div class="Error"><?= $_SESSION['ANORRL$Home$StatusResult'] ?></div>
										<?php else: ?>
										<div class="Success">Success!</div>
										<?php endif ?>
										<?php endif ?>
										<form method="POST">
											<input name="ANORRL$Home$Status$Text" type="text" minlength="4" maxlength="64" placeholder="What are you feeling today?">
											<input name="ANORRL$Home$Status$Submit" type="submit" value="Submit Status">
										</form>
									</div>
									<div id="Feeds">
										
									</div>
									<div id="Pager" style="display:none">
										<a href="javascript:ANORRL.Home.DeadvanceFeed()" id="BackPager">&lt;&lt; Back</a> <span id="PageCounter">Page 1 of 1</span> <a href="javascript:ANORRL.Home.AdvanceFeed()" id="NextPager">Next &gt;&gt;</a>
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
<?php
	unset($_SESSION['ANORRL$Home$StatusError']);
	unset($_SESSION['ANORRL$Home$StatusResult']);
?>