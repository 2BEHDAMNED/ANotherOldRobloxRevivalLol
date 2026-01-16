<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/status.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";

	/**
	 *  Core Profile Badges.
	 */
	enum ANORRLBadges {
		case ADMINISTRATOR;

		public function ordinal(): int {
			return match($this) {
				ANORRLBadges::ADMINISTRATOR => 1
			};
		}

		public static function index(int $badge): ANORRLBadges {
			return match($badge) {
				1 =>ANORRLBadges::ADMINISTRATOR
			};
		}
	}


	/**
	 * Data of the user.
	 */
	class User {
		public int $id;
		public string $name;
		public string $blurb;
		public string $password;
		public string $security_key;
		public DateTime $last_update;
		/**
		 * How do you name this better...
		 * @var bool
		 */
		public bool $setprofilepicture;
		public DateTime $join_date;
		
		/**
		 * Attempts to grab userdata from given id.<br>
		 * Returns null if user of id was not found.
		 * @param int $id
		 * @return User|null
		 */
		public static function FromID(int $id) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `users` WHERE `user_id` = ?");
			$stmt_getuser->bind_param('i', $id);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			if($result->num_rows == 1) {
				return new self($result->fetch_assoc());
			} else {
				return null;
			}
		}

		/**
		 * Attempts to grab userdata from given security key.<br>
		 * Returns null if user of security key was not found.
		 * @param int $security
		 * @return User|null
		 */
		public static function FromSecurityKey(string $security) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `users` WHERE `user_security` = ?");
			$stmt_getuser->bind_param('s', $security);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			if($result->num_rows == 1) {
				return new self($result->fetch_assoc());
			} else {
				return null;
			}
		}

		/**
		 * Check if that user id even exists (For presence checking)
		 * @param int $id
		 * @return bool
	 	 */
		public static function Exists(int $id) {
			return self::FromID($id) != null;
		}

		function __construct($rowdata) {
			$this->id = intval($rowdata['user_id']);
			$this->name = strval($rowdata['user_name']);
			$this->blurb = str_replace("<", "&lt;", str_replace(">", "&gt;", $rowdata['user_blurb']));
			$this->last_update = DateTime::createFromFormat("Y-m-d H:i:s", $rowdata['user_lastprofileupdate']);
			$this->setprofilepicture = boolval($rowdata['user_setprofilepicture']);
			$this->join_date = DateTime::createFromFormat("Y-m-d H:i:s", $rowdata['user_joindate']);
			
			$this->password = strval($rowdata['user_password']);
			$this->security_key = strval($rowdata['user_security']);
		}
		
		function GetFriends(): array {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `friends` WHERE (`sender` LIKE ? OR `reciever` LIKE ?) AND `status` = 1;");
			$stmt_getuser->bind_param('ii', $this->id, $this->id);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			$friends = [];

			while($row = $result->fetch_assoc()) {
				if($row['sender'] == $this->id) {
					array_push($friends, User::FromID($row['reciever']));
				} else {
					array_push($friends, User::FromID($row['sender']));
				}
			}
			return $friends;
		}
		
		function GetFollowers(): array {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `follows` WHERE `followed` = ?;");
			$stmt_getuser->bind_param('i', $this->id);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			$followers = [];

			while($row = $result->fetch_assoc()) {
				array_push($followers, User::FromID($row['follower']));
			}
			return $followers;
		}
		
		function GetFollowing(): array {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `follows` WHERE `follower` = ?;");
			$stmt_getuser->bind_param('i', $this->id);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			$following = [];

			while($row = $result->fetch_assoc()) {
				array_push($following, User::FromID($row['followed']));
			}
			return $following;
		}

		function GetPendingFriendRequests(): array {
			include $_SERVER['DOCUMENT_ROOT'] . "/core/connection.php";

			$stmt_getfriendreqs = $con->prepare("SELECT * FROM `friends` WHERE `reciever` = ? AND `status` = 0;");
			$stmt_getfriendreqs->bind_param("i", $this->id);
			$stmt_getfriendreqs->execute();

			$result_getfriendreqs = $stmt_getfriendreqs->get_result();
			
			$result = [];

			if($result_getfriendreqs->num_rows != 0) {
				while($row = $result_getfriendreqs->fetch_assoc()) {
					$user = User::FromID($row['sender']);

					if($user != null) {
						array_push($result, $user);
					} else {
						$stmt_deletefriendreq = $con->prepare("DELETE FROM `friends` WHERE `sender` = ? AND `reciever` = ? AND `status` = 0;");
						$stmt_deletefriendreq->bind_param("ii", $row['sender'], $this->id);
						$stmt_deletefriendreq->execute();
						// remove the request maybe
					}
				}
			}

			return $result;
		}

		function GetPendingFriendRequestsCount() {
			return count($this->GetPendingFriendRequests());
		}

		function GetFriendsCount(): int {
			return count($this->GetFriends());
		}
		
		function GetFollowersCount(): int {
			return count($this->GetFollowers());
		}

		function GetFollowingCount(): int {
			return count($this->GetFollowing());
		}

		/**
		 * Returns paged list of the user's created games
		 * @return void
		 */
		function GetOwnedGames(): array {
			return $this->GetAllOwnedAssetsOfType(AssetType::PLACE);
		}

		function GiveProfileBadge(ANORRLBadges $badge): void {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt = $con->prepare("SELECT * FROM `profilebadges` WHERE `badge_id` = ? AND `badge_userid` = ?");
			$ordinal = $badge->ordinal();
			$stmt->bind_param('ii', $ordinal, $this->id);
			$stmt->execute();

			if($stmt->get_result()->num_rows == 0) {
				// something
			}
		}

		function HasProfileBadgeOf(ANORRLBadges $badge): bool {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt = $con->prepare("SELECT * FROM `profilebadges` WHERE `badge_id` = ? AND `badge_userid` = ?");
			$ordinal = $badge->ordinal();
			$stmt->bind_param('ii', $ordinal, $this->id);
			$stmt->execute();

			return $stmt->get_result()->num_rows != 0;
		}

		/**
		 * Returns the system badges (Homestead and the alike)
		 * @return void
		 */
		function GetProfileBadges(): array {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt = $con->prepare("SELECT * FROM `profilebadges` WHERE `badge_userid` = ? ORDER BY `badge_recieved` DESC, `badge_admincorecore` DESC");
			$stmt->bind_param('i',$this->id);
			$stmt->execute();

			$result = $stmt->get_result();

			$badges = [];

			while($row = $result->fetch_assoc()) {
				array_push($badges, ANORRLBadge::FromID($row['badge_id']));
			}

			return $badges;
		}

		/**
		 * Returns badges created by the users (from games)
		 * @return void
		 */
		function GetUserBadges(): array {
			return [];
		}

		function GetLatestStatus(): Status|null {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt = $con->prepare("SELECT * FROM `statuses` WHERE `status_poster` = ? ORDER BY `status_posted` DESC");
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$result = $stmt->get_result();

			if($result->num_rows == 0) {
				return null;
			} else {
				return new Status($result->fetch_assoc());
			}
		}

		function GetAllOwnedAssetsOfTypePagedExcluding(AssetType $type, array $excludedids = [], int $pagenum, int $count): array {
			if(count($excludedids) == 0) {
				return $this->GetAllOwnedAssetsOfType($type);
			}

			$processedids = "AND `ta_asset` NOT IN (";
			foreach($excludedids as $id) {
				$processedids .= $id.",";
			}
			$processedids = substr($processedids, 0, strlen($processedids)-1);
			$processedids .= ")";

			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `transactions` WHERE `ta_assettype` = ? AND `ta_userid` = ? $processedids ORDER BY `ta_date` DESC LIMIT ?, ?");
			$page = (($pagenum-1)*$count);
			$ordinal = $type->ordinal();
			
			$stmt_getuser->bind_param('iiii', $ordinal, $this->id, $page, $count);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];


			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					$asset = Asset::FromID($row['ta_asset']);
					if($asset != null) {
						if($asset->type == $type) {
							array_push($result_array, $asset);
						}
					} else {
						$stmt = $con->prepare('DELETE FROM `transactions` WHERE `ta_asset` = ?');
						$stmt -> bind_param("i", $row['ta_asset']);
						$stmt->execute();
					}
					
				}
				return $result_array;
			}

			return $result_array;
		}

		function GetAllOwnedAssetsOfTypeExcluding(AssetType $type, array $excludedids = []): array {
			if(count($excludedids) == 0) {
				return $this->GetAllOwnedAssetsOfType($type);
			}

			$processedids = "AND `ta_asset` NOT IN (";
			foreach($excludedids as $id) {
				$processedids .= $id.",";
			}
			$processedids = substr($processedids, 0, strlen($processedids)-1);
			$processedids .= ")";

			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `transactions` WHERE `ta_assettype` = ? AND `ta_userid` = ? $processedids ORDER BY `ta_date` DESC");
			$ordinal = $type->ordinal();
			$stmt_getuser->bind_param('ii', $ordinal, $this->id);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];
			
			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					$asset = Asset::FromID($row['ta_asset']);
					if($asset != null) {
						array_push($result_array, $asset);
					} else {
						$stmt = $con->prepare('DELETE FROM `transactions` WHERE `ta_asset` = ?');
						$stmt -> bind_param("i", $row['ta_asset']);
						$stmt->execute();
					}
				}
			}

			return $result_array;
		}

		function GetAllOwnedAssetsOfTypePaged(AssetType $type, int $pagenum, int $count): array {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `transactions` WHERE `ta_assettype` = ? AND `ta_userid` = ? ORDER BY `ta_date` DESC LIMIT ?, ?");
			$page = (($pagenum-1)*$count);
			$ordinal = $type->ordinal();
			
			$stmt_getuser->bind_param('iiii', $ordinal, $this->id, $page, $count);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];


			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					$asset = Asset::FromID($row['ta_asset']);
					if($asset != null) {
						array_push($result_array, $asset);
					} else {
						$stmt = $con->prepare('DELETE FROM `transactions` WHERE `ta_asset` = ?');
						$stmt -> bind_param("i", $row['ta_asset']);
						$stmt->execute();
					}
				}
				return $result_array;
			}

			return $result_array;
		}

		function GetAllOwnedAssetsOfType(AssetType $type): array {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `transactions` WHERE `ta_assettype` = ? AND `ta_userid` = ? ORDER BY `ta_date` DESC");
			$ordinal = $type->ordinal();
			$stmt_getuser->bind_param('ii', $ordinal, $this->id);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];
			
			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					$asset = Asset::FromID($row['ta_asset']);
					if($asset == null) {
						$stmt = $con->prepare('DELETE FROM `transactions` WHERE `ta_asset` = ?');
						$stmt -> bind_param("i", $row['ta_asset']);
						$stmt->execute();
					} else {
						array_push($result_array, $asset);
					}
					
				}
			}

			return $result_array;
		}

		function GetAllOwnedAssets(): array {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `transactions` WHERE `ta_userid` = ? ORDER BY `ta_date` DESC");
			$stmt_getuser->bind_param('i', $this->id);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					array_push($result_array, $row);
				}
				return $result_array;
			}

			return [];
		}

		function GetLatestAssetUploaded() {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_creator` = ? ORDER BY `asset_id` DESC");
			$stmt_getuser->bind_param('i', $this->id);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				$row = $result->fetch_assoc();
				return new Asset($row);
			} else {
				return null;
			}
		}

		function IsWearing(Asset|int $asset): bool {
			$assetid = $asset;
			if($asset instanceof Asset) {
				$assetid = $asset->id;
			}
			
			if(!$this->Owns($asset) || Asset::FromID($assetid) == null) {
				return false;
			}
			
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_checkinventory = $con->prepare("SELECT * FROM `inventory` WHERE `inv_userid` = ? AND `inv_assetid` = ?;");
			$stmt_checkinventory->bind_param('ii', $this->id, $assetid);
			$stmt_checkinventory->execute();

			$numberrows = $stmt_checkinventory->get_result()->num_rows;
			if($numberrows > 1) {
				$stmt_deleteitem = $con->prepare("DELETE FROM `inventory` WHERE `inv_userid` = ? AND `inv_assetid` = ?;");
				$stmt_deleteitem->bind_param('ii', $this->id, $assetid);
				$stmt_deleteitem->execute();

				$stmt_additem = $con->prepare("INSERT INTO `inventory`(`inv_userid`, `inv_assetid`, `inv_assettype`) VALUES (?, ?, ?)");
				$assettype = 0;

				if($asset instanceof Asset) {
					$assettype = $asset->type->ordinal();
				} else {
					$assettype = Asset::FromID($assetid)->type->ordinal();
				}

				$stmt_additem->bind_param('iii', $this->id, $assetid, $assettype);
				$stmt_additem->execute();
			}

			return $numberrows != 0;
		}

		function Wear(Asset|int $asset): array {

			$theabsolutelimit = 5;

			$assetid = $asset;
			if($asset instanceof Asset) {
				$assetid = $asset->id;
			}
			
			if(!$this->Owns($asset) || Asset::FromID($assetid) == null) {
				return ["error"=>true, "reason"=>"Invalid item"];
			}

			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			if($this->IsWearing($asset)) {
				return ["error" => false];
			} else {
				$item = Asset::FromID($assetid);
				$assettype = $item->type->ordinal();
				
				if($item->type->wearable()) {
					if($item->type->wearone()) {
						$stmt_checkinventory = $con->prepare("SELECT * FROM `inventory` WHERE `inv_userid` = ? AND `inv_assettype` = ?;");
						$stmt_checkinventory->bind_param('ii', $this->id, $assettype);
						$stmt_checkinventory->execute();

						if($stmt_checkinventory->get_result()->num_rows == 0) {
							$stmt_additem = $con->prepare("INSERT INTO `inventory`(`inv_userid`, `inv_assetid`, `inv_assettype`) VALUES (?, ?, ?)");
							$assettype = $item->type->ordinal();
							$stmt_additem->bind_param('iii', $this->id, $assetid, $assettype);
							$stmt_additem->execute();
						} else {
							$stmt_replaceitem = $con->prepare("UPDATE `inventory` SET `inv_assetid` = ? WHERE `inv_userid` = ? AND `inv_assettype` = ?");
							$stmt_replaceitem->bind_param('iii', $assetid, $this->id, $assettype);
							$stmt_replaceitem->execute();
						}
					} else {
						/*$stmt_checkinventory = $con->prepare("SELECT * FROM `inventory` WHERE `inv_userid` = ? AND `inv_assettype` = ?;");
						$stmt_checkinventory->bind_param('ii', $this->id, $assettype);
						$stmt_checkinventory->execute();

						if($stmt_checkinventory->get_result()->num_rows < $theabsolutelimit) {
							
						} else {
							return ["error" => true, "reason" => "Too many fucking ".strtolower($item->type->label())."s on"];
						}*/

						$stmt_additem = $con->prepare("INSERT INTO `inventory`(`inv_userid`, `inv_assetid`, `inv_assettype`) VALUES (?, ?, ?)");
						$assettype = $item->type->ordinal();
						$stmt_additem->bind_param('iii', $this->id, $assetid, $assettype);
						$stmt_additem->execute();
					}
				} else {
					return ["error" => true, "reason" => "Invalid item"];
				}

			}

			return ["error" => false];
		}

		function Unwear(Asset|int $asset): array {
			$assetid = $asset;
			if($asset instanceof Asset) {
				$assetid = $asset->id;
			}
			
			if(!$this->Owns($asset) || Asset::FromID($assetid) == null) {
				return ["error"=>true, "reason"=>"Invalid item"];
			}

			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			if(!$this->IsWearing($asset)) {
				return ["error" => false];
			} else {
				$item = Asset::FromID($assetid);
				$assettype = $item->type->ordinal();

				if($item->type->wearable()) {
					if($item->type->wearone()) {
						$stmt_deleteitem = $con->prepare("DELETE FROM `inventory` WHERE `inv_userid` = ? AND `inv_assettype` = ?;");
						$stmt_deleteitem->bind_param('ii', $this->id, $assettype);
						$stmt_deleteitem->execute();
					} else {
						$stmt_deleteitem = $con->prepare("DELETE FROM `inventory` WHERE `inv_userid` = ? AND `inv_assetid` = ?;");
						$stmt_deleteitem->bind_param('ii', $this->id, $assetid);
						$stmt_deleteitem->execute();
					}
				} else {
					return ["error" => true, "reason" => "Invalid item"];
				}
			}

			return ["error" => false];
		}

		function GetBodyColoursXML() {
			$colours = $this->GetBodyColours();
			$headcolour = $colours['head'];
			$rightarmcolour = $colours['rightarm'];
			$leftlegcolour = $colours['leftleg'];
			$leftarmcolour = $colours['leftarm'];
			$rightlegcolour = $colours['rightleg'];
			$torsocolour = $colours['torso'];

return <<<EOT
<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://rbx.lambda.cam/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="BodyColors" referent="RBXCCC36C132C584B37B29DB69EAE48292A">
		<Properties>
			<int name="HeadColor">$headcolour</int>
			<int name="LeftArmColor">$rightarmcolour</int>
			<int name="LeftLegColor">$leftlegcolour</int>
			<string name="Name">Body Colors</string>
			<int name="RightArmColor">$leftarmcolour</int>
			<int name="RightLegColor">$rightlegcolour</int>
			<int name="TorsoColor">$torsocolour</int>
		</Properties>
	</Item>
</roblox>
EOT;
		}

		function GetCharacterAppearance(): string {
			$getwearing = $this->GetWearingArray();

			$userId = $this->id;
			$parsedshit= "";

			foreach($getwearing as $id) {
				$parsedshit .= ";http://arl.lambda.cam/asset/?id=$id";
			}

			if(str_ends_with($parsedshit, ";")) {
				$parsedshit = substr($parsedshit, 0, strlen($parsedshit)-1);
			}

			return "http://arl.lambda.cam/Asset/BodyColors.ashx?userId=$userId$parsedshit";
		}

		function GetCharacterAppearanceHash() {
			$bodycoloursxml = $this->GetBodyColoursXML();
		}

		function GetWearingArray() {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$stmt_checkinventory = $con->prepare("SELECT * FROM `inventory` WHERE `inv_userid` = ?;");
			$stmt_checkinventory->bind_param('i', $this->id);
			$stmt_checkinventory->execute();
			$checkinventory_result = $stmt_checkinventory->get_result();
			$ids = [];
		
			if($checkinventory_result->num_rows != 0) {
				while($row = $checkinventory_result->fetch_assoc()) {
					array_push($ids, $row['inv_assetid']);
				}
			}	

			return $ids;
		}

		function GetBodyColours() {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$stmt_grabcolours = $con->prepare("SELECT * FROM `bodycolours` WHERE `colours_userid` = ?;");
			$stmt_grabcolours->bind_param('i', $this->id);
			$stmt_grabcolours->execute();
			$grabcolours_result = $stmt_grabcolours->get_result();

			if($grabcolours_result->num_rows == 0) {
				$stmt_createcolours = $con->prepare("INSERT INTO `bodycolours`(`colours_userid`) VALUES (?);");
				$stmt_createcolours->bind_param('i', $this->id);
				$stmt_createcolours->execute();

				return $this->GetBodyColours();
			}
			$colours = $grabcolours_result->fetch_assoc();

			return [
				"head" => $colours['colours_head'],
				"torso" => $colours['colours_torso'],
				"leftarm" => $colours['colours_leftarm'],
				"rightarm" => $colours['colours_rightarm'],
				"leftleg" => $colours['colours_leftleg'],
				"rightleg" => $colours['colours_rightleg'],
			];
		}

		function SetBodyColours(int $head, int $torso, int $leftarm, int $rightarm, int $leftleg, int $rightleg) {
			$this->GetBodyColours(); // populate if doesn't exist

			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$stmt_createcolours = $con->prepare("UPDATE `bodycolours` SET `colours_head` = ?, `colours_torso` = ?, `colours_leftarm` = ?, `colours_rightarm` = ?, `colours_leftleg` = ?,`colours_rightleg` = ? WHERE `colours_userid` = ?;");
			$stmt_createcolours->bind_param('iiiiiii', $head, $torso, $leftarm, $rightarm, $leftleg, $rightleg, $this->id);
			$stmt_createcolours->execute();
		}
		
		function Follow(User|int $user) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}
			if(!$this->IsFollowing($user)) {
				$stmt_getuser = $con->prepare("INSERT INTO `follows`(`follower`, `followed`) VALUES (?, ?);");
				$stmt_getuser->bind_param('ii', $this->id, $userid);
				$stmt_getuser->execute();
			}
		}

		function Unfollow(User|int $user) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}
			if($this->IsFollowing($user)) {
				$stmt_getuser = $con->prepare("DELETE FROM `follows` WHERE `follower` = ? AND `followed` = ?;");
				$stmt_getuser->bind_param('ii', $this->id, $userid);
				$stmt_getuser->execute();
			}
		}

		function IsFollowing(User|int $user): bool {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			$stmt_getuser = $con->prepare("SELECT * FROM `follows` WHERE `follower` = ? AND `followed` = ?;");
			$stmt_getuser->bind_param('ii', $this->id, $userid);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			return $result->num_rows != 0;
		}

		function Friend(User|int $user) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			if(!$this->IsFriendsWith($user) && !$this->IsPendingFriendsReq($user) && !$this->IsIncomingFriendsReq($user)) {
				$stmt_addfriend = $con->prepare("INSERT INTO `friends`(`sender`, `reciever`) VALUES (?,?)");
				$stmt_addfriend->bind_param('ii', $this->id, $userid);
				$stmt_addfriend->execute();
			} else if($this->IsIncomingFriendsReq($user)) {
				$stmt_addfriend = $con->prepare("UPDATE `friends` SET `status`= 1 WHERE `reciever` = ? AND `sender` = ?;");
				$stmt_addfriend->bind_param('ii', $this->id, $userid);
				$stmt_addfriend->execute();
			} else {
				$this->Unfriend($user);
			}
		}

		function Unfriend(User|int $user) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			if($this->IsPendingFriendsReq($user) || $this->IsIncomingFriendsReq($user) || $this->IsFriendsWith($user)) {
				$stmt_getuser = $con->prepare("DELETE FROM `friends` WHERE (`reciever` = ? AND `sender` = ?)");
				$stmt_getuser->bind_param('ii', $this->id, $userid);
				$stmt_getuser->execute();

				$stmt_getuser = $con->prepare("DELETE FROM `friends` WHERE (`sender` = ? AND `reciever` = ?)");
				$stmt_getuser->bind_param('ii', $this->id, $userid);
				$stmt_getuser->execute();
			}
		}

		function IsPendingFriendsReq(User|int $user) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			$stmt_getuser = $con->prepare("SELECT * FROM `friends` WHERE `sender` = ? AND `reciever` = ? AND `status` = 0;");
			$stmt_getuser->bind_param('ii', $this->id, $userid);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			return $result->num_rows != 0;
		}

		function IsIncomingFriendsReq(User|int $user) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			$stmt_getuser = $con->prepare("SELECT * FROM `friends` WHERE `reciever` = ? AND `sender` = ? AND `status` = 0;");
			$stmt_getuser->bind_param('ii', $this->id, $userid);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			return $result->num_rows != 0;
		}

		function IsFriendsWith(User|int $user): bool {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			$stmt_getuser = $con->prepare("SELECT * FROM `friends` WHERE ((`reciever` = ? AND `sender` = ?) OR (`sender` = ? AND `reciever` = ?)) AND `status` = 1;");
			$stmt_getuser->bind_param('iiii', $this->id, $userid, $this->id, $userid);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			return $result->num_rows != 0;
		}

		function UpdateBio(string $bio): array {
			if(!$this->IsBanned()) {
				// check if user hasn't posted one in 30s

				//$offset = 3600; // windows blehh
				$offset = -3600; //prod


				$difference = (time()-($this->last_update->getTimestamp()+$this->last_update->getOffset()+$offset));

				//die(strval($difference));

				$calculated_time = 30 - $difference; 

				if($difference < 30) {
					return ["error"=> true, "reason" => "You need to wait $calculated_time seconds before updating again."];
				}

				$blockedchars = array('𒐫', '‮', '﷽', '𒈙', '⸻ ', '꧅');
				$bio_content = str_replace($blockedchars, '', trim($bio));

				if(strlen($bio_content) > 1000) {
					return ["error"=> true, "reason" => "Status was too long! (1000 characters maximum)"];
				}

				include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
				$stmt = $con->prepare('UPDATE `users` SET `user_blurb` = ?, `user_lastprofileupdate` = now() WHERE `user_id` = ?;');
				$stmt -> bind_param('si',  $bio_content, $this->id);
				$stmt -> execute();

				return ["error" => false];
			} else {
				return ["error"=> true, "reason" => "Unauthorized."];
			}
		}

		function Owns(Asset|int $asset): bool {
			$assetid = $asset;
			if($asset instanceof Asset) {
				$assetid = $asset->id;
			}
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt = $con->prepare('SELECT * FROM `transactions` WHERE `ta_userid` = ? AND `ta_asset` = ?;');
			$stmt -> bind_param('ii', $this->id, $assetid);
			$stmt -> execute();

			return $stmt->get_result()->num_rows != 0;
		}

		/**
		 * Checks if the user is admin (duh)
		 * @return void
		 */
		function IsAdmin(): bool {
			return $this->HasProfileBadgeOf(ANORRLBadges::ADMINISTRATOR);
		}

		/**
		 * Returns the ban details if the user has been suspended/terminated<br>
		 * Null if no bans have been issued.
		 * @return void
		 */
		function GetBanDetails() {}

		/**
		 * Checks if user is banned via {@see GetBanDetails}
		 * @return bool
		 */
		function IsBanned(): bool {
			return false;
		}

		/**
		 * Gives user a suspension until notice.
		 * @return void
		 */
		function Suspend(): void {}
		/**
		 * Permanent version of Suspend()
		 * @return void
		 */
		function Terminate(): void {}

		function IsOnline(): bool {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			
			$stmt_user_status_check = $con->prepare('SELECT * FROM `activity` WHERE `userid` = ? AND `action_time` > DATE_SUB(NOW(),INTERVAL 5 MINUTE)');
			$stmt_user_status_check->bind_param('i', $this->id);
			$stmt_user_status_check->execute();
			$activity_result = $stmt_user_status_check->get_result();
			
			return $activity_result->num_rows != 0;
		}

		private function getUserGameDetails(): array|null {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

			$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_players` WHERE `session_playerid` = ? AND `session_status` = 1;");
			$stmt_getsessiondetails->bind_param("i", $this->id);
			$stmt_getsessiondetails->execute();

			$result_getsessiondetails = $stmt_getsessiondetails->get_result();

			if($result_getsessiondetails->num_rows == 1) {
				return $result_getsessiondetails->fetch_assoc();
			}

			return null;
		}

		private function getServerDetails(string $serverID): array|null {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

			$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_servers` WHERE `server_id` = ?");
			$stmt_getsessiondetails->bind_param("s", $serverID);
			$stmt_getsessiondetails->execute();

			$result_getsessiondetails = $stmt_getsessiondetails->get_result();

			if($result_getsessiondetails->num_rows != 0) {
				return $result_getsessiondetails->fetch_assoc();
			}

			return null;
		}

		function GetOnlineActivity(): string {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			
			$userGameDetails = $this->getUserGameDetails();

			if($userGameDetails != null) {
				$server_details = $this->getServerDetails($userGameDetails['session_serverid']);

				if($server_details != null) {
					$place = Place::FromID(intval($server_details['server_placeid']));

					if($place != null) {
						$place_stubname = $place->GetURLTitle();
						$place_name = $place->name;
						$place_id = $place->id;

						return <<<EOT
						Playing: <a href="/$place_stubname-place?id=$place_id">$place_name</a>
						EOT;
					}
				}
			}

			$stmt_user_status_check = $con->prepare('SELECT * FROM `activity` WHERE `userid` = ? AND `action_time` > DATE_SUB(NOW(),INTERVAL 5 MINUTE)');
			$stmt_user_status_check->bind_param('i', $this->id);
			$stmt_user_status_check->execute();
			$activity_result = $stmt_user_status_check->get_result();
			
			if($activity_result->num_rows != 0) {
				return $activity_result->fetch_assoc()['action'];
			}

			

			return "Offline";
		}

		function SetProfilePicture(array $file): array {
			if($file['error'] == 0 && $file['size'] > 0 && $file['size'] <= 1048576) { // 1mb cap
				$file_contents = file_get_contents($file['tmp_name']);
				if(str_starts_with(ImageUtils::checkMimeType($file_contents),"image/")) {
					$pre_image = imagecreatefromstring($file_contents);
					
					$width = imagesx($pre_image);
					$height = imagesy($pre_image);

					if($width > 16 && $height > 16) {
						$size = $width;

						if($width == $height) {
							$size = $width;
						} else if($height < $width) {
							$size = $height;
						}

						$image = imagescale(ImageUtils::cropAlign($pre_image, $size, $size), 420, 420);
						
						imagepng($image, $_SERVER['DOCUMENT_ROOT']."/../users/profile_".$this->id.".png");

						return ["error" => false];
					}

					return ["error" => true, "reason" => "Image was wayyy too small!"];

				}
				return ["error" => true, "reason" => "Something went wrong when uploading!"];
			}

			return ["error" => true, "reason" => "Something went wrong when uploading!"];
		}

		function GetProfilePictureURL() {
			if($this->setprofilepicture) {
				return "\"><script>alert(\"How\")</script";
			} else {
				$pictures = array_diff(scandir($_SERVER['DOCUMENT_ROOT']."/images/profile_pictures/", SCANDIR_SORT_NONE), array("..", "."));
				 
				$rand_pic = rand(0, count($pictures) - 1);

				return "/images/profile_pictures/$rand_pic";
			}
		}
	}

	class ANORRLBadge {
		public ANORRLBadges $id;
		public string $name;
		public string $description;

		public static function FromID(int $id): ANORRLBadge|null {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `profilebadges_info` WHERE `pbadge_id` = ?");
			$stmt_getuser->bind_param('i', $id);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			if($result->num_rows == 1) {
				return new self($result->fetch_assoc());
			} else {
				return null;
			}
		}

		function __construct($rowdata) {
			$this->id = ANORRLBadges::index(intval($rowdata['pbadge_id']));
			$this->name = strval($rowdata['pbadge_name']);
			$this->description = strval($rowdata['pbadge_description']);
		}
	}
?>