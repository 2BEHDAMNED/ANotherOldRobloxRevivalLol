<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	$type = "none";
	if(isset($_GET['type'])) {
		$type = $_GET['type'];
	}

	if($user == null) {
		die(header("Location: /"));
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Create - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<script src="/js/create.js"></script>
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
				border-right: none;
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

			#StuffNavigation ul > li[selected] a {
				font-weight: bold;
				text-decoration: underline;
				color: #ffc63f;
			}

			#AssetsContainer {
				display: inline-block;
				border: 2px solid black;
				
				min-height: 180px;
				width: 676px;
				background: #222;
				vertical-align: middle;
				white-space: nowrap;
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

			#CreationPanel {
				display:inline-block;
				width: 688px;
			}

			#CreationPanel #UploadPanel {
				display: inline-block;
				border: 2px solid black;
				min-height: 180px;
				width: 676px;
				background: #222;
				vertical-align: middle;

				white-space: nowrap;
				border-top: 0;
				position: relative;
				margin-bottom: 15px;
			}


			#UploadPanel form {
				padding: 10px;
			}

			#UploadPanel td {
				vertical-align: top;
			}

			#UploadPanel input[type=text],
			#UploadPanel textarea {
				border: 2px solid black;
				background: #444;
				padding: 2px 4px;
				color: white;
			}

			#UploadPanel input[type=submit],
			#UploadPanel label[for=files] {
				border: 2px solid black;
				background: black;
				color: white;
				padding: 4px 8px;
				font-weight: bold;
				font-family: punk;
			}

			#UploadPanel input[type=submit]:hover,
			#UploadPanel label[for=files]:hover {
				text-decoration: underline;
				background: #161616;
				cursor: pointer;
			}


			#UploadPanel input[type=text] {
				width: 574px;
			}

			#UploadPanel textarea {
				width: 574px;
				height: 58px;
				resize: vertical;
			}

			#UploadPanel label#filename {
				margin-left: 5px;
			}

			#UploadPanel #ErrorTime,
			#UploadPanel #InfoWarning {
				padding: 5px 9px;
				border-bottom: 2px solid black;
				background: #9d0000;
				font-weight: bold;
			}

			#UploadPanel #InfoWarning {
				background: #b58b05;
			}

			#CreationPanel h3 {
				margin: 0;
				padding-right: 0px;
				width: 656px;
				background: #151515;
				border-bottom: 2px solid black;
				border-right: 2px solid black;
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
						<h1>Creation Panel</h1>
						<div id="StuffNavigation">							
							<ul>
								<li data_category="11"><a>Shirts</a></li>
								<li data_category="2" ><a>T-Shirts</a></li>
								<li data_category="12"><a>Pants</a></li>
								<hr>
								<li data_category="3" ><a>Audio</a></li>
								<li data_category="13"><a>Decals</a></li>
								<li data_category="10"><a>Models</a></li>
								<li data_category="9" ><a>Places</a></li>
							</ul>
						</div><div id="CreationPanel">
							
							<div id="UploadPanel">
								<h3>Upload <span id="TypaLabel"></span></h3>
								<div id="InfoWarning" style="display: none;">Must be in XML format NOT BINARY.</div>
								<div id="ErrorTime" style="display: none;">Error: <span id="Message">you upload too much it makes me sad :(</span></div>
								<form method="POST" enctype="multipart/form-data">
									<table>
										<tr>
											<td>Name</td>
											<td><input type="text" name="ANORRL$CreateAsset$Name" minlength="3" maxlength="100" required></td>
										</tr>
										<tr>
											<td>Description</td>
											<td><textarea name="ANORRL$CreateAsset$Description" maxlength="1000"></textarea></td>
										</tr>
										<tr>
											<td>File</td>
											<td><label for="files">Choose file</label><input id="files" style="display:none;" type="file" required><label id="filename">No file chosen</label></td>
										</tr>
										<tr>
											<td><input type="submit" value="Upload" style="margin-top:10px"></td>
										</tr>
									</table>
								</form>
							</div>
							<div id="AssetsContainer">
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
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>