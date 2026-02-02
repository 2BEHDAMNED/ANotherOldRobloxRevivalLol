<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width"/>
		<link rel="stylesheet" href="/css/toolbox.css"/>
		<script type="text/javascript" src="/js/jquery.js"></script>
		<script type="text/javascript" src="/js/jquery-migrate.js"></script>
		<script type="text/javascript" src="/js/toolbox.js"></script>

		<script type="text/javascript">
			if (typeof(Roblox) === "undefined") { Roblox = {}; }
			Roblox.Endpoints = Roblox.Endpoints || {};
			Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
			Roblox.Endpoints.Urls['/api/item.ashx'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/api/item.ashx';
			Roblox.Endpoints.Urls['/asset/'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/asset/';
			Roblox.Endpoints.Urls['/client-status/set'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/client-status/set';
			Roblox.Endpoints.Urls['/client-status'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/client-status';
			Roblox.Endpoints.Urls['/game/'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/';
			Roblox.Endpoints.Urls['/game-auth/getauthticket'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/game-auth/getauthticket';
			Roblox.Endpoints.Urls['/game/edit.ashx'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/edit.ashx';
			Roblox.Endpoints.Urls['/game/getauthticket'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/getauthticket';
			Roblox.Endpoints.Urls['/game/get-hash'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/get-hash';
			Roblox.Endpoints.Urls['/game/placelauncher.ashx'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/placelauncher.ashx';
			Roblox.Endpoints.Urls['/game/preloader'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/preloader';
			Roblox.Endpoints.Urls['/game/report-stats'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/report-stats';
			Roblox.Endpoints.Urls['/game/report-event'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/report-event';
			Roblox.Endpoints.Urls['/game/updateprerollcount'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/game/updateprerollcount';
			Roblox.Endpoints.Urls['/login/default.aspx'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/login/default.aspx';
			Roblox.Endpoints.Urls['/my/avatar'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/my/avatar';
			Roblox.Endpoints.Urls['/my/money.aspx'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/my/money.aspx';
			Roblox.Endpoints.Urls['/navigation/userdata'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/navigation/userdata';
			Roblox.Endpoints.Urls['/chat/chat'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/chat/chat';
			Roblox.Endpoints.Urls['/chat/data'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/chat/data';
			Roblox.Endpoints.Urls['/presence/users'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/presence/users';
			Roblox.Endpoints.Urls['/presence/user'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/presence/user';
			Roblox.Endpoints.Urls['/friends/list'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/friends/list';
			Roblox.Endpoints.Urls['/navigation/getcount'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/navigation/getCount';
			Roblox.Endpoints.Urls['/regex/email'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/regex/email';
			Roblox.Endpoints.Urls['/catalog/browse.aspx'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/catalog/browse.aspx';
			Roblox.Endpoints.Urls['/catalog/html'] = 'https://web.archive.org/web/20170814015848/https://search.roblox.com/catalog/html';
			Roblox.Endpoints.Urls['/catalog/json'] = 'https://web.archive.org/web/20170814015848/https://search.roblox.com/catalog/json';
			Roblox.Endpoints.Urls['/catalog/contents'] = 'https://web.archive.org/web/20170814015848/https://search.roblox.com/catalog/contents';
			Roblox.Endpoints.Urls['/catalog/lists.aspx'] = 'https://web.archive.org/web/20170814015848/https://search.roblox.com/catalog/lists.aspx';
			Roblox.Endpoints.Urls['/catalog/items'] = 'https://web.archive.org/web/20170814015848/https://search.roblox.com/catalog/items';
			Roblox.Endpoints.Urls['/asset-hash-thumbnail/image'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/asset-hash-thumbnail/image';
			Roblox.Endpoints.Urls['/asset-hash-thumbnail/json'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/asset-hash-thumbnail/json';
			Roblox.Endpoints.Urls['/asset-thumbnail-3d/json'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/asset-thumbnail-3d/json';
			Roblox.Endpoints.Urls['/asset-thumbnail/image'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/asset-thumbnail/image';
			Roblox.Endpoints.Urls['/asset-thumbnail/json'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/asset-thumbnail/json';
			Roblox.Endpoints.Urls['/asset-thumbnail/url'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/asset-thumbnail/url';
			Roblox.Endpoints.Urls['/asset/request-thumbnail-fix'] = 'https://web.archive.org/web/20170814015848/https://assetgame.roblox.com/asset/request-thumbnail-fix';
			Roblox.Endpoints.Urls['/avatar-thumbnail-3d/json'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/avatar-thumbnail-3d/json';
			Roblox.Endpoints.Urls['/avatar-thumbnail/image'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/avatar-thumbnail/image';
			Roblox.Endpoints.Urls['/avatar-thumbnail/json'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/avatar-thumbnail/json';
			Roblox.Endpoints.Urls['/avatar-thumbnails'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/avatar-thumbnails';
			Roblox.Endpoints.Urls['/avatar/request-thumbnail-fix'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/avatar/request-thumbnail-fix';
			Roblox.Endpoints.Urls['/bust-thumbnail/json'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/bust-thumbnail/json';
			Roblox.Endpoints.Urls['/group-thumbnails'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/group-thumbnails';
			Roblox.Endpoints.Urls['/groups/getprimarygroupinfo.ashx'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/groups/getprimarygroupinfo.ashx';
			Roblox.Endpoints.Urls['/headshot-thumbnail/json'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/headshot-thumbnail/json';
			Roblox.Endpoints.Urls['/item-thumbnails'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/item-thumbnails';
			Roblox.Endpoints.Urls['/outfit-thumbnail/json'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/outfit-thumbnail/json';
			Roblox.Endpoints.Urls['/place-thumbnails'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/place-thumbnails';
			Roblox.Endpoints.Urls['/thumbnail/asset/'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/asset/';
			Roblox.Endpoints.Urls['/thumbnail/avatar-headshot'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/avatar-headshot';
			Roblox.Endpoints.Urls['/thumbnail/avatar-headshots'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/avatar-headshots';
			Roblox.Endpoints.Urls['/thumbnail/user-avatar'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/user-avatar';
			Roblox.Endpoints.Urls['/thumbnail/resolve-hash'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/resolve-hash';
			Roblox.Endpoints.Urls['/thumbnail/place'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/place';
			Roblox.Endpoints.Urls['/thumbnail/get-asset-media'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/get-asset-media';
			Roblox.Endpoints.Urls['/thumbnail/remove-asset-media'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/remove-asset-media';
			Roblox.Endpoints.Urls['/thumbnail/set-asset-media-sort-order'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/set-asset-media-sort-order';
			Roblox.Endpoints.Urls['/thumbnail/place-thumbnails'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/place-thumbnails';
			Roblox.Endpoints.Urls['/thumbnail/place-thumbnails-partial'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail/place-thumbnails-partial';
			Roblox.Endpoints.Urls['/thumbnail_holder/g'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/thumbnail_holder/g';
			Roblox.Endpoints.Urls['/users/{id}/profile'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/users/{id}/profile';
			Roblox.Endpoints.Urls['/service-workers/push-notifications'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/service-workers/push-notifications';
			Roblox.Endpoints.Urls['/notification-stream/notification-stream-data'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/notification-stream/notification-stream-data';
			Roblox.Endpoints.Urls['/api/friends/acceptfriendrequest'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/api/friends/acceptfriendrequest';
			Roblox.Endpoints.Urls['/api/friends/declinefriendrequest'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/api/friends/declinefriendrequest';
			Roblox.Endpoints.addCrossDomainOptionsToAllRequests = true;
		</script>

		<script type="text/javascript">
			if (typeof(Roblox) === "undefined") { Roblox = {}; }
			Roblox.Endpoints = Roblox.Endpoints || {};
			Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
			Roblox.Endpoints.Urls['/sets/get-by-creator'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/sets/get-by-creator';
			Roblox.Endpoints.Urls['/sets/get-roblox-sets'] = '/sets/get-roblox-sets';
			Roblox.Endpoints.Urls['/sets/get-subscribed'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/sets/get-subscribed';
			Roblox.Endpoints.Urls['/ide/toolbox/items'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/ide/toolbox/items';
			Roblox.Endpoints.Urls['/voting/studio/vote'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/voting/studio/vote';
			Roblox.Endpoints.Urls['/voting/studio/unvote'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/voting/studio/unvote';
			Roblox.Endpoints.Urls['/ide/insertasset'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/ide/insertasset';
			Roblox.Endpoints.Urls['/groups/can-manage-games'] = 'https://web.archive.org/web/20170814015848/https://www.roblox.com/groups/can-manage-games';
		</script>

	</head>
	<body ng-app="clientToolbox">
		<script type="text/javascript">
			var Roblox = Roblox || {};
			Roblox.ClientToolboxLinks =
			{
				HeaderTemplateLink: "toolbox-header",
				SubnavTemplateLink: "toolbox-subnav",
				PaginationTemplateLink: "toolbox-pagination",
				AssetsTemplateLink: "toolbox-assets",
				ThumbnailTemplateLink: "toolbox-thumbnail",
				FooterTemplateLink: "toolbox-footer",
				GetSetsByCreatorLink: "/sets/get-by-creator",
				GetRobloxSetsLink: "/sets/get-roblox-sets",
				GetSubscribedSetsLink: "/sets/get-subscribed",
				GetAssetsLink: "/ide/toolbox/items",
				GetSetItemsLink: "/sets/0/items",
				VoteStudioLink: "/voting/studio/vote",
				UnvoteStudioLink: "/voting/studio/unvote",
				InsertAssetLink: "/ide/insertasset",
				GetGroupsCanManageGamesInLink: "/groups/can-manage-games"
			};
			Roblox.AssetType = {
				"10": "FreeModels",
				"13": "FreeDecals",
				"40": "FreeMeshes",
				"3": "FreeAudio"
			};
			Roblox.ClientToolboxModel = {
				// type cast to boolean for javascript
				IsUserAuthenticated: <?= $user == null ? "false" : "true" ?>,
				ContentUrl: "https://arl.lambda.cam/asset/",
				UserId: "<?= $user == null ? -1 : $user->id ?>",
				ShowGroupCategories: true,
			};
			Roblox.jsConsoleEnabled = true;
			Roblox.AreMeshesEnabled = true;
			Roblox.EnableNewToolboxSearch = true;
			Roblox.AreAudioShown = true;
		</script>
		<input name="__RequestVerificationToken" type="hidden" value="ih4CsjhsJJoAXkDOZzM0QGI7i5Qv8dITfldAx5dGjfXgJ7vqld9GMGIWNJ2DY8F8wALcDhqpYFJ0jj2hu8B7knL57Zs1"/>

	<div id="image-retry-data" data-image-retry-max-times="10" data-image-retry-timer="1500" data-ga-logging-percent="10">
	</div>
		<div roblox-toolbox>
			<div roblox-toolbox-header class="client-toolbox-header"></div>
			<div ng-controller="RobloxReferralLink" ng-show="showReferralLinks()" class="client-toolbox-referral-links">
				Try searching for:
				<a href ng-click="refer('NPC')">NPC</a>
				<a href ng-click="refer('Vehicle')">Vehicle</a>
				<a href ng-click="refer('Weapon')">Weapon</a>
				<a href ng-click="refer('Building')">Building</a>
				<a href ng-click="refer('Light')">Light</a>
			</div>
			<div roblox-toolbox-subnav class="client-toolbox-subnav"></div>
			<div roblox-toolbox-assets class="client-toolbox-content"></div>
			<div roblox-toolbox-pagination class="client-toolbox-subnav"></div>
			<div roblox-toolbox-footer class="client-toolbox-footer"></div>
		</div>
		<div ng-modules="pageTemplateApp">
			<script type="text/javascript" src="/js/toolbox2.js"></script>
		</div>
	</body>
</html>
