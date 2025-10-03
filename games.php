<!DOCTYPE html>
<html>
	<head>
		<title>Games - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<script src="/js/games.js"></script>
		<style>
			#GamesContainer {
				background: #222;
				padding: 10px;
				border: 2px solid black;
			}

			#GamesContainer #GamesFilterPanel {
				float: left;
				border: 2px solid black;
				background: #1a1a1a;
				width: 175px;
			}

			#GamesContainer #GamesFilterPanel h4 {
				margin: 0;
				width: 100%;
				padding: 10px 0px;
				text-align: center;
			}

			#GamesContainer #GamesFilterPanel ul {
				list-style: none;
				text-align: center;
				padding: 0;
			}

			#GamesContainer #GamesFilterPanel li > a{
				padding: 8px 6px;
				display: block;
				border: 2px solid black;
				margin: 5px;
				background: #111;
			}

			#GamesContainer #Games {
				float: left;
				border: 2px solid black;
				padding: 5px;
				background: #1a1a1a;

				width: 666px;
				margin-left: 7px;
			}

			#GamesContainer #Games .Game {
				display: inline-block;
				padding: 10px;
				border: 2px solid black;
				width: 194px;
				background: #111;
				margin: 2px;
			}

			#GamesContainer #Games .Game:hover {
				cursor: pointer;
			}

			#GamesContainer #Games .Game:hover #Info > a {
				cursor: pointer;
				text-decoration: underline;
				color: #ffc63f;
			}


			#GamesContainer #Games .Game img {
				border: 2px solid black;
			}

			#GamesContainer #Games .Game #Info > span,
			#GamesContainer #Games .Game #Info > a {
				display:block;
			}

			#GamesContainer #Games .Game #Info > span {
				color: lightgray;
			}

			#NumberPutter {
				width: 40px;
				border: 2px solid black;
				background: #242424;
				color: white;
				text-align: center;
			}

			#GamesContainer #Games #Paginator {
				margin: 4px auto;
				text-align: center;
			}

			.Game[template] {
				display:none;
			}
		</style>
	</head>
	<body>
		<div class="Game" template>
			<img src="">
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