<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	if(!isset($header_data)) {
		$header_data = null;
	}
	$header_check_user = UserUtils::RetrieveUser($header_data);
	// 99999999 max
	
	function rollImage() {
		$pictures = array_diff(scandir($_SERVER['DOCUMENT_ROOT']."/images/randoms/"), array("..", "."));
		$rand_pic = 1+rand(0, count($pictures) - 1);
        	$rand_pic_name = $pictures[$rand_pic];

		if($rand_pic_name == "") {
			return rollImage();
        	}

		return $rand_pic_name;
	}

	$rand_pic = rollImage();
?>
<img src="/images/randoms/<?= $rand_pic ?>" style="position: fixed;bottom: 0px;left: 0px;width: 250px;z-index: 9999;">
<div id="Header">
	<?php if($header_check_user != null): 
		$pendingreqscount = $header_check_user->GetPendingFriendRequestsCount();	
	?>
	<div id="ProfileSign" logged="true">
		<img id="background" src="/images/header/signs/profile.png"> <!-- DO NOT FUCKING REMOVE -->
		<div id="UsernameRow">
			YOU ARE: <br>
			<a href="/users/<?= $header_check_user->id ?>/profile"><?= $header_check_user->name ?></a>
		</div>
		<hr>
		<div id="CreditsRow">
			
			<span title="Your pending requests"><a href="/my/friends?pending"><img src="/images/icons/messages<?= $pendingreqscount == 0 ? "" : "_notify" ?>.png"> <?= $pendingreqscount ?></a></span> <span class="Separator">|</span>
			<span title="Your friends"><a href="/my/friends"><img src="/images/icons/friends.png"> <?= $header_check_user->GetFriendsCount() ?></a></span>
			<hr>
			<span title="Message" style="width:auto">Thank you for trying this!<a href="/images/anorrl-smile.png" target="_blank" style="display: block;"><img src="/images/anorrl-smile.png" style="width: 42px;margin: 2px 0px;"></a></span>
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
		<a href="/my/home"      <?php if($_SERVER['SCRIPT_NAME'] == "/my/home.php"     		 ):?>selected<?php endif ?>>Home</a>
		<a href="/my/profile"   <?php if($_SERVER['SCRIPT_NAME'] == "/my/profile.php"  		 ):?>selected<?php endif ?>>Account</a>
		<a href="/my/character" <?php if($_SERVER['SCRIPT_NAME'] == "/my/character.php"		 ):?>selected<?php endif ?>>Character</a>
		<a href="/create/"      <?php if($_SERVER['SCRIPT_NAME'] == "/core/create.php" 		 ):?>selected<?php endif ?>>Create</a>
		<a href="/my/stuff"     <?php if($_SERVER['SCRIPT_NAME'] == "/my/stuff.php"    		 ):?>selected<?php endif ?>>Stuff</a>
		<a href="/download"     <?php if($_SERVER['SCRIPT_NAME'] == "/download/index.php"    ):?>selected<?php endif ?>>Download</a>
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
