<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	if(!isset($header_data)) {
		$header_data = null;
	}
	$header_check_user = UserUtils::RetrieveUser($header_data);
	// 99999999 max
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
			<span title="Your messages" style="width:auto"><img src="/images/icons/messages_notify.png">Thank you for trying this!</span>
		</div>
	</div>
	<a id="LogoutSign" href="javascript:ANORRL.Logout()">LOGOUT</a>
	<?php else: ?>
	<div id="ProfileSign" logged="false">
		<img id="background" src="/images/header/signs/profile.png"> <!-- DO NOT FUCKING REMOVE -->
		<a href="/register" id="RegisterSign">Register</a>
		<img src="/images/sign_2way.png" style="width: 72px;padding: 10px 0;padding-top: 30px;padding-bottom:5px;z-index: 2;position: relative;">
		<a href="/login" id="LoginSign">Login</a>
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
	</div>
	
	<?php if($header_check_user != null): ?>
	<div id="UserLinks" >
		<a href="/my/home"      <?php if($_SERVER['SCRIPT_NAME'] == "/my/home.php"     ):?>selected<?php endif ?>>Home</a>
		<?php if($header_check_user->IsAdmin()): ?>
		<a href="/Admin/">Admin</a>
		<?php endif ?>
		<a href="/my/profile"   <?php if($_SERVER['SCRIPT_NAME'] == "/my/profile.php"  ):?>selected<?php endif ?>>Account</a>
		<a href="/my/character" <?php if($_SERVER['SCRIPT_NAME'] == "/my/character.php"):?>selected<?php endif ?>>Character</a>
		<a href="/my/friends"   <?php if($_SERVER['SCRIPT_NAME'] == "/my/friends.php"  ):?>selected<?php endif ?>>Friends</a>
		<a href="/create/"      <?php if($_SERVER['SCRIPT_NAME'] == "/core/create.php" ):?>selected<?php endif ?>>Create</a>
		<a href="/my/stuff"     <?php if($_SERVER['SCRIPT_NAME'] == "/my/stuff.php"    ):?>selected<?php endif ?>>Stuff</a>
		<a href="/download/"     <?php if($_SERVER['SCRIPT_NAME'] == "/download/index.php"    ):?>selected<?php endif ?>>Download</a>
	</div>
	<?php endif ?>
	
</div>
<div class="DisplayMobileWarning" style="display: none">
	<div id="MobileWarningText">
		<h1>HEADS UP!</h1>
		<p>This isn't optimised for mobile devices, best to use a pc (as this was designed for that)</p>
		<button onclick="ANORRL.HideMobileWarning()">Continue anyways...</button>
	</div>
</div>