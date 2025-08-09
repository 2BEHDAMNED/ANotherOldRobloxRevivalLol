<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user != null) {
		die(header("Location: /home"));
	}

	session_start();

	if(isset($_POST['Iota$Signup$Username']) &&
	   isset($_POST['Iota$Signup$Password']) &&
	   isset($_POST['Iota$Signup$ConfirmPassword']) &&
	   isset($_POST['Iota$Signup$AccessKey']) &&
	   isset($_POST['Iota$Signup$Submit'])) {
		$username = trim($_POST['Iota$Signup$Username']);
		$password = trim($_POST['Iota$Signup$Password']);
		$confirm_password = trim($_POST['Iota$Signup$ConfirmPassword']);
		$accesskey = trim($_POST['Iota$Signup$AccessKey']);

		$result = UserUtils::RegisterUser($username, $password, $confirm_password, $accesskey);

		if($result == "success") {
			die(header("Location: /home"));
		} else {
			$_SESSION['signup_errors'] = $result;
			die(header("Location: /register"));
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
		<script src="/js/registerchecker.js"></script>
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

			#FormPanel input[type=password] {
				margin-top:10px;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="WrapperBody" style="position:relative">
				<div id="Body">
					<div id="FormPanel">
						<form method="POST">
							<p>
								<h2>Registration</h2>
								<span>You should have been direct messaged by our discord bot for the access key!</span>
							</p>
							<p>
								<span>Username</span>
								<span class="Validator" id="v_username">
									<?php 
										if(isset($_SESSION['signup_errors'])) {
											echo $_SESSION['signup_errors']['username'];
										}
									?>
								</span>
								<input type="text" id="Iota_Signup_Username" name="Iota$Signup$Username" placeholder="a-z A-Z 0-9 and 3-20 characters!" maxlength="20" minlength="3" required>
							</p>
							<p>
								<span>Password</span>
								<span class="Validator" id="v_password">
									<?php 
										if(isset($_SESSION['signup_errors'])) {
											echo $_SESSION['signup_errors']['password'];
										}
									?>
								</span>
								<span class="Validator" id="v_confirmpassword"></span>
								<input type="password" id="Iota_Signup_Password"        name="Iota$Signup$Password"        placeholder="Should be a really solid one!" required>
								<input type="password" id="Iota_Signup_ConfirmPassword" name="Iota$Signup$ConfirmPassword" placeholder="Needs to match the first one!" required>
							</p>
							<p>
								<span>Access Key</span>
								<span class="Validator" id="v_access">
									<?php 
										if(isset($_SESSION['signup_errors'])) {
											echo $_SESSION['signup_errors']['accesskey'];
										}
									?>
								</span>
								<input type="password" id="Iota_Signup_AccessKey" name="Iota$Signup$AccessKey" placeholder="Check your discord for it!" maxlength="36" required>
							</p>
							<p>
								<input type="submit" id="Iota_Signup_Submit" name="Iota$Signup$Submit" value="Register">
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
	session_destroy();
?>