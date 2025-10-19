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
		<title>Catalog - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/catalog.js?t=1759774261"></script>
		<style>
			#CatalogContainer {
				background: #222;
				padding: 10px;
				border: 2px solid black;
			}

			#CatalogContainer #OptionsPanel {
				float: left;
				
			}

			#CatalogContainer #CategoriesChooser,
			#CatalogContainer #FiltersChooser {
				border: 2px solid black;
				background: #1a1a1a;
				width: 175px;
			}

			#CatalogContainer #CategoriesChooser h4,
			#CatalogContainer #FiltersChooser h4 {
				margin: 0;
				width: 100%;
				padding: 10px 0px;
				text-align: center;
			}

			#CatalogContainer #CategoriesChooser ul,
			#CatalogContainer #FiltersChooser ul {
				list-style: none;
				text-align: center;
				padding: 0;
			}

			#CatalogContainer #CategoriesChooser li > a,
			#CatalogContainer #FiltersChooser li > a {
				padding: 4px 6px;
				display: block;
				border: 2px solid black;
				margin: 5px;
				background: #111;
			}

			#CatalogContainer #CategoriesChooser li[selected] > a,
			#CatalogContainer #FiltersChooser li[selected] > a {
				font-weight: bold;
				text-decoration: underline;
				background: #1a1a1a;
			}

			#CatalogContainer #AssetsContainer {
				float: left;
				border: 2px solid black;
				margin-left: 6px;
				background: #1a1a1a;
				
			}

			#CatalogContainer #AssetsContainer #Paginator {
				margin-bottom: 0px;
			}

			#CatalogContainer #AssetsContainer .Asset {
				*background: #2d2d2d;
			}

			#CatalogContainer #AssetsContainer .Asset #Pricing {
				*background: #262626;
			}

		</style>
	</head>
	<body>
		<div class="Asset" template>
			<a id="NameAndThumbs">
				<img src="">
				<div id="Pricing">
					<span id="Cones" ><img src="/images/icons/traffic_cone.png" > <span id="Costing"></span></span>
					<span id="Lights"><img src="/images/icons/traffic_light.png"> <span id="Costing"></span></span>
				</div>
				<span>AssetName</span>
			</a>
			<a id="Creator"><span>AssetCreator</span></a>
		</div>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2 style="margin: 0px">Catalog</h2>
					<div id="CatalogContainer">
						<div id="OptionsPanel">
							<div id="CategoriesChooser">
								<h4>Categories</h4>
								<ul>
									<li data_category="8" ><a>Hats</a></li>
									<li data_category="18"><a>Faces</a></li>
									<li data_category="11"><a>Shirts</a></li>
									<li data_category="2" ><a>T-Shirts</a></li>
									<li data_category="12"><a>Pants</a></li>

									<li data_category="3" ><a>Audio</a></li>
									<li data_category="13"><a>Decals</a></li>
									<li data_category="10"><a>Models</a></li>

									<li data_category="19"><a>Gears</a></li>
									<li data_category="17"><a>Heads</a></li>
									<li data_category="32"><a>Packages</a></li>
								</ul>
							</div>
							<div id="FiltersChooser" style="margin-top: 10px;">
								<h4>Filters</h4>
								<ul>
									<li><a>Recently Uploaded</a></li>
									<li><a>Most Purchased</a></li>
									<li><a>Price (Low 2 High)</a></li>
									<li><a>Price (High 2 Low)</a></li>
									<li><a>Most Favourited</a></li>
								</ul>
							</div>
						</div>
						<div id="AssetsContainer">
							<div method="GET" id="FormPanel" style="margin: 5px auto;">
								<input id="SearchBox" name="query" type="text" placeholder="Look for awesome games!!!" style="width: 460px;">
								<input id="Submit" type="submit" value="Search" onclick="ANORRL.Games.Submit(); return false;">
							</div>
							<div id="StatusText">
								<b id="Loading" style="display: none">Loading assets...</b>
								<b id="NoAssets" style="display: none"><img src="/images/noassets.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: -92px;margin-top: 23px;">No <span id="AssetType"></span> like that here!</b>
							</div>
						
							<table id="Assets">
								
							</table>
							
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