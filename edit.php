<?php 
	$id = intval($_GET['id']);

	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetuploader.php";

	$user = UserUtils::RetrieveUser();

	$asset = Asset::FromID($id);

	if($user == null) {
		die(header("Location: /catalog"));
	}

	if($asset != null) {

		if($asset->type == AssetType::PLACE) {
			$asset = Place::FromID($id);
		}
		$is_creator = $user->id == $asset->creator->id || $user->IsAdmin();

		if(!$is_creator) {
			die(header("Location: /catalog"));
		}

		$asset_description = $asset->description;
	} else {
		die(header("Location: /my/stuff"));
	}

	function ReturnNotUnicodedString(string $contents) {
		$blockedchars = array('𒐫', '‮', '﷽', '𒈙', '⸻ ', '꧅');
		return str_replace($blockedchars, '', trim($contents));
	}

	$not_selling_types = [
		AssetType::PLACE,
		AssetType::LUA,
	];

	$versioning_types = [
		AssetType::PLACE,
		AssetType::MESH,
		AssetType::MODEL,
		AssetType::LUA
	];

	if(isset($_POST['ANORRL$EditItem$Name']) &&
	   isset($_POST['ANORRL$EditItem$Description'])) {

		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$name = ReturnNotUnicodedString($_POST['ANORRL$EditItem$Name']);
		$description = ReturnNotUnicodedString($_POST['ANORRL$EditItem$Description']);
		$public = isset($_POST['ANORRL$EditItem$PublicBox']) ? 1 : 0;
		$comments_enabled = isset($_POST['ANORRL$EditItem$CommentsBox']) ? 1 : 0;

		if(strlen($name) < 4) {
			$_SESSION['ANORRL$EditItem$Error'] = "Name must not be shorter than 4 characters!";
			$_SESSION['ANORRL$EditItem$Success'] = false;

			die(header("Location: /edit?id=$id"));
		} else {
			if(isset($_POST['ANORRL$EditItem$OnSaleBox']) &&
			isset($_POST['ANORRL$EditItem$Cost$Cones']) &&
			isset($_POST['ANORRL$EditItem$Cost$Lights']) && !in_array($asset->type, $not_selling_types)) {
				$cost_cones = intval($_POST['ANORRL$EditItem$Cost$Cones']);
				$cost_lights = intval($_POST['ANORRL$EditItem$Cost$Lights']);

				if($cost_cones < 0) { $cost_cones = 0; }
				if($cost_lights < 0) { $cost_lights = 0; }
				
				$stmt = $con->prepare('UPDATE `assets` SET `asset_name` = ?, `asset_description` = ?, `asset_public` = ?, `asset_comments_enabled` = ?, `asset_onsale` = 1, `asset_cost_cones` = ?,`asset_cost_lights` = ?,`asset_lastedited` = now() WHERE `asset_id` = ?;');
				$stmt->bind_param('ssiiiii', $name, $description, $public, $comments_enabled, $cost_cones, $cost_lights, $id);
				$stmt->execute();

				$_SESSION['ANORRL$EditItem$Success'] = true;
			} else {
				$stmt = $con->prepare('UPDATE `assets` SET `asset_name` = ?, `asset_description` = ?, `asset_public` = ?, `asset_comments_enabled` = ?, `asset_onsale` = 0, `asset_lastedited` = now() WHERE `asset_id` = ?;');
				$stmt->bind_param('ssiii', $name, $description, $public, $comments_enabled, $id);
				$stmt->execute();

				$_SESSION['ANORRL$EditItem$Success'] = true;
			}

			if($asset->type == AssetType::PLACE &&
			   isset($_POST['ANORRL$EditItem$Place$ServerSize']) && 
			   isset($_POST['ANORRL$EditItem$Place$ChatType'])) {

				//TODO: Add Genres
				$copylocked = isset($_POST['ANORRL$EditItem$Place$Copylocked']) ? 1 : 0;
				$server_size = intval($_POST['ANORRL$EditItem$Place$ServerSize']);
				$chattype = intval($_POST['ANORRL$EditItem$Place$ChatType']);

				if($chattype < 0 || $chattype > 2) {
					$chattype = $asset->chattype->ordinal();
				}

				if($server_size < 0) {
					$server_size = $asset->server_size;
				}


				$stmt = $con->prepare('UPDATE `asset_places` SET `place_copylocked` = ?, `place_serversize` = ?, `place_chattype` = ? WHERE `place_id` = ?;');
				$stmt->bind_param('iiii', $copylocked, $server_size, $chattype, $id);
				$stmt->execute();
			}

			die(header("Location: /".$asset->GetURLTitle()."-item?id=$id"));
		}

		
	} else if(isset($_FILES['ANORRL$PublishAsset$File']) &&
	   isset($_POST['ANORRL$PublishAsset$Submit'])) {

		if(in_array($asset->type, $versioning_types)) {
			if($asset->type == AssetType::LUA) {
				$result = AssetUploader::UpdateLua($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			} else if($asset->type == AssetType::MESH) {
				$result = AssetUploader::UpdateMesh($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			} else if($asset->type == AssetType::PLACE) {
				$result = AssetUploader::UpdatePlace($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			}  else {
				die("Type was recognised but not implemented...");
			}
			
			if($result['error']) {
				$_SESSION['ANORRL$EditItem$Error'] = $result['reason'];
				$_SESSION['ANORRL$EditItem$Success'] = false;

				die(header("Location: /edit?id=$id"));
			} else {
				die(header("Location: /".$asset->GetURLTitle()."-item?id=$id"));
			}
		} else {
			die("Yo, what are you doing??");
		}

	}

	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Editing: <?= htmlspecialchars($asset->name, ENT_QUOTES) ?> - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/edit.js"></script>
		<style>

			h1, h2, h3, h4 {
				margin: 0;
			}

			h2 {
				margin-top: 10px;
			}

			#EditContainer #ItemDetails {
				background: #2c2c2c;
				border: 2px solid black;
				padding: 10px;
				width: 866px;
			}

			#EditContainer #ItemDetails table {
				border: 2px solid black;
				padding: 10px;
				background: #222;
				width:512px;
				
			}

			#ItemDetails input[type=text],
			#ItemDetails input[type=number],
			#ItemDetails select,
			#ItemDetails textarea {
				border: 2px solid black;
				background: #444;
				padding: 2px 4px;
				color: white;
				resize: vertical;
			}

			#ItemDetails input[type=text],
			#ItemDetails textarea {
				width: 320px;
			}

			#ItemDetails input[type=submit],
			#ItemDetails a[type=submit],
			#ItemDetails label[for=files] {
				border: 2px solid black;
				background: black;
				color: white;
				padding: 4px 8px;
				font-weight: bold;
				font-family: punk;
				margin: 10px auto;
				margin-bottom: 0px;
				display: block;
			}

			#ItemDetails input[type=submit]:hover,
			#ItemDetails input[type=submit]:hover,
			#ItemDetails label[for=files]:hover {
				text-decoration: underline;
				background: #161616;
				cursor: pointer;
			}

			#ItemDetails label#filename {
				margin-left: 5px;
			}

			#EditContainer #ItemDetails table td {
				width: 140px;
				min-width: 140px;
				vertical-align: top;
			}

			#EditContainer #ItemDetails #DetailStack {
				margin: 0 auto;
				width: 510px;
			}
		</style>
		<script>
			$(function() {
				$(".VersionPicker").each(function() {
					$(this).attr("title", "click to make this the current version");
				})
			})
		</script>
		<?php if(isset($_SESSION['ANORRL$EditItem$Success']) && !$_SESSION['ANORRL$EditItem$Success']): ?>
		<script>
			window.alert("<?= $_SESSION['ANORRL$EditItem$Error'] ?>");
			window.location.reload();
		</script>
		<?php endif ?>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="EditContainer">
						<h2>Editing: <?= $asset->name ?></h2>
						<div id="ItemDetails">
							<form method="POST">
								<div id="DetailStack">
									<h4>Information</h4>
									<table>
										<tr>
											<td>Name</td>
											<td><input type="text" name="ANORRL$EditItem$Name" value="<?= $asset->name ?>" minlength="3" maxlength="128"></td>
										</tr>
										<tr>
											<td>Description</td>
											<td><textarea style="height: 50px;" name="ANORRL$EditItem$Description"><?= $asset->description ?></textarea></td>
										</tr>
										<tr>
											<td>Public</td>
											<td><input type="checkbox" name="ANORRL$EditItem$PublicBox" <?php if($asset->public): ?>checked<?php endif ?>></td>
										</tr>
										<tr>
											<td>Enable Comments</td>
											<td><input type="checkbox" name="ANORRL$EditItem$CommentsBox" <?php if($asset->comments_enabled): ?>checked<?php endif ?>></td>
										</tr>
									</table>
								</div>
								<?php if($asset->type == AssetType::PLACE): ?>
								<div id="DetailStack">
									<h4 style="margin-top: 10px">Place Settings</h4>
									<table>
										
										<tr>
											<td>Server Size</td>
											<td><input type="number" name="ANORRL$EditItem$Place$ServerSize" value="<?= $asset->server_size ?>"></td>
										</tr>
										<tr>
											<td>Chat Type</td>
											<td>
												<select name="ANORRL$EditItem$Place$ChatType" id="cars">
													<option value="1">Classic</option>
													<option value="2">Bubble</option>
													<option value="0">Both</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Copylocked</td>
											<td><input type="checkbox" name="ANORRL$EditItem$Place$Copylocked" <?php if($asset->copylocked): ?>checked<?php endif ?>></td>
										</tr>
										
									</table>
								</div>
								<?php endif ?>
								<?php if(!in_array($asset->type, $not_selling_types)): ?>
								<div id="DetailStack">
									<h4 style="margin-top: 10px">Money&nbsp;&nbsp;money&nbsp;&nbsp;money...</h4>
									<table>
										<tr>
											<td><span style="font-size:11px; color:lightgray;font-weight: bold;">Set currency to 0 to make it not use that currency...</span></td>
										</tr>
										<tr>
											<td><label for="OnSaleCheckbox">On Sale</label><input id="OnSaleCheckbox" name="ANORRL$EditItem$OnSaleBox" type="checkbox" <?php if($asset->onsale): ?>checked<?php endif ?>></td>

											<td class="ThePricing" id="TrafficCones">Traffic Cones<input type="number" name="ANORRL$EditItem$Cost$Cones" value="<?= $asset->cost_cones ?>"></td>
											<td class="ThePricing" id="TrafficLights">Traffic Lights<input type="number" name="ANORRL$EditItem$Cost$Lights" value="<?= $asset->cost_lights ?>"></td>
										</tr>
									</table>
								</div>
								<?php endif ?>

								<input type="submit" value="Update" name="ANORRL$EditItem$Submit">
								
							</form>
							<?php if(in_array($asset->type, $versioning_types)): ?>
							<form method="POST" id="DetailStack" enctype="multipart/form-data">
								<h4 style="margin-top: 10px">Publish Version</h4>
								<table style="padding-bottom: 37px;">
									<tr>
										<td><span style="font-size:11px; color:lightgray;font-weight: bold;"></span></td>
									</tr>
									<tr>
										<td>File</td>
										<td><label for="files" style="width: 72px;margin: 0;display: inline;">Choose file</label><input id="files" style="display:none;" type="file"  name="ANORRL$PublishAsset$File" accept="" required><label id="filename" >No file chosen</label></td>
									</tr>
									
								</table>
								<input type="submit" value="Publish" style="margin-top:-33px" name="ANORRL$PublishAsset$Submit">
							</form>
							<div id="DetailStack">
								<h4 style="margin-top: 10px">Version History</h4>
								
								<div class="PublicDomainRow">
									<span style="font-size:11px; color:lightgray;font-weight: bold;display: block;margin-bottom: -25px;margin-top: 12px;margin-left: 10px;">Click one of the buttons below to revert to a previous version of this item.</span>
									<table cellspacing="10" style="padding-top: 22px">
										<tr>
											<td>
												&nbsp;
											</td>
											<td align="center">
												<strong>Version</strong>
											</td>
											<td align="center">
												<strong>Created</strong>
											</td>
										</tr>

										<?php
											$versions = $asset->GetAllVersions();
											
											$version_id = count($versions);
											$current_version = $asset->current_version;

											foreach($versions as $version) {
												if($version instanceof AssetVersion) {
													
													$version_date = $version->publish_date->format('d/m/Y H:i:s A');
													
													
													$versionpicker = <<<EOT
													<td><a class="VersionPicker" href="">[ Make Current ]</a></td>
													EOT; 

													if($current_version == $version_id) {
														$versionpicker = <<<EOT
														<td><b>Current Version</b></td>
														EOT; 
													}

													echo <<<EOT
													<tr>
														$versionpicker
														<td align="center">$version_id</td>
														<td align="center">$version_date</td>
													</tr>
													EOT;

													$version_id--;
												}
											}
										?>

									</table>
								</div>
							</div>
							<?php endif ?>
						
							<a type="submit" href="/<?= $asset->GetURLTitle() ?>-item?id=<?= $id ?>" style="width:50px">Go Back</a>
						</div>
						
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>
<?php 
	unset($_SESSION['ANORRL$EditItem$Success']);
	unset($_SESSION['ANORRL$EditItem$Error']);
?>