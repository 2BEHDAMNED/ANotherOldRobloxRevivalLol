<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetuploader.php";

	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die("Hey have you tried logging in before doing this? <br><a href='javascript:window.close()'>No...</a>");
	}

	function ReturnNotUnicodedString(string $contents) {
		$blockedchars = array('𒐫', '‮', '﷽', '𒈙', '⸻ ', '꧅');
		return str_replace($blockedchars, '', trim($contents));
	}

	$publishnewplace = isset($_POST['ANORRL$IDE$Public$Place$CreateNewPlace']);
?>
<?php if(!$publishnewplace): ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Publish to a Place - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<script src="/js/publish.js"></script>
		<style>

			h1, h2, h3, h4 {
				margin: 0;
			}

			h2 {
				margin-top: 10px;
			}

			#PublishContainer #ItemDetails {
				background: #000;
				border: 2px solid black;
				padding: 10px;
				width: 866px;
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

			#PublishContainer #ItemDetails table td {
				width: 140px;
				min-width: 140px;
				vertical-align: top;
			}

			#PublishContainer #ItemDetails #DetailStack {
				margin: 0 auto;
				width: 510px;
			}

			#Body {
				background: none;
				border: none;
			}

			#PublishPlaces {
				padding: 5px;
				border: 2px solid black;
				background: #222;
			}

			#PublishPlaces .Place {
				border: 2px solid black;
				background: #1d1d1d;
				width:235px;
				padding: 5px;
				text-align: center;
				margin: 3px;
				display: inline-block;
			}

			#PublishPlaces .Place * {
				display:block;
				width:230px;
			}

			#PublishPlaces .Place img {
				border: 2px solid black;
				background: #111;
			}

			#PublishPlaces .Place span {
				margin-top: 5px;
				font-weight: bold;
			}

			#PublishPlaces .Place:hover,
			#PublishPlaces .Place:hover span {
				text-decoration: underline;
				cursor: pointer;
			}

			#PublishPlaces .Place:hover {
				border-color: white;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<div id="Body">
				<div id="BodyContainer">
					<div id="PublishContainer">
						<h2>Publish your lovely little place...</h2>
						<div id="ItemDetails">
							<form method="POST">
								<input name="ANORRL$IDE$Publish$Place$Action" hidden>
								<div id="PublishPlaces">
									<div class="Place" data-placeid="createnew">
										<img src="/images/ide/createnewplace.png">
										<span>Create a New Place</span>
									</div>
									<div class="Place" data-placeid="createnew">
										<img src="/images/ide/createnewplace.png">
										<span>Create a New Place</span>
									</div>
									<div class="Place" data-placeid="createnew">
										<img src="/images/ide/createnewplace.png">
										<span>Create a New Place</span>
									</div>
									<div class="Place" data-placeid="createnew">
										<img src="/images/ide/createnewplace.png">
										<span>Create a New Place</span>
									</div>
									<div class="Place" data-placeid="createnew">
										<img src="/images/ide/createnewplace.png">
										<span>Create a New Place</span>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php else: ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Publish Place - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<style>

			h1, h2, h3, h4 {
				margin: 0;
			}

			h2 {
				margin-top: 10px;
			}

			#PublishContainer #ItemDetails {
				background: #2c2c2c;
				border: 2px solid black;
				padding: 10px;
				width: 866px;
			}

			#PublishContainer #ItemDetails table {
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

			#PublishContainer #ItemDetails table td {
				width: 140px;
				min-width: 140px;
				vertical-align: top;
			}

			#PublishContainer #ItemDetails #DetailStack {
				margin: 0 auto;
				width: 510px;
			}

			#Body {
				background: none;
				border: none;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<div id="Body">
				<div id="BodyContainer">
					<div id="PublishContainer">
						<h2>Publish your lovely little place...</h2>
						<div id="ItemDetails">
							<form method="POST">
								<div id="DetailStack">
									<h4>Information</h4>
									<table>
										<tr>
											<td>Name</td>
											<td><input type="text" name="ANORRL$IDE$Publish$Place$Name" value="My Place" minlength="3" maxlength="128"></td>
										</tr>
										<tr>
											<td>Description</td>
											<td><textarea style="height: 50px;" name="ANORRL$IDE$Publish$Place$Description"></textarea></td>
										</tr>
										<tr>
											<td>Public</td>
											<td><input type="checkbox" name="ANORRL$IDE$Publish$Place$PublicBox" checked></td>
										</tr>
										<tr>
											<td>Enable Comments</td>
											<td><input type="checkbox" name="ANORRL$IDE$Publish$Place$CommentsBox" checked></td>
										</tr>
									</table>
								</div>
								<div id="DetailStack">
									<h4 style="margin-top: 10px">Place Settings</h4>
									<table>
										<tr>
											<td>Server Size</td>
											<td><input type="number" name="ANORRL$IDE$Publish$Place$ServerSize" value="12"></td>
										</tr>
										<tr>
											<td>Chat Type</td>
											<td>
												<select name="ANORRL$IDE$Publish$Place$Place$ChatType" id="cars">
													<option value="1">Classic</option>
													<option value="2">Bubble</option>
													<option value="0">Both</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Copylocked</td>
											<td><input type="checkbox" name="ANORRL$IDE$Publish$Place$Copylocked" checked></td>
										</tr>
									</table>
									<input type="submit" value="Publish" name="ANORRL$IDE$Publish$Place$Submit" style="text-align: center">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php endif ?>