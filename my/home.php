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
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/home.js"></script>
		<style>
			h1, h2, h3, h4 {
				width: initial\9;
			}
		</style>
	</head>
	<body>
		<table id="FeedItem" template>
			<td class="User">
				<a href="">
					<img src="/images/avatar.png">
					<div id="Name">Name here</div>
				</a>
			</td>
			<td id="Content">
				<code>Content content</code>
				<div id="DatePosted">Posted <span id="Date">DD/MM/YYYY</span> ago <a href="">Report abuse</a></div>
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
											<!--<div class="Game">
												<a href="/game/1000" title="Whats up guys im gonna rob a store">
													<img src="/images/review-pending.png">
													<span id="Name">aaaaaaaaaaaaaaaaaaaaa...</span>
												</a>
												<div id="Stats">
													<div id="OnlinePlayers">Couple ppl online</div>
													<div id="Created">Creator: <a href="/profile/1">Creator</a></div>
												</div>
											</div>-->

											<span id="NoTagline">No recently played games yet!</span>
										</div>
									</div>
									<div id="Favourites">
										<h3>Favourites</h3>
										<div id="Games">
											<span id="NoTagline">No favourites yet!</span>
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