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
		</style>
	</head>
	<body>
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
										<a>Hats</a>
										<a>Faces</a>
										<a>T-Shirts</a>
										<a>Shirts</a>
										<a>Pants</a>
										<a>Gears</a>
										<a>Outfits</a>
									</div>
									<hr>
									<div>
										<a>Packages</a>
										<a>Heads</a>
										<a>Torsos</a>
										<a>Left Arms</a>
										<a>Right Arms</a>
										<a>Left Legs</a>
										<a>Right Legs</a>
									</div>
								</div>
								<div id="AssetsContainer">
									<table id="Assets">

									</table>
									<div id="Paginator">

									</div>
								</div>

							</div>
							<div id="CurrentlyWearing">
								<h4>Currently Wearing</h4>
								<div id="AssetsContainer">
									<table id="Assets">

									</table>
									<div id="Paginator">

									</div>
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