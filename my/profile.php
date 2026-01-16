<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /login"));
	}


	if(isset($_POST['ANORRL$Update$Profile$Bio']) &&
	   isset($_POST['ANORRL$Update$Profile$Submit'])) {
		
		$result = $user->UpdateBio(trim($_POST['ANORRL$Update$Profile$Bio']));

		if($result['error']) {
			$_SESSION['ANORRL$Update$ProfileError'] = true;
			$_SESSION['ANORRL$Update$ProfileResult'] = $result['reason'];
			die(header("Location: /my/profile"));
		} else {
			die(header("Location: /users/".$user->id."/profile"));
		}

		
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Profile - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<style>
		</style>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<?php if(isset($_SESSION['ANORRL$Update$ProfileError']) && $_SESSION['ANORRL$Update$ProfileError']): ?>
					<div id="ErrorTime">Error: <?= $_SESSION['ANORRL$Update$ProfileResult'] ?></div>
					<?php endif ?>
					<form method="POST" class="FormBox">
						<div id="DetailsBox">
							<h3>About yourself</h3>
							<div id="FormStuff">
								
								<span>Who are you? What do you like etc etc</span>
								<textarea name="ANORRL$Update$Profile$Bio"><?= $user->blurb ?></textarea>
								<input type="submit" value="Update" name="ANORRL$Update$Profile$Submit">
							</div>
						</div>
					</form>
					<form method="POST" class="FormBox">
						<div id="DetailsBox" style="margin-top: 5px;">
							<h3>Get a look!</h3>
							<div id="FormStuff">
								<span>Profile Picture!!!!</span>
								<div class="FilePicker">
									<label for="thumbfiles">Choose file</label>
									<input id="thumbfiles" type="file" name="ANORRL$Update$Profile$Picture" accept="image/*">
									<label id="thumbfilename" >No file chosen</label>
								</div>
							</div>
						</div>
					</form>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>
<?php
	unset($_SESSION['ANORRL$Home$StatusError']);
	unset($_SESSION['ANORRL$Home$StatusResult']);
?>