<?php 
	session_start();
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/asset.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/utilities/userutils.php";
	
	$user = UserUtils::RetrieveUser();

	if(!$user->IsAdmin()) {
		http_response_code(401);
		die("Not authorised");
	}

	$assets = Asset::GetAllUncheckedAssets();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<style>
			body {
				background-color: lightgray;
				margin:15px;
			}
			
			#ModBox {
				border:1px solid black;
				background-color: rgb(230, 230, 230);
				padding:15px;
				float:left;
			}

			#ModBox h2 {
				margin-top:0;
			}

			#ModBox h3 {
				margin-top:0;
			}

			.Asset {
				float:left;
				width: 150px;
				text-align: center;
			}
		</style>
		<script src="/js/jquery.js" type="text/javascript"></script>
		<script>
			function checkAndRemove() {
				if($('.Asset').length == 0) {
					$("#noassetthang").css("display", "block");
				} else {
					$("#noassetthang").css("display", "none");
				}
			}

			function reject(pass_id) {
				$.post( "/Admin/components/assetstuff", { id: pass_id, type: "deny" }).done(function( data ) {
					$("#asset_"+pass_id).remove();
					checkAndRemove();
				});
			}

			function render(pass_id) {
				$.post( "/Admin/components/assetstuff", { id: pass_id, type: "render" }).done(function( data ) {
					$("#asset_"+pass_id).find("#AssetThumbnail").attr("src","/thumbs/?id="+pass_id+"&sxy=120&t="+Math.random());
					checkAndRemove();
				});
			}
			
			function accept(pass_id) {
				$.post( "/Admin/components/assetstuff", { id: pass_id, type: "accept" }).done(function( data ) {
					$("#asset_"+pass_id).remove();
					checkAndRemove();
				});
			}
		</script>

		<style>
			table {
				border-collapse: collapse;
			}

			td {
				vertical-align: top;
				border-bottom: 1px solid black;
				padding: 10px 0px;
			}

			th {
				border-bottom: 1px solid;
			}

			table {
				border: 1px solid;
			}
		</style>
	</head>
	<body>
		<div id="ModBox">
			<h2>Asset Approval Panel</h2>
			<h3>Rules</h3>
			<ul>
				<li>No roblox reuploads (like if someone uploads Flood Escape then reject cuz thats obviously not theirs)</li>
				<li>No NSFW (and other common sense things like racial content)</li>
				<li>No freaky shit (like femboys and stuff)</li>
			</ul>
			<br>
			<table>
				<tr>
					<th>Thumbnail</th>
					<th style="width:150px;">Info</th>
					<th style="width:150px;">Creator</th>
					<th style="width:500px">Description</th>
					<th>Actions</th>
				</tr>
				<?php 
					if(count($assets) != 0) {
						foreach($assets as $asset) {
							$asset_id = $asset->id;
							$asset_name = $asset->name;
							$creator = $asset->creator;
							$creator_id = $creator->id;
							$creator_name = $creator->name;
							$asset_thumburl = "/thumbs/?id=$asset_id&sxy=120";
							$asset_desc = str_replace(PHP_EOL, "<br>", trim($asset->description));
							$asset_urlname = $asset->GetURLTitle();

							if($asset_desc == "") {
								$asset_desc = "<b>No description provided</b>";
							}

							
							$no_rendering_types = [
								AssetType::AUDIO,
								AssetType::BADGE,
								AssetType::DECAL,
								AssetType::TSHIRT
							];

							$dontevershow = [
								AssetType::IMAGE
							];

							if(in_array($asset->type, $dontevershow)) {
								continue;
							}

							$asset_renderline = "";
							if(!in_array($asset->type, $no_rendering_types)) {
								$asset_renderline = <<<EOT
								<button id="submit" onclick="render($asset_id); return false;">Render</button>
								EOT;
							}

							echo <<<EOT
								<tr id="asset_$asset_id">
									<td>
										<div style="padding: 15px"><a href="/$asset_urlname-item?id=$asset_id" target="_blank"><img src="/thumbs/?id=$asset_id&sxy=100" id="AssetThumbnail"></a></div>
									</td>
									<td style="vertical-align: middle;text-align: center;">
										<div><a href="/$asset_urlname-item?id=$asset_id" target="_blank">$asset_name</a></div>
									</td>
									<td style="text-align: center;">
										<div><a href="/users/$creator_id/profile" target="_blank"><img src="/images/avatar.png" style="width:100px"><br>$creator_name</a></div>
									</td>
									<td>
										<div>
											$asset_desc
										</div>
									</td>
									<td style="vertical-align: middle;padding: 10px;">
										<button id="submit" onclick="accept($asset_id); return false;">Approve</button>
										<button id="submit" onclick="reject($asset_id); return false;">Reject</button>
										$asset_renderline
									</td>
								</tr>
							EOT;
						}
					}
				?>
			</table>
			<?php if(count($assets) == 0): ?>
			<p id="noassetthang">No assets for you buddy.</p>
			<?php endif ?>
		</div>
	</body>
</html>