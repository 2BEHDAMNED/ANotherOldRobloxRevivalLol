<?php 
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/utilities/userutils.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/core/splasher.php";
	
	$nav_get_user = UserUtils::RetrieveUser();
?>
<?php if($nav_get_user == null): ?>
<div id="Header">
	<div id="Banner" style="font-family: sans-serif;">
		<div id="Options">
			<div id="Authentication">
				<span><a id="ctl00_lsLoginStatus" href="/Login/Default.aspx">Login</a></span>
			</div>
			<div id="Settings">
				<span id="ctl00_lSettings"></span>
			</div>
		</div>
		<div id="Logo" style="margin-bottom: -4px;">
			<a id="ctl00_rbxImage_Logo" title="GAMMA" href="/Gamma/Default.aspx" style="display:inline-block;height:60px;width:267px;cursor:pointer;">
				<img src="/images/Gamma/logo.png" border="0" id="img" alt="GAMMA" style="height:60px;">
			</a>
		</div>
		<div id="Alerts">
			<table style="width:100%;height:100%">
				<tbody>
					<tr>
						<td valign="middle">
							<a id="ctl00_rbxAlerts_SignupAndPlayHyperLink" class="SignUpAndPlay" href="/Login/New.aspx">
								<img src="/images/Gamma/SignupBanner.png" alt="Sign-up and Play!" border="0" blankurl="/images/Gamma/blank-267x70.gif">
							</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php Splasher::GenerateSplashHeader(); ?>
</div>
<?php endif ?>
<?php if($nav_get_user != null): ?>
<div id="Header">
	<div id="Banner" style="font-family: sans-serif;">
		<div id="Options">
			<div id="Authentication">
				<span>
					<span id="ctl00_lnLoginName">Logged in as <?= $nav_get_user->name ?> | </span>
					<a id="ctl00_lsLoginStatus" href="javascript:__doPostBack('ctl00$lsLoginStatus$ctl00','')">Logout</a>
				</span>
			</div>
			<div id="Settings">
				<span id="ctl00_lSettings">Age: 13+, Chat Mode: Safe</span>
			</div>
		</div>
		<div id="Logo">
			<a id="ctl00_rbxImage_Logo" title="GAMMA" href="/Gamma/Default.aspx" style="display:inline-block;height:60px;width:267px;cursor:pointer;">
				<img src="/images/Gamma/logo.png" border="0" id="img" alt="GAMMA" style="height:60px;">
			</a>
		</div>
	</div>
	<?php Splasher::GenerateSplashHeader(); ?>
</div>
<?php endif ?>