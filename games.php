<!DOCTYPE html>
<html>
	<head>
		<title>Games - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<style>
			#GamesContainer {
				background: #222;
				padding: 10px;
				border: 2px solid black;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2 style="margin: 0px">Games</h2>
					<div id="GamesContainer">
						<div id="GamesFilterPanel">

						</div>
						<div id="Games">

						</div>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>