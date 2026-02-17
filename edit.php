<?php 
	$id = intval($_GET['id']);

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";
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
		AssetType::LUA,
		AssetType::HAT,
		AssetType::GEAR,
		AssetType::ANIMATION
	];

	function CheckMimeType($contents) {
		$file_info = new finfo(FILEINFO_MIME_TYPE);
		return $file_info->buffer($contents);
	} 

	if(
		isset($_POST['action']) &&
		$_POST["action"] == 'ANORRL$EditItem$ResetThumbnail'
	) {
		AssetVersion::GetLatestVersionOf($asset)->ResetThumbnail();
	}

	if(
		isset($_POST['ANORRL$EditItem$Audio$AssetID']) &&
		isset($_POST['ANORRL$EditItem$Audio$Submit']) && 
		$asset->type == AssetType::AUDIO
	) {
		$thumbassetid = intval($_POST['ANORRL$EditItem$Audio$AssetID']);
		$thumbasset = Asset::FromID($thumbassetid);

		if(
			$thumbasset->type == AssetType::DECAL ||
			$thumbasset->type == AssetType::IMAGE
		) {
			$asset->SetThumbnailTo($thumbasset);

			$_SESSION['ANORRL$EditItem$Success'] = true;

			die(header("Location: /edit?id=$id"));
		} else {
			$_SESSION['ANORRL$EditItem$Error'] = "ID must either be a decal or image!";
			$_SESSION['ANORRL$EditItem$Success'] = false;

			die(header("Location: /edit?id=$id"));
		}
	}

	if(isset($_POST['ANORRL$EditItem$Name']) &&
	   isset($_POST['ANORRL$EditItem$Description'])) {

		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$name = ReturnNotUnicodedString($_POST['ANORRL$EditItem$Name']);
		$description = ReturnNotUnicodedString($_POST['ANORRL$EditItem$Description']);
		$public = isset($_POST['ANORRL$EditItem$PublicBox']) ? 1 : 0;
		$comments_enabled = isset($_POST['ANORRL$EditItem$CommentsBox']) ? 1 : 0;
		$on_sale = isset($_POST['ANORRL$EditItem$OnSaleBox']) ? 1 : 0;

		if(strlen($name) <= 0) {
			$_SESSION['ANORRL$EditItem$Error'] = "Name must not be empty!";
			$_SESSION['ANORRL$EditItem$Success'] = false;

			die(header("Location: /edit?id=$id"));
		} else {
			$stmt = $con->prepare('UPDATE `assets` SET `asset_name` = ?, `asset_description` = ?, `asset_public` = ?, `asset_comments_enabled` = ?, `asset_onsale` = ?,`asset_lastedited` = now() WHERE `asset_id` = ?;');
			$stmt->bind_param('ssiiii', $name, $description, $public, $comments_enabled, $on_sale, $id);
			$stmt->execute();

			$_SESSION['ANORRL$EditItem$Success'] = true;

			if($asset->type == AssetType::PLACE &&
			   isset($_POST['ANORRL$EditItem$Place$ServerSize']) &&
			   isset($_POST['ANORRL$EditItem$Place$Year'])
			   ) {

				$copylocked = isset($_POST['ANORRL$EditItem$Place$Copylocked']) ? 1 : 0;

				$original = isset($_POST['ANORRL$EditItem$Place$Original']) ? 1 : 0;
				$gears = isset($_POST['ANORRL$EditItem$Place$Gears']) ? 1 : 0;
				$server_size = intval($_POST['ANORRL$EditItem$Place$ServerSize']);
				
				if($server_size < 0) {
					$server_size = $asset->server_size;
				}

				$allUsersCount = count(UserUtils::GetAllUsers());

				if($server_size > $allUsersCount) {
					$server_size = $allUsersCount;
				}

				//$year = $asset->year->ordinal();
				$year = PlaceYear::index($_POST['ANORRL$EditItem$Place$Year'])->ordinal();

				$stmt = $con->prepare('UPDATE `asset_places` SET `place_year` = ?, `place_copylocked` = ?, `place_serversize` = ?, `place_original` = ?, `place_gears_enabled` = ? WHERE `place_id` = ?;');
				$stmt->bind_param('siiiii', $year, $copylocked, $server_size, $original, $gears, $id);
				$stmt->execute();

				if(isset($_FILES['ANORRL$EditItem$Place$ThumbnailFile'])) {
					$file = $_FILES['ANORRL$EditItem$Place$ThumbnailFile'];
					
					if($file['error'] == 0 && $file['size'] <= 5242880) {
						$contents = file_get_contents($file['tmp_name']);
						$type = CheckMimeType($contents);

						if(str_starts_with($type,"image/")) {
							$image = imagecreatefromstring($contents);
							imagesavealpha($image, true);

							if(imagesx($image) > 128 && imagesy($image) > 96) {
								if(file_exists($_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$id")) {
									unlink($_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$id");
								}

								imagepng($image, $_SERVER['DOCUMENT_ROOT']."/../assets/thumbs/$id");
							}
						}
					}
				}
			}

			die(header("Location: /".$asset->GetURLTitle()."-item?id=$id"));
		}

		
	} else if(isset($_FILES['ANORRL$PublishAsset$File']) &&
	   isset($_POST['ANORRL$PublishAsset$Submit'])) {

		if(in_array($asset->type, $versioning_types)) {
			if($asset->type == AssetType::LUA) {
				$result = AssetUploader::UpdateLua($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			}
			else if($asset->type == AssetType::MESH) {
				$result = AssetUploader::UpdateMesh($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			}
			else if($asset->type == AssetType::PLACE) {
				$result = AssetUploader::UpdatePlace($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			}
			else if($asset->type == AssetType::HAT) {
				$result = AssetUploader::UpdateHat($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			}
			else if($asset->type == AssetType::MODEL) {
				$result = AssetUploader::UpdateModel($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			} 
			else if($asset->type == AssetType::GEAR) {
				$result = AssetUploader::UpdateGear($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			}
			else if($asset->type == AssetType::ANIMATION) {
				$result = AssetUploader::UpdateAnimation($asset->id, $_FILES['ANORRL$PublishAsset$File']);
			}
			else {
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
		<link rel="stylesheet" href="/css/new/main.css">
		<link rel="stylesheet" href="/css/new/forms.css">
		<link rel="stylesheet" href="/css/new/item/edit.css?v=1">
		<script src="/js/core/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/edit.js?t=<?= time() ?>"></script>
		<script>
			$(function() {
				$(".VersionPicker").each(function() {
					$(this).attr("title", "click to make this the current version");
					$(this).on("click", function() {
						alert("later...");
					})
				})
			})

			function RemoveThumbnail() {
				$.post("", {"action": "ANORRL$EditItem$ResetThumbnail"}, function() {
					window.location.reload();
				})
			}
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
							<form method="POST" enctype="multipart/form-data">
								<div id="DetailStack">
									<h4>Information</h4>
									<div id="Table">
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
											<?php if(!in_array($asset->type, $not_selling_types)): ?>
											<tr>
												<td><label for="OnSaleCheckbox">On Sale</label></td>
												<td><input id="OnSaleCheckbox" name="ANORRL$EditItem$OnSaleBox" type="checkbox" <?php if($asset->onsale): ?>checked<?php endif ?>></td>
											</tr>
											<?php endif ?>
											<tr>
												<td>Enable Comments</td>
												<td><input type="checkbox" name="ANORRL$EditItem$CommentsBox" <?php if($asset->comments_enabled): ?>checked<?php endif ?>></td>
											</tr>
											
										</table>
									</div>
									
								</div>
								
								<?php if($asset->type == AssetType::PLACE): ?>
								<div id="DetailStack">
									<h4 style="margin-top: 10px">Place Settings</h4>
									<table id="Table">
										<tr id="PlaceYear">
											<td style="vertical-align: middle;">Year</td>
											<td>
												<select name="ANORRL$EditItem$Place$Year">
													<option value="2016">ANORRL (2016)</option>
													<!--<option value="2008">2008 (Gamma)</option>-->
													<option value="2013">2013</option>
													<option value="2010">2010</option>
													<!--<option value="2012">2012</option>-->
													
												</select>
											</td>
										</tr>
										<tr>
											<td>Server Size</td>
											<td><input type="number" name="ANORRL$EditItem$Place$ServerSize" value="<?= $asset->server_size ?>"></td>
										</tr>
										<tr>
											<td>Copylocked</td>
											<td><input type="checkbox" name="ANORRL$EditItem$Place$Copylocked" <?php if($asset->copylocked): ?>checked<?php endif ?>></td>
										</tr>
										<tr>
											<td>Original</td>
											<td><input type="checkbox" name="ANORRL$EditItem$Place$Original" <?php if($asset->is_original): ?>checked<?php endif ?>></td>
										</tr>
										<tr>
											<td>Gears</td>
											<td><input type="checkbox" name="ANORRL$EditItem$Place$Gears" <?php if($asset->gears_enabled): ?>checked<?php endif ?>></td>
										</tr>
										<tr>
											<td>Thumbnail! (<a href="javascript:RemoveThumbnail()">Remove</a>)</td>
											<td class="FilePicker">
												<label for="thumbfiles">Choose file</label>
												<input id="thumbfiles" type="file" name="ANORRL$EditItem$Place$ThumbnailFile" accept="image/*">
												<label id="thumbfilename" >No file chosen</label>
											</td>
										</tr>
									</table>
								</div>
								<?php endif ?>
								

								<input type="submit" value="Update" name="ANORRL$EditItem$Submit">
								
							</form>
							<?php if($asset->type == AssetType::AUDIO): ?>
							<form method="POST" enctype="multipart/form-data">
								<div id="DetailStack">
									<h4>Album Cover</h4>
									<div id="Table">
										<span style="display: block;margin-bottom: 10px;font-size: 10px;color: #999;font-style: italic;">This is for if you want to add a neat little cover for your audio! (Decal/Image ids only!)</span>
										<div style="width:294px;margin: 0 auto;">
											<h4 style="margin: 0;width: 254px;">Current Thumbnail Photo</h4>
											<img style="width: 290px;border: 2px solid black;background: #1a1a1a;" src="/thumbs/?id=<?= $asset->id ?>&sxy=290">
											<div class="FilePicker" style="display: block;margin-top: 10px;text-align:center">
												<input type="number" name="ANORRL$EditItem$Audio$AssetID" style="width: 100px;" placeholder="Decal ID" value="">
												<input type="submit" name="ANORRL$EditItem$Audio$Submit"  style="margin: 0;display: inline-block;"     value="Update">
												<a href="javascript:RemoveThumbnail()">Remove...</a>
											</div>
										</div>
									</div>
									
								</div>
							</form>
							<?php endif ?>
							<?php if(in_array($asset->type, $versioning_types)): ?>
							<form method="POST" id="DetailStack" enctype="multipart/form-data">
								<h4 style="margin-top: 10px">Publish Version</h4>
								<table style="padding-bottom: 37px;" id="Table">
									<tr>
										<td><span style="font-size:11px; color:lightgray;font-weight: bold;"></span></td>
									</tr>
									<tr>
										<td>File</td>
										<td class="FilePicker">
											<label for="files">Choose file</label>
											<input id="files" type="file"  name="ANORRL$PublishAsset$File" accept="" required>
											<label id="filename" >No file chosen</label>
										</td>
									</tr>
									
								</table>
								<input type="submit" value="Publish" style="margin-top:-33px;margin-bottom: 18px;" name="ANORRL$PublishAsset$Submit">
							</form>
							<div id="DetailStack">
								<h4 style="margin-top: 10px">Version History</h4>
								
								<div class="PublicDomainRow">
									<span style="font-size:11px; color:lightgray;font-weight: bold;display: block;margin-bottom: -25px;margin-top: 12px;margin-left: 10px;">Click one of the buttons below to revert to a previous version of this item.</span>
									<table cellspacing="10" style="padding-top: 22px" id="Table">
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
													
													// TODO: TODO...
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