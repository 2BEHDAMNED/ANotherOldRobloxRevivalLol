<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /login"));
	}

	include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

	$stmt = $con->prepare("SELECT * FROM `friends` WHERE (`sender` = ? OR `reciever` = ?) ORDER BY `status` ASC");
	$stmt->bind_param("ii", $user->id, $user->id);
	$stmt->execute();

	$result_stmt = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your Character - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/friends.js?t=<?= time() ?>"></script>
		<style>
			h2, h4 {
				margin: 0;
			}

			h4 {
				margin-top: 5px;
			}

			#FriendsContainer {
				border: 2px solid black;
				background: #222;
				padding: 10px;
			}

			#FriendsContainer > table {
				width: 100%;
			}

			#FriendsContainer > table td {
				vertical-align: top;
			}

			#FriendsContainer .Friend {
				border: 2px solid black;
				background: #1a1a1a;
				width: 100px;
				text-align: center;
				padding: 10px;
				margin: 0 auto;
			}

			#FriendsContainer .Friend a > * {
				display: block;
				margin: 0 auto;
				font-weight: bold;
				text-overflow: ellipsis;
				overflow: hidden;
			}

			#FriendsContainer .Friend a > span {
				margin-top: 5px;
			}

			#FriendsContainer .Friend #ControlPanel > * {
				*display: block;
				margin: 2px auto;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2>Your Friends</h2>
					<div id="FriendsContainer">
						<?php if($result_stmt->num_rows != 0): ?>
						<table>
						<?php 
							$count = 0;
							while($row = $result_stmt->fetch_assoc()) {
								if($count == 0) {
									echo "<tr>";
								}

								$controlPanel = "";

								$friendo = $row['reciever'] == $user->id ? User::FromID($row['sender']) : User::FromID($row['reciever']);
								
								if($friendo == null) {
									// There's a person that's non existent somehow!
									continue;
								}

								$fid = $friendo->id;

								if($row['status'] == 1) {
									$controlPanel = <<<EOT
									<hr>
									<div id="ControlPanel" style="font-size: 11px">
										<a href="javascript:ANORRL.Friends.Remove($fid)">Remove</a>
									</div>
									EOT;
								} else {
									if($row['reciever'] == $user->id) {
										$controlPanel = <<<EOT
										<hr>
										<div id="ControlPanel" style="font-weight: bold;font-size: 13px">
											<a href="javascript:ANORRL.Friends.Accept($fid)">Accept</a>
											<span>|</span>
											<a href="javascript:ANORRL.Friends.Reject($fid)">Reject</a>
										</div>
										EOT;
									} else {
										$controlPanel = <<<EOT
										<hr>
										<div id="ControlPanel" style="font-weight: bold;font-size: 13px">
											<a href="javascript:ANORRL.Friends.Cancel($fid)">Cancel</a>
										</div>
										EOT;
									}
									
								}
								
								$profile = $friendo->setprofilepicture ? "profile" : "player";
								
								$fname = $friendo->name;
								echo <<<EOT
								<td>
									<div class="Friend">
										<a href="/users/$fid/profile" title="$fname" target="_blank">
											<img src="/thumbs/$profile?id=$fid&sxy=100">
											<span>$fname</span>
										</a>
										$controlPanel
									</div>
										
								</td>
								EOT;

								$count++;

								if($count%6 == 0) {
									echo "</tr>";
								}
							}
						?>
						</table>
						<?php endif ?>
					</div>
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>
