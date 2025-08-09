<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user != null) {
		die(header("Location: /home"));
	}

	if(isset($_POST['Iota$Login$Username']) &&
	   isset($_POST['Iota$Login$Password']) &&
	   isset($_POST['Iota$Login$Submit'])) {
		
		$username = trim($_POST['Iota$Login$Username']);
		$password = trim($_POST['Iota$Login$Password']);

		$result = UserUtils::LoginUser($username, $password);

		if($result["login"] != "Incorrect details provided!") {
			die(header("Location: /home"));
		} else {
			$_SESSION['login_errors'] = $result;
			die(header("Location: /"));
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Iota</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css">
		<script src="/js/jquery.js"></script>
		<script src="/js/loginchecker.js"></script>
		<script src="/js/jquery.imageloader.js"></script>
		<script>
			$(function() {
				$('img').imageloader();
			});
		</script>
		<style>
			#Body {
				position:relative;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="WrapperBody" style="position:relative">
				<div id="Body">
					<pre>
<?php print_r($_SESSION) ?>
					</pre>
					<div id="FormPanel">
						
						<form method="POST">
							<p>
								<h2>Welcome back!</h2>
								<span>If you do not have an account then,<br><a href="/register">register here!</a></span>
								<span class="Validator">
									<?php 
										if(isset($_SESSION['login_errors'])) {
											echo $_SESSION['login_errors']['login'];
										}
									?>
								</span>
							</p>
							<p>
								<span>Username</span>
								<span class="Validator" id="v_username">
									<?php 
										if(isset($_SESSION['login_errors'])) {
											echo $_SESSION['login_errors']['username'];
										}
									?>
								</span>
								<input type="text" id="Iota_Login_Username" name="Iota$Login$Username" minlength="3" maxlength="20" required>
							</p>
							<p>
								<span>Password</span>
								<span class="Validator" id="v_password">
									<?php 
										if(isset($_SESSION['login_errors'])) {
											echo $_SESSION['login_errors']['password'];
										}
									?>
								</span>
								<input type="password" id="Iota_Login_Password" name="Iota$Login$Password" minlength="7" required>
							</p>
							<p>
								<input type="submit" id="Iota_Login_Submit" name="Iota$Login$Submit" value="Login">
							</p>
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