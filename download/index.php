<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /login"));
	}

	$randomclientsplashes = [
		"Now with 100% viruses!",
		"Ohhh you gotta install my clients...",
		"Colle t my 8 cliens...",
		"Smells like malware in here...",
		"Will be detected by all antiviruses (I'm coming for you.)",
		"Download RIGHT NOW!",
		"Now with 100% the fun or something"
	];

	$randomclientsplash = $randomclientsplashes[array_rand($randomclientsplashes)];
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

			#DownloadContainer table div {
				margin: 15px auto;
				text-align: center;
			}

			#DownloadContainer table div > a {
				font-size: 14px;
			}

			#DownloadContainer table div > a > span {
				display: block;
				margin-top: 5px;
			}

			#DownloadContainer table div > a > img {
				margin: 0 auto;
				display: block;
				width: 150px;
			}

			h2, h3 {
				margin: 0px;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2><?= $randomclientsplash ?></h2>
					<div id="DownloadContainer">
						<p style="font-size: 16px;text-transform: uppercase;font-weight: bold;letter-spacing: 5px;font-style: italic;margin-bottom: 5px;">So much malware!!!!!!!!!!</p>
						<span style="font-size: 11px; color: #999; font-style: italic">(Unfortunately, it is windows only. But wine works fine on linux! Mac builds may come soon...)</span>
						<hr>
						<h3>Clients</h3>
						<div id="DownloadContainer" style="background: #161616;">
							<table style="width: 100%">
								<tr>
									<td>
										<div>
											<a href="2016/ANORRLPlayerLauncher.exe">
												<img src="/images/placeholder.png">
												<span>2016</span>
											</a>
										</div>
									</td>
									
									<td>
										<div>
											<a href="javascript:alert('2013 Soon!')">
												<img src="/images/placeholder.png">
												<span>2013 (soon)</span>
											</a>
										</div>
									</td>

									<td>
										<div>
											<a href="javascript:alert('2010 Client Soon!')">
												<img src="/images/placeholder.png">
												<span>Client (soon)</span>
											</a>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<hr>
						<h3>Studio</h3>
						<div id="DownloadContainer" style="background: #161616;">
							<table style="width: 100%">
								<tr>
									<td>
										<div>
											<a href="2016/ANORRLStudioLauncher.exe">
												<img src="/images/placeholder.png">
												<span>2016</span>
											</a>
										</div>
									</td>
									<td>
										<div>
											<a href="javascript:alert('2013 Studio Soon!')">
												<img src="/images/placeholder.png">
												<span>2013 (soon)</span>
											</a>
										</div>
									</td>
								</tr>
							</table>
						</div>
						
					</div>
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>