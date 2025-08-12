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
		<?php if($user->blurb == ""): ?>
		<style>
			#HomePage #StatusContainer {
				margin-top: 10px !important;
			}
		</style>
		<?php endif ?>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
				<div id="HomePage">
						<div id="HelloUser">
							<a href="/users/<?= $user->id ?>/profile"><img id="ProfilePicture" src="/images/spinner100x100.gif" data-src="/images/avatar.png"></a>
							<div id="HelloStuff">
								<h1 id="Hello">Hello, <?= $user->name ?>!</h1>
								<div id="StatusContainer">
									<?php 
										if($user->blurb != "") {
											echo <<<EOT
												<span id="Quotation">&quot;</span>
													<span id="Status">Fun status stuff here!</span>
												<span id="Quotation">&quot;</span>
											EOT;
										} else {
											echo <<<EOT
												<span id="NoStatus">Seems like you have no status... Try setting one <a href="/my/profile">here!</a></span>
											EOT;
										}
									?>
									
								</div>
								<div id="FriendsContainer">
									<h2>Friends <a href="/my/friends">(See all)</a></h2>
									<ul id="Friends">
										<li id="Friend">
											<a id="ProfileLink" href="/users/1/profile">
												<img id="Profile" src="/images/spinner100x100.gif" data-src="/images/avatar.png">
												<div id="Name">Username</div>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div style="clear: both;"></div>
						<div id="ProfileGames">
							<div id="RecentlyPlayed">
								<h2>Recently Played</h2>
								<ul id="Games">
									<li class="Game">
										<a href="/game/1000" title="Whats up guys im gonna rob a store">
											<img src="/images/spinner100x100.gif" data-src="/images/review-pending.png"><br>
											<span id="Name">aaaaaaaaaaaaaaaaaaaaa...</span>
										</a>
										<div id="Stats">
											<div id="OnlinePlayers">Couple ppl online</div>
											<div id="Rating">Rating: 100%</div>
											<div id="Created">Creator: <a href="/profile/1">Creator</a></div>
										</div>
									</li>
								</ul>
							</div>
							<div id="Favourites">
								<h2>Favourites</h2>
								<ul id="Games">
									<li class="Game">
										<a href="/game/1000" title="Whats up guys im gonna rob a store">
											<img src="/images/spinner100x100.gif" data-src="/images/review-pending.png"><br>
											<span id="Name">aaaaaaaaaaaaaaaaaaaaa...</span>
										</a>
										<div id="Stats">
											<div id="OnlinePlayers">Couple ppl online</div>
											<div id="Rating">Rating: 100%</div>
											<div id="Created">Creator: <a href="/profile/1">Creator</a></div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>