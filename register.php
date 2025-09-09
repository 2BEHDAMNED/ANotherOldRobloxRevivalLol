<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user != null) {
		die(header("Location: /my/home"));
	}

	if(session_status() != PHP_SESSION_ACTIVE) {
		session_start();
	}
	

	if(isset($_POST['ANORRL$Signup$Username']) &&
	   isset($_POST['ANORRL$Signup$Password']) &&
	   isset($_POST['ANORRL$Signup$ConfirmPassword']) &&
	   isset($_POST['ANORRL$Signup$AccessKey']) &&
	   isset($_POST['ANORRL$Signup$Submit'])) {
		$username = trim($_POST['ANORRL$Signup$Username']);
		$password = trim($_POST['ANORRL$Signup$Password']);
		$confirm_password = trim($_POST['ANORRL$Signup$ConfirmPassword']);
		$accesskey = trim($_POST['ANORRL$Signup$AccessKey']);

		$result = UserUtils::RegisterUser($username, $password, $confirm_password, $accesskey);

		if($result == "success") {
			die(header("Location: /my/home"));
		} else {
			$_SESSION['signup_errors'] = $result;
			die(header("Location: /register"));
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/registerchecker.js"></script>
		<!-- TODO: Add images on left and right when you get the right images -->
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
				<div id="FormPanel">
						<form method="POST">
							<div>
								<h2>Registration</h2>
								<span>You should have been direct messaged by our discord bot for the access key!</span>
							</div>
							<div>
								<h4>Username</h4>
								<span class="Validator" id="v_username">
									<?php 
										if(isset($_SESSION['signup_errors'])) {
											echo $_SESSION['signup_errors']['username'];
										}
									?>
								</span>
								<input type="text" id="ANORRL_Signup_Username" name="ANORRL$Signup$Username" placeholder="a-z A-Z 0-9 and 3-20 characters!" maxlength="20" minlength="3" required>
							</div>
							<div>
								<h4>Password</h4>
								<span class="Validator" id="v_password">
									<?php 
										if(isset($_SESSION['signup_errors'])) {
											echo $_SESSION['signup_errors']['password'];
										}
									?>
								</span>
								<span class="Validator" id="v_confirmpassword"></span>
								<input type="password" id="ANORRL_Signup_Password"        name="ANORRL$Signup$Password"        placeholder="Should be a really solid one!" required>
								<input type="password" id="ANORRL_Signup_ConfirmPassword" name="ANORRL$Signup$ConfirmPassword" placeholder="Needs to match the first one!" required>
							</div>
							<div>
								<h4>Access Key</h4>
								<span class="Validator" id="v_access">
									<?php 
										if(isset($_SESSION['signup_errors'])) {
											echo $_SESSION['signup_errors']['accesskey'];
										}
									?>
								</span>
								<input type="password" id="ANORRL_Signup_AccessKey" name="ANORRL$Signup$AccessKey" placeholder="Check your discord for it!" maxlength="36" required>
							</div>
							<div style="margin-top: 10px;">
								<input type="submit" id="ANORRL_Signup_Submit" name="ANORRL$Signup$Submit" value="Register">
							</div>
						</form>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>
<?php 
	unset($_SESSION['login_errors']);
	session_destroy();
?>