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
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<script src="/js/stuff.js"></script>
		<style>
			#StuffContainer {
				width: 876px;
				margin: 0 auto;
				margin-bottom: 15px;
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
				border-top: 0;
				
			}

			#StuffNavigation ul {
				list-style: none;
				padding: 15px;
				margin: 0;
			}

			#StuffNavigation hr {
				border: 0;
			}

			#StuffNavigation ul > li[selected] {
				font-weight: bold;
			}

			#AssetsContainer {
				display: inline-block;
				border: 2px solid black;
				border-left: none;
				min-height: 180px;
				width: 676px;
				background: #222;
				vertical-align: middle;

				white-space: nowrap;
				border-top: 0;
				position: relative;
			}

			#AssetsContainer table {
				min-height: 180px;
				padding: 5px;
			}

			#AssetsContainer #Paginator {
				display: block;
				background: #111111;
				padding: 10px;
				text-align: center;
			}

			#AssetsContainer #Paginator input {
				font-size: 12px;
				width: 30px;
				height: 13px;
				text-align: center;
			}

			#StuffNavigation #CreateArea {
				display: block;
				width: 100%;
				padding: 5px 0px;
				background: #111;
				font-weight: bold;
				text-align: center;
			}
			
			#StuffNavigation #CreateArea a {
				display: inline-block;
				width: 45%;
			}

			#StuffNavigation #CreateArea a[disabled] {
				color: gray;
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

			#AssetsContainer table[hidden] {
				display: none;
			}

			#AssetsContainer #Loading, 
			#AssetsContainer #NoAssets {
				font-size: 18px;
				width: 100%;
				display: block;
				text-align: center;
				line-height: 180px;
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
							<div id="CreateArea">
								<a href="/create/">Create</a>
								<a href="/catalog">Shop</a>
							</div>
							
							<ul>
								<li data_category="8" ><a>Hats</a></li>
								<li data_category="18"><a>Faces</a></li>
								<li data_category="11"><a>Shirts</a></li>
								<li data_category="2" ><a>T-Shirts</a></li>
								<li data_category="12"><a>Pants</a></li>
								<hr>
								<li data_category="3" ><a>Audio</a></li>
								<li data_category="13"><a>Decals</a></li>
								<li data_category="10"><a>Models</a></li>
								<li data_category="9" ><a>Places</a></li>
								<hr>
								<li data_category="19"><a>Gears</a></li>
								<li data_category="21"><a>Badges</a></li>
								<li data_category="34"><a>Gamepasses</a></li>
								<li data_category="32"><a>Packages</a></li>
							</ul>
						</div><div id="AssetsContainer">
							<div id="StatusText">
								<b id="Loading" style="display: none">Loading assets...</b>
								<b id="NoAssets" style="display: none"><img src="/images/noassets.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: -92px;margin-top: 23px;">You have no <span id="AssetType"></span>!</b>
							</div>
						
							<table hidden></table>

							<div id="Paginator" style="display: none">
								<a href="" id="PrevPager">&lt;&lt;Previous</a> Page <input maxlength="4"> of <span id="Pages">1</span> <a href="" id="NextPager">Next&gt;&gt;</a>
							</div>
						</div>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>