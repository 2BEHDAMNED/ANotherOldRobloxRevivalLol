<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	$header_check_user = $user;

	function CreateProfile(int $id, string $description) {
		$profileUser = User::FromID($id);
		
		$name = $profileUser->name;
		$online = $profileUser->IsOnline();
		$thumbs = $profileUser->GetAutoThumbsUrl();

		if($profileUser != null) {
			echo <<<EOT
			<td>
				<div>
					<a href="/users/$id/profile">
						<img src="$thumbs&sxy=128">
						<span>$name</span>
					</a>
				</div>
				<div style="text-align: center; border: 2px solid black; background: #1a1a1a; padding: 10px;">
					$description
				</div>
			</td>
			EOT;
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Page - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<style>
			h1, h2, h3, h4 {
				margin: 0px;
			}
			#CreditsContainer {
				border: 2px solid black;
				background: #222;
				padding: 10px;
			}

			#CreditsContainer .Note {
				font-style:italic;
				color: #AAA;
			}

			#CreditsContainer table {
				width: 100%;
				table-layout: fixed
			}

			#CreditsContainer table a {
				text-align: center;
			}

			#CreditsContainer table a > * {
				display: block;
			}

			#CreditsContainer table a > img {
				margin: 0 auto;
				border: 2px solid black;
			}

			#CreditsContainer table a > span {
				margin-top: 5px;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2>Credits!</h2>
					<div id="CreditsContainer">
						<div class="Note">This is a page dedicated to the people who has ever contributed to this projects (AND MAKING IT COOLER!)</div>
						<hr>
						<table>
							<tr>
								<?php CreateProfile(
									41,
									"Created the ANORRL studio icons you see!!! (and also other things like corescripts and stuff)"
								); ?>

								<?php CreateProfile(
									43,
									"Helped me find and fix up some vulnerabilities, along with supplying help for datastores!!!"
								); ?>

								<?php CreateProfile(
									2,
									"Helped me sanity check this when I was first testing this lol"
								); ?>
							</tr>
						</table>
						<hr>
						<p>But these aren't the only ones, no.</p>
						<p>Thank you everyone (in this list below) for playing on/participating in the community for this project!</p>
						<div style="text-align: center">
						<?php
							$excludelist = [
								1, 2, 41, 43
							];
							foreach(UserUtils::GetAllUsers() as $user) {
								if(in_array($user->id, $excludelist)) {
									continue;
								}
								$userid = $user->id;
								$username = $user->name;

								echo <<<EOT
								<a href="/users/$userid/profile">$username</a> 
								EOT;
							}
						?>
						</div>
						<p>Even if you don't say much, even if you don't play that much. Just knowing you like and play on this at all means enough to me :]</p>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>