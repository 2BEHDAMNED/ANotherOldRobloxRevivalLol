<!DOCTYPE html>
<html>
	<head>
		<title>Index RAHHH</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css">
		<script src="/js/jquery.js"></script>
		<script src="/js/jquery.backgroundSize.js"></script> 
		<script>
			$(function() {
				//$("#Header #Links").css( "background-size", "contain" ); 
			})
			
		</script>
	</head>
	<body>
		<div id="Container">
			<div id="Header">
				<div id="ProfileSign">
					<div id="UsernameRow">
						YOU ARE: <br>
						<a href="/">aaaaaaaaaaaaaaaaaaaa</a>
					</div>
					<hr style="margin:2px 0px;color:white">
					<div id="CreditsRow">
						<span><img src="/images/icons/traffic_cone.png"> 100</span><span class="Separator">|</span><span><img src="/images/icons/traffic_light.png"> 100</span>

						<hr style="margin:2px 0px;color:white">
						<span><img src="/images/icons/messages.png"> 100</span><span class="Separator">|</span><span><img src="/images/icons/messages_notify.png"> 100</span>
					</div>
				</div>
				<div id="Logo">
					<a href="/">
						<img src="/images/header/logo.png">
					</a>
				</div>
				<a id="LogoutSign" href="/">LOGOUT</a>
				<div id="Links" >
					<a href="/user/1/profile">Profile</a>
						<a href="/games">Games</a>
						<a href="/catalog">Catalog</a>
						<a href="/people">People</a>
						<a href="/forums">Forums</a>
					
				</div>
			</div>
			<div id="Body">
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>