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
		<title>Your Stuff - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<script src="/js/stuff.js"></script>
		<style>

			#StuffContainer {
				width: 876px;
				margin: 0 auto;
			}

			#StuffContainer h1 {
				margin-bottom: 0px;
				margin-top: 5px;
				width:826px;
			}

			#StuffContainer #StuffNavigation {
				border: 2px solid black;
				background: #222;
				width:184px;
				display: inline-block;
				vertical-align: top;
				border-right: 0;
				border-top: 0;
				
			}

			#StuffNavigation ul {
				list-style: none;
			}

			#AssetsContainer {
				display: inline-block;
				padding: 5px;
				border: 2px solid black;
				min-height: 157px;
				width: 666px;
				background: #222;
				vertical-align: middle;

				overflow:hidden;
				overflow-x: scroll;
				list-style: none;
				white-space: nowrap;
				border-top: 0;
			}

			#AssetsContainer #NoFriends {
				display: table-cell;
				width: 100%;
				height: 100%;
				vertical-align: middle;
				text-align: center;
				font-weight: bold;
				font-size: 16px;
			}

			#AssetsContainer h3,
			#AssetsContainer ul {
				margin:0px;
			}

			.Asset {
				border: 2px solid black;
				width: 135px;
				padding: 10px;
				background: #1a1a1a;
				font-size: 0px;
			}

			.Asset #NameAndThumbs {
				display: block;
			}

			.Asset #NameAndThumbs > img {
				border: 2px solid black;
				display: block;
				width: 130px;
				height: 130px;
				background: #222;
			}

			.Asset #NameAndThumbs > span {
				display: block;
				font-weight: bold;
			}

			.Asset > a > span {
				margin: 6px 5px;
				margin-bottom: 0px;
				display:block;
				font-size: 12px;
			}

			.Asset > a:hover {
				text-decoration: none;
			}
			.Asset > a:hover > span {
				text-decoration: underline;
			}

			.Asset #Creator,
			.Asset #Creator span {
				margin-top: 0px;
			}

			.Asset #Pricing {
				background: #313131;
				border: 2px solid black;
				border-top: 0px;
				padding: 5px;
				width: 120px;
				color: white;
				text-decoration: none;
				white-space: none;
			}

			.Asset #Pricing > span > img {
				border: 0;
				width: 16px;
				height: 16px;
				image-rendering: pixelated;
				margin-bottom: -3px;
				position: relative;
				display: unset;
			}

			.Asset #Pricing > span {
				display:inline-block;
				width: 50%;
				padding-left: 4px;
				font-size: 12px;
			}

			.Asset[template] {
				display: none;
			}

			#AssetsContainer #Loading, 
			#AssetsContainer #NoAssets {
				font-size: 18px;
				width: 100%;
				display: block;
				text-align: center;
				line-height: 150px;
			}
		</style>
	</head>
	<body>
		<div class="Asset" template>
			<a id="NameAndThumbs">
				<img src="/images/avatar.png">
				<div id="Pricing">
					<span id="Cones" ><img src="/images/icons/traffic_cone.png" > 100</span>
					<span id="Lights"><img src="/images/icons/traffic_light.png"> 100</span>
				</div>
				<span>AssetName</span>
			</a>
			<a id="Creator"><span>AssetCreator</span></a>
		</div>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="StuffContainer">
						<h1>Your Stuff</h1>
						<div id="StuffNavigation">
							<ul>
								<li><a href="">Hats</a></li>
								<li><a href="">Hats</a></li>
								<li><a href="">Hats</a></li>
								<li><a href="">Hats</a></li>
							</ul>
						</div><div id="AssetsContainer">
							<div id="StatusText">
								<b id="Loading">Loading assets...</b>
								<b id="NoAssets" style="display: none">You have no &lt;asset type here&gt;!</b>
							</div>
						
							<table>
								<tr>
									<td>
										
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