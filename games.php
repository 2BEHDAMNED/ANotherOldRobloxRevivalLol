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
		<title>Games - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/games.js"></script>
	</head>
	<body>
		<div class="Game" template>
			<div id="ImageContainer">
				<div id="FavouritesArea"><img src="/images/favourite_star.gif" style="width:16px; margin-bottom: -2px;"> <span>0</span></div>
				<img src="">
			</div>
			<div id="Info">
				<a href="" id="GameName">Game Name</a>
				<hr style="border: none; margin: 2px">
				
				<span>By <a href="" id="GameCreator">creator</a></span>
				<span>Cool stats I think</span>
			</div>
		</div>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2 style="margin: 0px">Games</h2>
					<div id="GamesContainer">
						<div id="GamesFilterPanel">
							<h4>Filters</h4>
							<ul>
								<li><a>Most Popular</a></li>
								<li><a>Most Visited</a></li>
								<li><a>Original Games</a></li>
								<li><a>Newly Created</a></li>
								<li><a>Recently Updated</a></li>
							</ul>
						</div>
						<div id="Games">
							<div method="GET" id="FormPanel" style="margin: 5px auto;">
								<input id="SearchBox" name="query" type="text" placeholder="Look for awesome games!!!" style="width: 460px;">
								<input id="Submit" type="submit" value="Search" onclick="ANORRL.Games.Submit(); return false;">
							</div>
							<div id="StatusText">
								<b id="Loading" style="display: none">Loading assets...</b>
								<b id="NoAssets" style="display: none"><img src="/images/noassets.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: -92px;margin-top: 23px;">No games like that here!</b>
							</div>
						
							<div id="ContainerThingy">
								
							</div>
							
							<div id="Paginator" style="display: block;">
								<a id="BackPager" href="javascript:ANORRL.Games.PrevPage()" style="display: none;">&lt;&lt; Back</a> <input type="text" id="NumberPutter" maxlength="3"> of <span id="Counter">1</span> <a id="NextPager" href="javascript:ANORRL.Games.NextPage()" style="display: none;">Next &gt;&gt;</a>
							</div>
							
						</div>
						<br style="display:block; clear: both;">
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>