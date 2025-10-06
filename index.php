<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Welcome to ANORRL!</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<style>
			h2, h3 {
				font-style:italic;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="IntroductionBox">
						<h2 style="width: -moz-available;">What is ANORRL?</h2>
						<p><b>ANORRL</b> is a 2013 "Old Roblox Revival" which aims to bring a fresh new look at the past!</p>
						<p><b>ANORRL</b> stands for <code>"<b>AN</b>other <b>O</b>ld <b>R</b>oblox <b>R</b>evival <b>L</b>ol"</code></p>
						<div style="margin-left: 5px;">
							<h3 style="width: -moz-available;">So what is a "Old Roblox Revival"</h3>
							<p>
								It is a project that aims to recreate and bring life into relatively old clients that ROBLOX has created in the past.<br>
								<b>Please note that</b>: These revivals do tend to be private (along with this one) as to ensure the maximum security because not only are These
								clients old, they are also insecure!
							</p>
						</div>
						
					</div>
					<div id="ShowcaseContainer">
						<h3>Showcase</h3>
						<div id="ShowcaseBox">
						<img id="Showcase" src="/images/avatar.png">
							<div id="Carousel">
								<img class="selected" src="/images/avatar.png">
								<img src="/images/avatar.png">
								<img src="/images/avatar.png">
								<img src="/images/avatar.png">
								<img src="/images/avatar.png">
							</div>
						</div>
						
					</div>
					<br style="clear:both">

					<div id="NewUsersContainer">
						<h3>New Users!</h3>
						<table id="NewUsersBox">
							<tr>
								<?php  
									$users = UserUtils::GetLatestUsers(7);
									$users_count = count($users);

									foreach($users as $user) {
										if($user instanceof User) {
											$user_id = $user->id;
											$user_name = $user->name;
											echo <<<EOT
												<td>
													<div class="User">
														<a href="/users/$user_id/profile">
															<img src="/images/avatar.png">
															<span>$user_name</span>
														</a>
													</div>
												</td>
											EOT;
										}
									}

									if($users_count < 7) {
										$count = 7 - $users_count;
										for($i = 0; $i < $count; $i++) {
											echo <<<EOT
												<td></td>
											EOT;
										}
 									}
								?>
							</tr>
						</table>
					</div>


					<div id="PopularGamesContainer">
						<h3>Popular Games</h3>
						<table id="PopularGamesBox">
							<td class="PopularGame">
								<table>
									<td id="ShowcaseBigImages">
										<img src="/images/avatar.png">
										<a id="Play" href=""></a>
									</td>
									<td id="ShowcaseDetails">
										<div id="NameAndCreator">
											<a href="" id="Name">Game Name</a>
											<a href="" id="Creator">Creator</a>
										</div>
										<code>
											Description hi hihi
										</code>
									</td>
								</table>
							</td>
							<td id="PopularGames">
								<div style="height: 201px;overflow: auto;">
									<img src="/images/avatar.png">
									<img src="/images/avatar.png">
									<img src="/images/avatar.png">
								</div>
								
							</td>
						</table>
					</div>
					
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>