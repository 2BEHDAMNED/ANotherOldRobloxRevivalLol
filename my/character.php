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
		<title>Your Character - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/character.js?t=1760895512"></script>
		<style>
			h2, h4 {
				margin: 0;
			}

			h4 {
				margin-top: 5px;
			}

			#CharacterContainer {

			}

			#CharacterContainer #CharacterLeftwardSide {
				float: left;
				width:600px;
			}
			
			#CharacterContainer #CharacterRightwardSide {
				float: left;
				width:290px;
			}

			#CharacterContainer #AssetsContainer {
				width: 590px;
				border:2px solid black;
			}

			#CharacterContainer #Wardrobe #WardrobeHeader {
				width: 590px;
				background: #111111;
				padding: 5px 0px;
				border: 2px solid black;
				border-bottom: none;
				text-align: center;
			}

			#CharacterContainer #Wardrobe #WardrobeHeader hr {
				width: 85%;
				
			}

			#CharacterContainer #Wardrobe #WardrobeHeader a {
				padding: 10px;
			}

			#CharacterContainer #AvatarRender #RenderContainer {
				border: 2px solid black;
				background: #222;
				text-align: center;
			}

			#CharacterContainer #AvatarRender button {
				border: 2px solid black;
				background: #111;
				color: white;
				padding: 3px 10px;
				font-size: 12px;
			}

			#CharacterContainer #AvatarRender button:hover {
				border: 2px solid white;
				cursor: pointer;
				font-weight: bold;
			}

			#CharacterContainer a[data_category][selected] {
				font-weight: bold;
				text-decoration: underline;
			}

			#CharacterContainer a[data_category]:hover {
				cursor: pointer;
			}

			#CharacterContainer .Asset {
				position: relative;
			}

			#CharacterContainer #WearButton {
				font-size: 12px;
				position: absolute;
				right: 5px;
				top: 5px;
				background: gray;
				padding: 1px 3px;
				border: 2px solid #575757;
				color: #ffa634;
			}

			#CharacterContainer #WearButton:hover { 
				text-decoration: underline;
				color: #ffc63f;
				font-weight: bold;
				cursor: pointer;
			}

			#CharacterContainer .Asset {
				width: 117px;
			}

			#CharacterContainer .Asset #NameAndThumbs > img {
				width: 113px;
				height: 113px;
			}

			#CharacterContainer #BodyColours #BodyColoursContainer {
				border: 2px solid black;
				background: #222;
				text-align: center;
				padding: 15px;
			}

			#CharacterContainer #BodyColours #BodyColoursContainer button[data_bodytype] {
				border: 2px solid black;
				width: 38px;
				height: 76px;
			}

			#CharacterContainer #BodyColours #BodyColoursContainer button[data_bodytype]:hover {
				cursor: pointer;
			}

			#CharacterContainer #BodyColours #BodyColoursContainer button[data_bodytype="1"] {
				width: 76px;
			}

			#CharacterContainer #BodyColours #BodyColoursContainer button[data_bodytype="0"] {
				width: 44px;
				height: 44px;
			}

			#CharacterContainer #BodyColours #BodyColoursContainer #BodyPartInfo {
				margin: 0 auto;
				margin-top: 15px;
				background: black;
				padding: 10px 5px;
				font-family: punk;
				font-size: 16px;
			}
			#ColourPickerChooser {
				position: absolute;
				width: 100vw;
				height: 100vh;
				background: #0006;
				z-index: 999;
				margin-top: -55px;
			}

			#ColourPickerChooser #Colours {
				position: absolute;
				inset: 0;
				margin: auto;
				border: 2px solid black;
				background: #333;
				width: 580px;
				height: 327px;
				padding: 10px;
			}

			#ColourPickerChooser #Colours button {
				border: 2px solid black;
				width: 32px;
				height: 32px;
				margin: 2px;
			}

			#ColourPickerChooser #Colours button:hover {
				cursor: pointer;
				border-color: white;
			}
		</style>
	</head>
	<body>
		<div id="ColourPickerChooser" style="display: none">
			<div id="Colours">

			</div>
		</div>
		<div class="Asset" template>
			<div id="WearButton">[ wear ]</div>
			<a id="NameAndThumbs">
				<img src="">
				<span>AssetName</span>
			</a>
			<a id="Creator"><span>AssetCreator</span></a>
		</div>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2 style="margin-bottom: 5px">Your Character</h2>
					<div id="CharacterContainer">
						<div id="CharacterLeftwardSide">
							<div id="Wardrobe">
								<h4>Wardrobe</h4>
								<div id="WardrobeHeader">
									<div>
										<a data_category="8">Hats</a>
										<a data_category="18">Faces</a>
										<a data_category="2">T-Shirts</a>
										<a data_category="11">Shirts</a>
										<a data_category="12">Pants</a>
										<a data_category="19">Gears</a>
										<a data_category="outfits">Outfits</a>
									</div>
									<hr>
									<div>
										<a data_category="32">Packages</a>
										<a data_category="17">Heads</a>
										<a data_category="27">Torsos</a>
										<a data_category="29">Left Arms</a>
										<a data_category="28">Right Arms</a>
										<a data_category="30">Left Legs</a>
										<a data_category="31">Right Legs</a>
									</div>
								</div>
								<div id="AssetsContainer">
									<div id="StatusText">
										<b id="Loading" style="">Loading assets...</b>
										<b id="NoAssets" style="display: none"><img src="/images/noassets.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: -92px;margin-top: 23px;">Seems barren, try buying some stuff!</b>
									</div>
									<table id="Assets" hidden>										
									</table>
									<div id="Paginator" style="display: block;">
										<a id="BackPager" href="javascript:ANORRL.Character.PrevPage()" style="display: none;">&lt;&lt; Back</a> <input type="text" id="NumberPutter" maxlength="3"> of <span id="Counter">1</span> <a id="NextPager" href="javascript:ANORRL.Character.NextPage()" style="display: none;">Next &gt;&gt;</a>
									</div>
								</div>

							</div>
							<div id="CurrentlyWearing">
								<h4>Currently Wearing</h4>
								<div id="AssetsContainer">
									<div id="StatusText">
										<b id="Loading" style="">Loading assets...</b>
										<b id="NoAssets" style="display: none"><img src="/images/noassets.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: -92px;margin-top: 23px;">Seems barren, try buying some stuff!</b>
									</div>
									<table id="Assets" hidden>										
									</table>
								</div>
							</div>
						</div>
						<div id="CharacterRightwardSide">
							<div id="AvatarRender">
								<h4>Avatar Render</h4>
								<div id="RenderContainer">
									<img src="/images/avatar.png" width="260">
									<div style="margin-top: -10px;margin-bottom: 5px">
										<button style="width: 105px;">Create Outfit</button>
										<button style="width: 90px;">Re-Render</button>
									</div>
								</div>
							</div>
							<div id="BodyColours">
								<h4>Body Colours</h4>
								<div id="BodyColoursContainer">
									<div id="HeadRow">
										<button data_bodytype="0"></button>
									</div>
									<div id="TorsoRow">
										<button data_bodytype="2"></button><button data_bodytype="1"></button><button data_bodytype="3"></button>
									</div>
									<div id="LegsRow">
										<button data_bodytype="4"></button><button data_bodytype="5"></button>
									</div>
									<div id="BodyPartInfo">&nbsp;</div>
								</div>
							</div>
						</div>
					</div>
					<br style="display:block; clear:both;">
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>