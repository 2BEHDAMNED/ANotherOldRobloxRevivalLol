<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/assetuploader.php';
	$user = UserUtils::RetrieveUser();

	$type = "none";
	if(isset($_GET['type'])) {
		$type = trim(strtolower($_GET['type']));
	}

	$validtypes = [
		"faces",
		"shirts",
		"tshirts",
		"pants",

		"audio",
		"decals",
		"models",
		"places",

		"gears",

		"meshes",
		"images",
		"lua",
		"hats",
		"animations"
	];

	if(count($_POST) != 0) {
		if(in_array($type, $validtypes)) {
			$timer = 61;
			if($user->GetLatestAssetUploaded() != null) {
				$difference = (time()-($user->GetLatestAssetUploaded()->created_at->getTimestamp()-3600));

				$timer = $difference;
			}
			
//			die(strval($timer));

			if($timer > 60) {
				if(isset($_POST['ANORRL$CreateAsset$Name']) &&
					isset($_POST ['ANORRL$CreateAsset$Description']) &&
					isset($_FILES['ANORRL$CreateAsset$File'])) {

					$result = null;
					$name = trim($_POST['ANORRL$CreateAsset$Name']);
					
					if(strlen(trim($name)) <= 0) {
						$result = ['error' => true, 'reason' => "You need to enter a name doofus!"];
					}

					$description = trim($_POST['ANORRL$CreateAsset$Description']);
					$public = isset($_POST['ANORRL$CreateAsset$Public']);
					$comments_enabled = isset($_POST['ANORRL$CreateAsset$CommentsEnabled']);
					$on_sale = isset($_POST['ANORRL$CreateAsset$OnSale']);
					
					if($result == null) {
						if($type == "images") {
							if($user->IsAdmin()) {
								$result = AssetUploader::UploadImage($name, $description, $_FILES['ANORRL$CreateAsset$File']);
							} else {
								$result = ['error' => true, 'reason' => "You are not authorised to perform this action!"];
							}
							
						} else if($type == "lua") {
							if($user->IsAdmin()) {
								$result = AssetUploader::UploadLua($name, $description, $_FILES['ANORRL$CreateAsset$File']);
							} else {
								$result = ['error' => true, 'reason' => "You are not authorised to perform this action!"];
							}
						}
						else if($type == "decals") {
							$result = AssetUploader::UploadDecal($name, $description, $_FILES['ANORRL$CreateAsset$File'], false, $public, $comments_enabled, $on_sale);
						}
						else if($type == "audio") {
							$result = AssetUploader::UploadAudio($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);
						}
						else if($type == "tshirts") {
							$result = AssetUploader::UploadTShirt($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);
						}
						else if($type == "faces") {
							$result = AssetUploader::UploadDecal($name, $description, $_FILES['ANORRL$CreateAsset$File'], true, $public, $comments_enabled, $on_sale);
						}
						else if($type == "shirts") {
							$result = AssetUploader::UploadShirt($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);
						}
						else if($type == "pants") {
							$result = AssetUploader::UploadPants($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);
						}
						else if($type == "meshes") {
							$result = AssetUploader::UploadMesh($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);
						}
						else if($type == "models") {
							$result = AssetUploader::UploadModel($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);
						}
						else if($type == "hats") {
							/*if($user->IsAdmin()) {
							} else {
								$result = ['error' => true, 'reason' => "You are not authorised to perform this action!"];
							}*/
							$result = AssetUploader::UploadHat($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);	
							
						}
						else if($type == "animations") {
							$result = AssetUploader::UploadAnimation($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);	
						}
						else if($type == "gears") {
							/*if($user->IsAdmin()) {
							} else {
								$result = ['error' => true, 'reason' => "You are not authorised to perform this action!"];
							}*/
							$result = AssetUploader::UploadGear($name, $description, $_FILES['ANORRL$CreateAsset$File'], $public, $comments_enabled, $on_sale);	
							
						}
						else {
							die("type found but not handled...");
						}
					}

					if(isset($result)) {
						if($result['error']) {
							$_SESSION['ANORRL$CreateAsset$Error'] = true;
							$_SESSION['ANORRL$CreateAsset$Result'] = $result['reason'];
						} else {
							$_SESSION['ANORRL$CreateAsset$Error'] = false;
							$_SESSION['ANORRL$CreateAsset$Result'] = $result['id'];
						}
						
						die(header("Location: /create/".$type));
					}
				}
			} else {
				$_SESSION['ANORRL$CreateAsset$Error'] = true;
				$_SESSION['ANORRL$CreateAsset$Result'] = "Dude chill the fuck down it ain't that deep bro..."; 
				die(header("Location: /create/".$type));
			}
			
		} else {
			die("Not valid type...");
		}
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
		<link rel="stylesheet" href="/css/new/main.css">
		<link rel="stylesheet" href="/css/new/create.css">
		<link rel="stylesheet" href="/css/new/stuff.css?v=1">
		<script src="/js/core/jquery.js"></script>
		<script src="/js/main.js?t=1771413807"></script>
		<script src="/js/create.js?t=1771413807"></script>
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
		</div>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="StuffContainer">
						<h1>Creation Panel</h1>
						<div id="StuffNavigation">							
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
								<li data_category="4"><a>Meshes</a></li>
								
								<hr>
								<li data_category="19"><a>Gears</a></li>
								<?php if($user->IsAdmin()): ?>
								<li data_category="32"><a>Packages</a></li>
								<hr>
								<li data_category="1"><a>Images</a></li>
								<li data_category="5"><a>Lua</a></li>
								<?php endif ?>
								<hr>
								<li data_category="24"><a>Animations</a></li>
								
							</ul>
						</div><div id="CreationPanel">
							
							<div id="UploadPanel">
								<h3>Upload <span id="TypaLabel"></span></h3>
								<div id="InfoWarning" style="display: none;">Must be in XML format NOT BINARY.</div>
								<?php if(isset($_SESSION['ANORRL$CreateAsset$Error']) && isset($_SESSION['ANORRL$CreateAsset$Result'])): ?>
									<?php if($_SESSION['ANORRL$CreateAsset$Error']): ?>
									<div id="ErrorTime">Error: <span id="Message"><?= $_SESSION['ANORRL$CreateAsset$Result'] ?></span></div>
									<?php else: ?>
									<div id="SuccessTime">Success! <span id="Message"><?= "Check it out <a href=\"/".Asset::FromID($_SESSION['ANORRL$CreateAsset$Result'])->GetURLTitle()."-item?id=". $_SESSION['ANORRL$CreateAsset$Result']."\">here!</a>"?></span></div>
									<?php endif ?>
								<?php endif ?>
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
											<td><label for="files">Choose file</label><input id="files" style="display:none;" type="file"  name="ANORRL$CreateAsset$File" required><label id="filename">No file chosen</label></td>
										</tr>
										<tr>
											<td><span style="margin-top: 8px;display: block;">Public</span></td>
											<td><input name="ANORRL$CreateAsset$Public" type="checkbox" style="margin-top: 8px;" checked></td>
										</tr>
										<tr>
											<td>Comments</td>
											<td><input name="ANORRL$CreateAsset$CommentsEnabled" type="checkbox" checked></td>
										</tr>
										<tr>
											<td>On Sale</td>
											<td><input name="ANORRL$CreateAsset$OnSale" type="checkbox"></td>
										</tr>
										<tr>
											<td><input type="submit" value="Upload" style="margin-top:10px" name="ANORRL$CreateAsset$Submit" onclick="$(this).attr('disabled', 'true'); document.forms[0].submit()"></td>
										</tr>
									</table>
								</form>
							</div>
							<div id="AssetsContainer" style="border-top: 2px solid black">
								<div id="StatusText">
									<b id="Loading" style="display: none">Loading assets...</b>
									<b id="NoAssets" style="display: none"><img src="/images/noassets.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: -92px;margin-top: 23px;">You have no <span id="AssetType"></span>!</b>
								</div>
							
								<table hidden></table>

								<div id="Paginator" style="display: none">
									<a href="javascript:ANORRL.Create.DeadvancePager()" id="PrevPager">&lt;&lt;Previous</a> Page <input maxlength="4"> of <span id="Pages">1</span> <a href="javascript:ANORRL.Create.AdvancePager()" id="NextPager">Next&gt;&gt;</a>
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

<?php
	unset($_SESSION['ANORRL$CreateAsset$Error']);
	unset($_SESSION['ANORRL$CreateAsset$Result']);
?>