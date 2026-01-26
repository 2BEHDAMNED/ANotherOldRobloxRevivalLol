<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/classes/comment.php';

	function IsRewrite() {
		if(!empty($_SERVER['IIS_WasUrlRewritten']))
			return true;
		else if(array_key_exists('HTTP_MOD_REWRITE',$_SERVER))
			return true;
		else if( array_key_exists('REDIRECT_URL', $_SERVER))
			return true;
		else
			return false;
	}

	if(!IsRewrite()) {
		die(header("Location: /my/home"));
	}

	// No id parameter? GET OUT!
	if(!isset($_GET['id'])) {
		die(header("Location: /my/home"));
	}

	$get_user = User::FromID(intval($_GET['id']));

	if($get_user == null) {
		die(header("Location: /my/home"));
	}
	
	$user = UserUtils::RetrieveUser($get_user);

	$header_data = $get_user;

	include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

	$stmt = $con->prepare("SELECT * FROM `follows` WHERE `followed` = ? ORDER BY `followed_at` ASC");
	$stmt->bind_param("i", $get_user->id);
	$stmt->execute();

	$result_stmt = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $get_user->name ?>'s Followers - ANORRL</title>
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
		</style>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2><?= $get_user->name ?>'s Followers</h2>
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

								$friendo = User::FromID($row['follower']);
								
								if($friendo == null) {
									// There's a person that's non existent somehow!
									continue;
								}

								$fid = $friendo->id;
								
								$profile = $friendo->setprofilepicture ? "profile" : "player";

								$status = $friendo->IsOnline() ? "Online" : "Offline";
								
								$fname = $friendo->name;
								echo <<<EOT
								<td>
									<div class="Friend">
										<a href="/users/$fid/profile" title="$fname" target="_blank">
											<img src="/thumbs/$profile?id=$fid&sxy=100">
											<span><img src="/images/OnlineStatusIndicator_Is$status.png"> $fname</span>
										</a>
									</div>
								</td>
								EOT;

								$count++;

								if($count == $result_stmt->num_rows && $count%6 < 6) {
									for($i = 0; $i < 6-($count%6); $i++) {
										echo "<td style=\"width:142px;\"></td>";
									}
								}

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
