<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$header_check_user = UserUtils::RetrieveUser();
?>
<div id="Header">
	<?php if($header_check_user != null): ?>
	<div id="ProfileSign" logged="true">
		<img id="background" src="/images/header/signs/profile.png"> <!-- DO NOT FUCKING REMOVE -->
		<div id="UsernameRow">
			YOU ARE: <br>
			<a href="/users/<?= $header_check_user->id ?>/profile"><?= $header_check_user->name ?></a>
		</div>
		<hr>
		<div id="CreditsRow">
			<span title="Traffic Cones (ROBUX)"><img src="/images/icons/traffic_cone.png"> 1</span> <span class="Separator">|</span>
			<span title="Traffic Lights (TIX)"><img src="/images/icons/traffic_light.png"> 1</span>

			<hr>
			
			<span title="Your messages"><a href="/my/messages"><img src="/images/icons/messages.png"> 1</a></span> <span class="Separator">|</span>
			<span title="Your friends"><a href="/my/friends"><img src="/images/icons/friends.png"> 1</a></span>
		</div>
	</div>
	<a id="LogoutSign" href="/">LOGOUT</a>
	<?php else: ?>
	<div id="ProfileSign" logged="true">
		<img id="background" src="/images/header/signs/profile.png"> <!-- DO NOT FUCKING REMOVE -->
		<a href="" id="RegisterSign">Register</a>
		<img src="/images/sign_2way.png" style="width: 72px;padding: 10px 0;padding-top: 30px;padding-bottom:5px;z-index: 2;position: relative;">
		<a href="" id="LoginSign">Login</a>
	</div>
	<?php endif ?>
	<div id="Logo">
		<a href="/">
			<img src="/images/header/logo.png">
		</a>
	</div>
	
	<div id="Links" >
		<?php if($header_check_user != null): ?><a href="/users/<?= $header_check_user->id ?>/profile">Profile</a><?php endif ?>
		<a href="/games">Games</a>
		<a href="/catalog">Catalog</a>
		<a href="/people">People</a>
		<a href="/forums">Forums</a>
	</div>
</div>