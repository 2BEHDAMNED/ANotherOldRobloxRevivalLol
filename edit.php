<?php 
	$id = intval($_GET['id']);

	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();

	$asset = Asset::FromID($id);

	if($user == null) {
		die(header("Location: /catalog"));
	}

	if($asset != null) {
		$is_creator = $user->id == $asset->creator->id;

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

	if(isset($_POST['ANORRL$EditItem$Name']) &&
	   isset($_POST['ANORRL$EditItem$Description']) &&
	   isset($_POST['ANORRL$EditItem$Submit'])) {

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
			isset($_POST['ANORRL$EditItem$Cost$Lights'])) {
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

			die(header("Location: /".$asset->GetURLTitle()."-item?id=$id"));
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
		<script src="/js/main.js"></script>
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
			#ItemDetails a[type=submit] {
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
			#ItemDetails a[type=submit]:hover {
				text-decoration: underline;
				background: #161616;
				cursor: pointer;
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
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="EditContainer">
						<h2>Editing: <?= $asset->name ?></h2>
						<form id="ItemDetails" method="POST">
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
							
							<input type="submit" value="Update" name="ANORRL$EditItem$Submit">
							<a type="submit" href="/<?= $asset->GetURLTitle() ?>-item?id=<?= $id ?>" style="width:50px">Go Back</a>
							
						</form>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>