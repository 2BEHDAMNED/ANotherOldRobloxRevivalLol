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

			#Users #SearchBox {
				width: 400px
			}

			#Users #Submit {
				width: 70px
			}

			#Users form {
				margin: 5px auto;
				margin-bottom: 15px;
				text-align: center; 
			}

			#Users table {
				border: 2px solid black;
				background: #222;
				padding: 5px;
				border-collapse: separate;
			}

			#Users tr {
				border-bottom: 1px solid black;
			}

			h2 {
				margin: 0;
				margin-top: 10px;
			}

			#Users {
				border: 2px solid black;
				background: #2a2a2a;
				padding: 10px;
			}

			#UsersNavLinks {
				text-align: center;
				margin: 15px;
				margin-bottom: 5px;
				display: none;
			}

			#FormPanel {
				display: none;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2>People</h2>
					<div id="Users">
						<form method="GET" id="FormPanel">
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
							<?php 
								$users = UserUtils::GetLatestUsers(50);

								foreach($users as $user) {
									$userid = $user->id;
									$username = $user->name;
									$userbio = $user->blurb;
									if(trim($userbio) == "")  {
										$userbio = "<b>No blurb set</b>";
									}
									echo <<<EOT
									<tr>
										<td style="text-align: center"><a href="/users/$userid/profile" title="$username"><img src="/images/avatar.png" width="64"></a></td>
										<td style="text-align: center"><img src="/images/OnlineStatusIndicator_IsOffline.gif"> <a href="/users/$userid/profile">$username</a></td>
										<td style="word-break: break-all;">$userbio</td>
										<td style="text-align: center">Online</td>
									</tr>
									EOT;
								}
							?>
							
						</table>
						<div id="UsersNavLinks">
							<a id="Back" href="">&lt;&lt; Back</a> <input type="number"> of 1 <a id="Next" href="">Next &gt;&gt;</a>
						</div>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>