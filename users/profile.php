<?php

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
		die(header("Location: /home"));
	}

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /"));
	}

	// No id parameter? GET OUT!
	if(!isset($_GET['id'])) {
		die(header("Location: /home"));
	}

	$get_user = User::FromID(intval($_GET['id']));

	if($get_user == null) {
		die(header("Location: /home"));
	}

	if(isset($_GET['redirect']) && $_GET['redirect'] == "true") {
		die(header("Location: /users/".$get_user->id."/profile"));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?= $user->name ?> - Iota</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css">
		<script src="/js/jquery.js"></script>
		<script src="/js/jquery.imageloader.js"></script>
		<script src="/js/main.js"></script>
		<style>
			#UserStatsContainer, #UserInfoContainer {
				margin: 0 auto;
				width: 900px;
				border: 2px solid gray;
				height:fit-content;
				border-radius: 6px;
				background: white;
				margin-top:10px;
			}

			#UserInfoContainer #PaddingContainer {
				padding: 10px;
			}

			#UserInfoContainer #ProfileInfo, #UserInfoContainer #ProfileImage {
				float: left;
			}

			#UserInfoContainer #ProfileInfo {
				width: 670px;
				
			}

			#UserStatsContainer #LeftContainer,
			#UserStatsContainer #RightContainer {
				width: 429px;
				float: left;
				padding: 10px;
			}

			#UserStatsContainer #RightContainer {
				border-left: 1px solid lightgray;
			}

			#UserInfoContainer #Stats #Blurb {
				border: 1px solid gray;
				background: #eaeaea;
				border-radius: 4px;
				padding: 10px;
				margin-top: 5px;
				height: 110px;
			}

			#UserInfoContainer #Stats div > a {
				text-decoration: none;
				color: #2d2d2d;
				transition: font-size .25s, font-weight .25s ease-in;
			}

			#UserInfoContainer #Stats div > a:hover {
				font-size:15px;
				font-weight: 600;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="WrapperBody">
				<div id="Body">
					<div id="UserInfoContainer">
						<div id="PaddingContainer">
							<div id="ProfileImage">
								<img src="/images/avatar.png" width="200">
							</div>
							<div id="ProfileInfo">
								<h2 style="margin: 5px auto;"><?= $get_user->name ?></h2>
								<div id="Stats">
									<div style="height: 24px;">
										<a href="/users/<?= $get_user->id ?>/friends">
											<b><?= $get_user->GetFriendsCount() ?></b> Friends
										</a> | 
										<a href="/users/<?= $get_user->id ?>/followers">
											<b><?= $get_user->GetFollowersCount() ?></b> Followers
										</a> | 
										<a href="/users/<?= $get_user->id ?>/following">
											<b><?= $get_user->GetFollowingCount() ?></b> Following
										</a>
									</div>
									<div id="Blurb">
										<?php 
											if(strlen($get_user->blurb) == 0) {
												echo "<b>This user has no blurb!</b>";
											} else {
												echo $get_user->blurb;
											}
										?>
									</div>
								</div>
							</div>
							<br clear="all">
						</div>
					</div>
					<div id="UserStatsContainer">
						<div id="LeftContainer">

						</div>
						<div id="RightContainer">
							
						</div>
						<br clear="all">
					</div>

				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>