<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /"));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>People - Iota</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/jquery.imageloader.js"></script>
		<script src="/js/main.js"></script>
		<style>
			#Users #SearchBox,#Submit {
				border-radius: 4px;
				border: 1px solid gray;
				padding:3px;
			}

			#Users #SearchBox {
				width: 400px
			}

			#Users #Submit {
				width: 70px
			}

			#Users form {
				margin: 0 auto;
				text-align: center;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="WrapperBody">
				<div id="Body">
					<div id="Users">
						<form method="GET">
							<input id="SearchBox" name="query" type="text" placeholder="Look for users lol">
							<input id="Submit" type="submit" value="Search">
						</form>
						<table>
							<tr>
								<th width="80" style="border:0">Avatar</th>
								<th width="200" style="border:0">Name</th>
								<th style="border:0; width: 600px; max-width: 600px;">Blurb</th>
								<th width="150" style="border:0">Active</th>
							</tr>
							<tr>
								<td style="text-align: center"><a href="/users/1/profile" title="Zlysie"><img src="/images/avatar.png" width="64"></a></td>
								<td><img src="/images/OnlineStatusIndicator_IsOffline.gif"> <a href="/users/1/profile">Zlysie</a></td>
								<td style="word-break: break-all;">Blurb</td>
								<td style="text-align: center">Online</td>
							</tr>
						</table>
						<div id="UsersNavLinks">
							<a id="Back" href="">&lt;</a><a href="">1</a><a href="">2</a><a href="">3</a><a href="">4</a><a href="">5</a><a id="Next" href="">&gt;</a>
						</div>
					</div>
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>