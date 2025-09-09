<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user != null) {
		die(header("Location: /my/home"));
	}

	if(isset($_POST['ANORRL$Login$Username']) &&
	   isset($_POST['ANORRL$Login$Password']) &&
	   isset($_POST['ANORRL$Login$Submit'])) {
		
		$username = trim($_POST['ANORRL$Login$Username']);
		$password = trim($_POST['ANORRL$Login$Password']);

		$result = UserUtils::LoginUser($username, $password);

		if($result["login"] != "Incorrect details provided!") {
			die(header("Location: /my/home"));
		} else {
			$_SESSION['login_errors'] = $result;
			die(header("Location: /login"));
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
		<script src="/js/loginchecker.js"></script>
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
								<h2>Welcome back!</h2>
								<span>If you do not have an account then,<br><a href="/register">register here!</a></span>
								<span class="Validator">
									<?php 
										if(isset($_SESSION['login_errors'])) {
											echo $_SESSION['login_errors']['login'];
										}
									?>
								</span>
							</div>
							<div>
								<h4>Username</h4>
								<span class="Validator" id="v_username">
									<?php 
										if(isset($_SESSION['login_errors'])) {
											if(isset($_SESSION['login_errors']['username'])) {
												echo $_SESSION['login_errors']['username'];
											}
										}
									?>
								</span>
								<input type="text" id="ANORRL_Login_Username" name="ANORRL$Login$Username" minlength="3" maxlength="20" required>
							</div>
							<div>
								<h4>Password</h4>

								<span class="Validator" id="v_password">
									<?php 
										if(isset($_SESSION['login_errors'])) {
											if(isset($_SESSION['login_errors']['password'])) {
												echo $_SESSION['login_errors']['password'];
											}
										}
									?>
								</span>
								<input type="password" id="ANORRL_Login_Password" name="ANORRL$Login$Password" minlength="7" required>
							</p>
							<div>
								<input type="submit" id="ANORRL_Login_Submit" name="ANORRL$Login$Submit" value="Login">
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