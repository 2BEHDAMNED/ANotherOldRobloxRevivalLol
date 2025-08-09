<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$header_check_user = UserUtils::RetrieveUser();
?>
<?php if($header_check_user == null): ?>
<style>
	#Body, #Footer { margin-left: unset !important; }
</style>
<?php endif ?>
<div id="Header">
	<div id="Topbar">
		<a href="/" id="Logo">
			<img src="/images/spinner100x100.gif" data-src="/images/menus/IOTA128x.png" height="36" style="margin-left:10px;">
		</a>
		<?php if($header_check_user != null): ?>
		<div id="Links">
			<a href="/games">Games</a>
			<span class="Separator">&nbsp;|&nbsp;</span>
			<a href="/catalog">Catalog</a>
			<span class="Separator">&nbsp;|&nbsp;</span>
			<a href="/people">People</a>
		</div>
		<div id="Search">
			<input type="text" placeholder="Search...">
			<div id="Queries" style="display:none">
				<div id="Query">
					<a href="/games" typa="Games">Search "" in Games</a>
				</div>
				<div id="Query">
					<a href="/catalog" typa="Catalog">Search "" in Catalog</a>
				</div>
				<div id="Query">
					<a href="/people" typa="People">Search "" in People</a>
				</div>
			</div>
		</div>
		<div id="Controls">
			<a>
				<img src="/images/spinner16x16.gif" data-src="/images/icons/bling.png" height="32">
				<span>100000</span>
			</a>
			<a href="/my/messages">
				<img src="/images/spinner16x16.gif" data-src="">
			</a>
			<a id="SettingsButton">
				<img src="/images/spinner16x16.gif" data-src="/images/icons/settings.png">
			</a>
			<div id="SettingsPanel" style="display: none">
				<div id="Option"><a href="">Settings</a></div>
				<div id="Option"><a href="javascript:Iota.Logout()">Log out</a></div>
			</div>
		</div>
		<?php else: ?>
		<div id="Links">
			<a href="/register">Register</a>
			<span class="Separator">&nbsp;|&nbsp;</span>
			<a href="/">Login</a>
		</div>	
		<?php endif ?>
	</div>
	<?php if($header_check_user != null): ?>
	<div id="Sidebar">
		<div style="margin-top:5px; width:100%;"></div>
		
		<a href="/home" id="Navigation">
			<img src="/images/spinner16x16.gif" data-src="/images/icons/home_coloured.png">Home
		</a>
		<a href="/my/profile" id="Navigation">
			<img src="/images/spinner16x16.gif" data-src="/images/icons/profile_coloured.png">Profile
		</a>
		<a href="/my/friends" id="Navigation">
			<img src="/images/spinner16x16.gif" data-src="/images/icons/friends_coloured.png">Friends
		</a>
		<a href="/my/character" id="Navigation">
			<img src="/images/spinner16x16.gif" data-src="/images/icons/avatar_coloured.png">Avatar
		</a>
		<a href="/my/stuff" id="Navigation">
			<img src="/images/spinner16x16.gif" data-src="/images/icons/inventory_coloured.png">Inventory
		</a>
		<a href="/my/trades" id="Navigation">
			<img src="/images/spinner16x16.gif" data-src="/images/icons/trading_coloured.png">Trades
		</a>
	</div>
	<?php endif ?>
</div>