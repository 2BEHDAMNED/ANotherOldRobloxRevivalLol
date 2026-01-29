<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /login"));
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Download - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<style>
			#DownloadContainer {
				border: 2px solid black;
				background: #222;
				padding: 10px;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2 style="margin: 0">Ohhh you gotta install my client...</h2>
					<div id="DownloadContainer">
						<p style="font-size: 16px;text-transform: uppercase;font-weight: bold;letter-spacing: 5px;font-style: italic;">So much malware!!!!!!!!!!</p>

						<hr>
						<h4 style="margin:0px">2016</h4>
						<p>Anyways here's the client here: <a href="2016/ANORRLPlayerLauncher.exe">Download</a></p>
						<p>Here's the studio here: <a href="2016/ANORRLStudioLauncher.exe">Download</a></p>
					</div>
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>