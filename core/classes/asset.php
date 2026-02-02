<?php

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/classes/user.php';

	enum AssetType {
		case IMAGE;
		case TSHIRT;
		case AUDIO;
		case MESH;
		case LUA;
		case HAT;
		case PLACE;
		case MODEL;
		case SHIRT;
		case PANTS;
		case DECAL;
		case HEAD;
		case FACE;
		case GEAR;
		case BADGE;
		case ANIMATION;
		case TORSO;
		case RIGHTARM;
		case LEFTARM;
		case LEFTLEG;
		case RIGHTLEG;
		case PACKAGE;
		case GAMEPASS;

		public static function index(?int $ordinal): AssetType {
			return match($ordinal) {
				1 => AssetType::IMAGE,
				2 => AssetType::TSHIRT,
				3 => AssetType::AUDIO,
				4 => AssetType::MESH,
				5 => AssetType::LUA,
				8 => AssetType::HAT,
				9 => AssetType::PLACE,
				10 => AssetType::MODEL,
				11 => AssetType::SHIRT,
				12 => AssetType::PANTS,
				13 => AssetType::DECAL,
				17 => AssetType::HEAD,
				18 => AssetType::FACE,
				19 => AssetType::GEAR,
				21 => AssetType::BADGE,
				24 => AssetType::ANIMATION,
				27 => AssetType::TORSO,
				28 => AssetType::RIGHTARM,
				29 => AssetType::LEFTARM,
				30 => AssetType::LEFTLEG,
				31 => AssetType::RIGHTLEG,
				32 => AssetType::PACKAGE,
				34 => AssetType::GAMEPASS,
			};
		}

		public function ordinal(): int {
			return match($this) {
				AssetType::IMAGE 	=> 1,
				AssetType::TSHIRT 	=> 2,
				AssetType::AUDIO	=> 3,
				AssetType::MESH 	=> 4,
				AssetType::LUA 		=> 5,
				AssetType::HAT 		=> 8,
				AssetType::PLACE	=> 9,
				AssetType::MODEL 	=> 10,
				AssetType::SHIRT 	=> 11,
				AssetType::PANTS 	=> 12,
				AssetType::DECAL 	=> 13,
				AssetType::HEAD 	=> 17,
				AssetType::FACE 	=> 18,
				AssetType::GEAR 	=> 19,
				AssetType::BADGE 	=> 21,
				AssetType::ANIMATION 	=> 24,
				AssetType::TORSO 		=> 27,
				AssetType::RIGHTARM 	=> 28,
				AssetType::LEFTARM 		=> 29,
				AssetType::LEFTLEG 		=> 30,
				AssetType::RIGHTLEG 	=> 31,
				AssetType::PACKAGE      => 32,
				AssetType::GAMEPASS     => 34,
			};
		}

		public function wearable(): bool {
			return match($this) {
				AssetType::TSHIRT 	=> true,
				AssetType::HAT 		=> true,
				AssetType::SHIRT 	=> true,
				AssetType::PANTS 	=> true,
				AssetType::HEAD 	=> true,
				AssetType::FACE 	=> true,
				AssetType::GEAR 	=> true,
				AssetType::TORSO 		=> true,
				AssetType::RIGHTARM 	=> true,
				AssetType::LEFTARM 		=> true,
				AssetType::LEFTLEG 		=> true,
				AssetType::RIGHTLEG 	=> true,
				default => false
			};
		}

		public function wearone(): bool {
			return match($this) {
				AssetType::TSHIRT 	=> true,
				AssetType::SHIRT 	=> true,
				AssetType::PANTS 	=> true,
				AssetType::HEAD 	=> true,
				AssetType::FACE 	=> true,
				AssetType::TORSO 		=> true,
				AssetType::RIGHTARM 	=> true,
				AssetType::LEFTARM 		=> true,
				AssetType::LEFTLEG 		=> true,
				AssetType::RIGHTLEG 	=> true,
				default => false
			};
		}

		public function label(): string {
			return match($this) {
				AssetType::IMAGE 	=> "Image",
				AssetType::TSHIRT 	=> "T-Shirt",
				AssetType::AUDIO	=> "Audio",
				AssetType::MESH 	=> "Mesh",
				AssetType::LUA 		=> "Script",
				AssetType::HAT 		=> "Hat",
				AssetType::PLACE	=> "Place",
				AssetType::MODEL 	=> "Model",
				AssetType::SHIRT 	=> "Shirt",
				AssetType::PANTS 	=> "Pants",
				AssetType::DECAL 	=> "Decal",
				AssetType::HEAD 	=> "Head",
				AssetType::FACE 	=> "Face",
				AssetType::GEAR 	=> "Gear",
				AssetType::BADGE 	=> "Badge",
				AssetType::ANIMATION 	=> "Animation",
				AssetType::TORSO 		=> "Torso",
				AssetType::RIGHTARM 	=> "Right Arm",
				AssetType::LEFTARM 		=> "Left Arm",
				AssetType::LEFTLEG 		=> "Left Leg",
				AssetType::RIGHTLEG 	=> "Right Leg",
				AssetType::PACKAGE      => "Package",
				AssetType::GAMEPASS     => "Gamepass",
			};
		}
	}

	/**
	 * Abstract class for assets
	*/
	class Asset {
		public int         $id;
		public User        $creator;
		public AssetType   $type;
		public string      $name;
		public string      $description;
		/** friends-only in places */
		public bool        $public;

		public int         $favourites_count;
		public bool        $comments_enabled;

		public bool        $onsale;
		public int         $sales_count;

		public Asset|null $relatedasset;
		public bool         $notcatalogueable;
		public int $current_version;
		

		public DateTime    $last_updatetime;
		public DateTime    $created_at;

		public static function FromID(int $id): Asset|null {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_id` = ?");
			$stmt_getuser->bind_param('i', $id);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			if($result->num_rows == 1) {
				return new self($result->fetch_assoc());
			} else {
				return null;
			}
		}


		public static function GetAssetsOfTypePaged(string $query, AssetType $type, int $pagenum, int $count, User|null $input_user = null) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$page = (($pagenum-1)*$count);
			$q = "%$query%";
			$ordinal = $type->ordinal();

			if($input_user == null) {
				$stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_name` LIKE ? AND `asset_type` = ? AND `asset_public` = 1 ORDER BY `asset_lastedited` DESC LIMIT ?, ?");
				$stmt_getuser->bind_param('siii', $q, $ordinal, $page, $count);
				$stmt_getuser->execute();	
			} else {
				$stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_name` LIKE ? AND `asset_type` = ? AND `asset_public` = 1 AND `asset_creator` = ? ORDER BY `asset_lastedited` DESC LIMIT ?, ?");
				$stmt_getuser->bind_param('siiii', $q, $ordinal, $input_user->id, $page, $count);
				$stmt_getuser->execute();
			}
			
			
			

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					$asset = new Asset($row);
					if($row['asset_type'] == AssetType::PLACE->ordinal()) {
						$asset = Place::FromID($row['asset_id']);
					} else {
						$asset = new Asset($row);
					}

					if(!$asset->notcatalogueable && $asset->public) {
						array_push($result_array, $asset);
					}
					
				}
				return $result_array;
			}

			return [];
		}

		public static function GetAssetsOfType(string $query, AssetType $type, User|null $input_user = null) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$q = "%$query%";
			$ordinal = $type->ordinal();

			if($input_user == null) {
				$stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_name` LIKE ? AND `asset_type` = ? AND `asset_public` = 1");
				$stmt_getuser->bind_param('si', $q, $ordinal);
				$stmt_getuser->execute();
			} else {
				$stmt_getuser = $con->prepare("SELECT * FROM `assets` WHERE `asset_name` LIKE ? AND `asset_type` = ? AND `asset_creator` = ? AND `asset_public` = 1");
				$stmt_getuser->bind_param('sii', $q, $ordinal, $input_user->id);
				$stmt_getuser->execute();
			}

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					if($row['asset_type'] == AssetType::PLACE->ordinal()) {
						$asset = Place::FromID($row['asset_id']);
					} else {
						$asset = new Asset($row);
					}

					if(!$asset->notcatalogueable && $asset->public) {
						array_push($result_array, $asset);
					}
				}
				return $result_array;
			}

			return [];
		}

		function __construct(array|int $rowdata) {
			if(is_array($rowdata)) {
				$this->id = intval($rowdata['asset_id']);
				$this->creator = User::FromID($rowdata['asset_creator']);
				$this->type = AssetType::index(intval($rowdata['asset_type'])); // temp
				$this->name = str_replace("<", "&lt;", str_replace(">", "&gt;", $rowdata['asset_name']));
				$this->description = str_replace("<", "&lt;", str_replace(">", "&gt;", $rowdata['asset_description']));
				$this->public = boolval($rowdata['asset_public']);

				$this->favourites_count = intval( $rowdata['asset_favourites_count']);
				$this->comments_enabled = boolval($rowdata['asset_comments_enabled']);
	
				$this->onsale = boolval($rowdata['asset_onsale']);
				$this->sales_count = intval($rowdata['asset_sales_count']);

				$this->notcatalogueable = boolval($rowdata['asset_nevershow']);
				$this->relatedasset = Asset::FromID(intval($rowdata['asset_relatedid']));
				$this->current_version = intval($rowdata['asset_currentversion']);
	
				$this->last_updatetime = DateTime::createFromFormat("Y-m-d H:i:s", $rowdata['asset_lastedited']);
				$this->created_at      = DateTime::createFromFormat("Y-m-d H:i:s", $rowdata['asset_created']);	
			} else {
				// for extended classes
				$asset_data = Asset::FromID($rowdata);
				
				$this->id = $asset_data->id;
				$this->creator = $asset_data->creator;
				$this->type = $asset_data->type;
				$this->name = $asset_data->name;
				$this->description = $asset_data->description;
				$this->public = $asset_data->public;
	
				$this->favourites_count = $asset_data->favourites_count;
				$this->comments_enabled = $asset_data->comments_enabled;
	
				$this->onsale = $asset_data->onsale;
				$this->sales_count = $asset_data->sales_count;
				
				$this->notcatalogueable = $asset_data->notcatalogueable;
				$this->relatedasset = $asset_data->relatedasset;
				$this->current_version = $asset_data->current_version;

				$this->last_updatetime = $asset_data->last_updatetime;
				$this->created_at      = $asset_data->created_at;	
			}
		}

		function GetURLTitle() {
			$result = str_replace(" ", "-", strtolower(trim(preg_replace('/[^A-Za-z0-9 ]/', "", $this->name))));
			if($result == "") {
				$result = "unnamed";
			}

			return $result;
		}

		function GetAllVersions(): array {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `assetversions` WHERE `version_assetid` = ? ORDER BY `version_id` DESC");
			$stmt_getuser->bind_param('i', $this->id);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					array_push($result_array, new AssetVersion($row));
				}
			}

			return $result_array;
		}

		function GetLatestVersionDetails(): AssetVersion|null {
			return AssetVersion::GetLatestVersionOf($this);
		}

		function GetVersionID(): int {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt = $con->prepare("SELECT * FROM `assetversions` WHERE `version_assetid` = ? ORDER BY `version_id`");
			$stmt->bind_param("i", $this->id);
			$stmt->execute();

			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			return $row["version_id"];
		}

		function GetMD5HashCurrent(): string {
			return $this->GetMD5Hash($this->GetVersionID());
		}

		function GetMD5Hash(int $version): string {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt = $con->prepare("SELECT * FROM `assetversions` WHERE `version_id` = ?");
			$stmt->bind_param("i", $version);
			$stmt->execute();

			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			return $row["version_md5sig"];
		}

		function Comment(User|int|null $user, string $contents) {
			if($user != null) {
				$user_id = $user;
				if($user instanceof User) {
					$user_id = $user->id;
				}


			} else {
				return ['error' => true, 'reason' => "User not logged in!"];
			}
		}
		function GetAllComments(): array {
			return [];
		}
		function GetComments(int $page = 1, int $rows = 15): array {
			return [];
		}

		function Favourite(User|int $user) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			if(!$this->HasUserFavourited($user)) {
				$stmt = $con->prepare("INSERT INTO `favourites`(`fav_assetid`, `fav_userid`, `fav_assettype`) VALUES (?, ?, ?);");
				$type = $this->type->ordinal();
				$stmt->bind_param("iii", $this->id, $userid, $type);
				$stmt->execute();

				$this->UpdateFavouritesCount();
			}
		}

		private function UpdateFavouritesCount() {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt = $con->prepare("SELECT * FROM `favourites` WHERE `fav_assetid` = ?;");
			$stmt->bind_param("i", $this->id);
			$stmt->execute();

			$favcount = $stmt->get_result()->num_rows;

			$stmt = $con->prepare("UPDATE `assets` SET `asset_favourites_count` = ? WHERE `asset_id` = ?");
			$stmt->bind_param("ii", $favcount, $this->id);
			$stmt->execute();
		}

		function Unfavourite(User|int $user) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			if($this->HasUserFavourited($user)) {
				$stmt = $con->prepare("DELETE FROM `favourites` WHERE `fav_assetid` = ? AND `fav_userid` = ?;");
				$stmt->bind_param("ii", $this->id, $userid);
				$stmt->execute();

				$this->UpdateFavouritesCount();
			}
		}

		function HasUserFavourited(User|int $user) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			$stmt = $con->prepare("SELECT * FROM `favourites` WHERE `fav_assetid` = ? AND `fav_userid` = ?;");
			$stmt->bind_param("ii", $this->id, $userid);
			$stmt->execute();

			return $stmt->get_result()->num_rows != 0;
		}

		function UpdateSalesCount() {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt = $con->prepare("SELECT * FROM `transactions` WHERE `ta_userid` != `ta_assetcreator` AND `ta_asset` = ?;");
			$stmt->bind_param("i", $this->id);
			$stmt->execute();

			$salescount = $stmt->get_result()->num_rows;

			$stmt = $con->prepare("UPDATE `assets` SET `asset_sales_count` = ? WHERE `asset_id` = ?");
			$stmt->bind_param("ii", $salescount, $this->id);
			$stmt->execute();
		}

		function GetFileContents(int $version = -1) {
			if($version > 0) {
				$asset_version = AssetVersion::GetVersionOf($this, $version);

				if($asset_version != null) {
					$filename = $_SERVER['DOCUMENT_ROOT']."/../assets/".$asset_version->md5sig;
				} else {
					return null;
				}
			} else {
				$filename = $_SERVER['DOCUMENT_ROOT']."/../assets/".$this->GetLatestVersionDetails()->md5sig;
			}

			if(file_exists($filename)) {
				$handle = fopen($filename, "r"); 
				$contents = fread($handle, filesize($filename)); 
				fclose($handle);
				$contents = str_replace("www.roblox.com", "arl.lambda.cam",$contents);
				$contents = str_replace("api.roblox.com", "arl.lambda.cam",$contents);

				return str_replace("arl.lambda.cam", $_SERVER['SERVER_NAME'], $contents);
			}
			
			return null;
		}

		function GetRelatedAssets() {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

			$stmt = $con->prepare("SELECT `asset_id` FROM `assets` WHERE `asset_relatedid` = ?");
			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			
			$stmt_result = $stmt->get_result();

			$result = [];

			while($row = $stmt_result->fetch_assoc()) {
				$asset = Asset::FromID(intval($row['asset_id']));
				if($asset != null) {
					array_push($result, $asset);
				}
			}

			return $result;
		}
	}

	enum PlaceYear {
		case Y2010;
		case Y2013;
		case Y2016;

		public static function index(string $ordinal): PlaceYear {
			return match($ordinal) {
				"2010" => PlaceYear::Y2010,
				"2013" => PlaceYear::Y2013,
				"2016" => PlaceYear::Y2016,
				default => PlaceYear::Y2016
			};
		}

		public function ordinal(): string {
			return match($this) {
				PlaceYear::Y2010 	=> "2010",
				PlaceYear::Y2013 	=> "2013",
				PlaceYear::Y2016	=> "2016",
			};
		}
	}

	class Place extends Asset {
		/** is the same as Asset::public */
		public PlaceYear $year;
		public bool $friends_only;
		public bool $copylocked;
		public int $server_size;
		public int  $visit_count;
		public int  $current_playing_count;
		public bool $is_original;
		public bool $gears_enabled;
		public bool $teamcreate_enabled;

		public static function UpdatePlaceStats(int $placeID) {
			$place = Place::FromID($placeID);

			if($place != null) {
				include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
				$stmt_checkserver = $con->prepare("SELECT * FROM `active_servers` WHERE `server_placeid` = ?;");
				$stmt_checkserver->bind_param("i", $place->id);
				$stmt_checkserver->execute();

				$result_checkserver = $stmt_checkserver->get_result();

				$data = [];

				$concurrentplayers = 0;

				while($server_row = $result_checkserver->fetch_assoc()) {
					$stmt_checkplayersfromserver = $con->prepare("SELECT * FROM `active_players` WHERE `session_serverid` = ? AND `session_status` = 1;");
					$stmt_checkplayersfromserver->bind_param("s", $server_row['server_id']);
					$stmt_checkplayersfromserver->execute();

					$result_checkplayersfromserver = $stmt_checkplayersfromserver->get_result();
					
					$concurrentplayers += $result_checkplayersfromserver->num_rows;
				}

				$stmt_updateplayercount = $con->prepare("UPDATE `asset_places` SET `place_currently_playing` = ? WHERE `place_id` = ?");
				$stmt_updateplayercount->bind_param("ii", $concurrentplayers, $place->id);
				$stmt_updateplayercount->execute();
			}
		}

		public static function UpdateAllPlaces() {
			foreach(self::GetAssetsOfType("", AssetType::PLACE) as $place) {
				self::UpdatePlaceStats($place->id);
			}
		}

		public static function FromID(int $id): Place|null {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `asset_places` WHERE `place_id` = ?");
			$stmt_getuser->bind_param('i', $id);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			if($result->num_rows == 1) {
				return new self($result->fetch_assoc());
			} else {
				return null;
			}
		}
		
		public static function GetAllPaged(string $query, int $pagenum, int $count) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT asset_places.* FROM `asset_places`, `assets` WHERE assets.asset_id = asset_places.place_id AND assets.asset_public = 1 AND assets.asset_name LIKE ? ORDER BY `place_currently_playing` DESC, `place_visit_count` DESC, `asset_lastedited` DESC LIMIT ?, ?");
			$page = (($pagenum-1)*$count);
			$q = "%$query%";
			$stmt_getuser->bind_param('sii', $q, $page, $count);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					$asset = Place::FromID($row['place_id']);

					if(!$asset->notcatalogueable && $asset->public) {
						array_push($result_array, $asset);
					}
					
				}
				return $result_array;
			}

			return [];
		}

		public static function GetAll(string $query) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$stmt_getuser = $con->prepare("SELECT asset_places.* FROM `asset_places`, `assets` WHERE assets.asset_id = asset_places.place_id AND assets.asset_public = 1 AND assets.asset_name LIKE ? ORDER BY `place_currently_playing` DESC, `place_visit_count` DESC, `asset_lastedited` DESC;");
			
			$q = "%$query%";
			$stmt_getuser->bind_param('s', $q);
			$stmt_getuser->execute();

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					$asset = Place::FromID($row['place_id']);
					if(!$asset->notcatalogueable && $asset->public) {
						array_push($result_array, $asset);
					}
				}
				return $result_array;
			}

			return [];
		}

		function __construct($rowdata) {
			parent::__construct(intval($rowdata['place_id']));

			$this->friends_only = $this->public;
			$this->copylocked = boolval($rowdata['place_copylocked']);
			$this->year = PlaceYear::index($rowdata['place_year']);
			$this->server_size = intval($rowdata['place_serversize']);
			$this->visit_count = intval($rowdata['place_visit_count']);
			$this->current_playing_count = intval($rowdata['place_currently_playing']);
			$this->teamcreate_enabled = boolval($rowdata['place_teamcreate_enabled']);

			$this->is_original = boolval($rowdata['place_original']);
			$this->gears_enabled = boolval($rowdata['place_gears_enabled']);
		}

		function EnableTeamCreate() {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_enableteamcreate = $con->prepare('UPDATE `asset_places` SET `place_teamcreate_enabled` = 1 WHERE `place_id` = ?');
			$stmt_enableteamcreate->bind_param('i', $this->id);
			$stmt_enableteamcreate->execute();

			if(!$this->IsCloudEditor($this->creator)) {
				$this->AddCloudEditor($this->creator);
			}
		}

		function DisableTeamCreate() {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_disableteamcreate = $con->prepare('UPDATE `asset_places` SET `place_teamcreate_enabled` = 0 WHERE `place_id` = ?');
			$stmt_disableteamcreate->bind_param('i', $this->id);
			$stmt_disableteamcreate->execute();

			if($this->teamcreate_enabled) {
				$stmt_checkiseditor = $con->prepare('DELETE FROM `cloudeditors` WHERE `cloudeditor_userid` != ? AND `cloudeditor_placeid` = ?;');
				$stmt_checkiseditor->bind_param('ii', $this->creator->id, $this->id);
				$stmt_checkiseditor->execute();

				$stmt_getactiveservers = $con->prepare("SELECT * FROM `active_servers` WHERE `server_placeid` = ? AND `server_teamcreate` = 1");
				$stmt_getactiveservers->bind_param("i", $this->id);
				$stmt_getactiveservers->execute();

				$result_getactiveservers = $stmt_getactiveservers->get_result();

				if($result_getactiveservers->num_rows != 0) {
					$row = $result_getactiveservers->fetch_assoc();

					$jobID = $row['server_jobid'];

					$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
	
					$rcc_settings = $settings['renderer'];
					$rcc_ip = $rcc_settings['RCCGAMEIP'];
					$rcc_port = 64888;

					$directory = $_SERVER['DOCUMENT_ROOT']."/core/Assemblies/Roblox/Grid/Rcc/";
					$scanned_directory = array_diff(scandir($directory), array('..', '.'));

					foreach($scanned_directory as $file) {
						if(str_contains($file, "wsdl")) {
							continue;
						}
						require $directory.$file;
					}

					$scriptText = <<<EOT
					for _, v in pairs(game.Players:GetPlayers()) do
						v:Kick("Team Create Session Closed.")
					end
					EOT;

					$rcc = new Roblox\Grid\Rcc\RCCServiceSoap($rcc_ip, $rcc_port);
					$script = new Roblox\Grid\Rcc\ScriptExecution("kicker", $scriptText);
					$rcc->Execute($jobID, $script);
					$rcc->CloseJob(trim($jobID));

					include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
					$stmt_createnewserver = $con->prepare("DELETE FROM `active_servers` WHERE `server_jobid` = ?;");
					$stmt_createnewserver->bind_param("s", $jobID);
					$stmt_createnewserver->execute();
				}

				
			}
		}

		function IsCloudEditor(User $user) {
			if($this->teamcreate_enabled) {
				include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
				$stmt_checkiseditor = $con->prepare('SELECT * FROM `cloudeditors` WHERE `cloudeditor_userid` = ?;');
				$stmt_checkiseditor->bind_param('i', $user->id);
				$stmt_checkiseditor->execute();

				return $stmt_checkiseditor->get_result()->num_rows != 0;
			}
			return false;
		}

		function AddCloudEditor(User $user) {
			if(!$this->IsCloudEditor($user)) {
				include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
				$stmt_addeditor = $con->prepare('INSERT INTO `cloudeditors`(`cloudeditor_userid`, `cloudeditor_placeid`) VALUES (?, ?)');
				$stmt_addeditor->bind_param('ii', $user->id, $this->id);
				$stmt_addeditor->execute();
			}	
		}

		function RemoveCloudEditor(User $user) {
			if($this->IsCloudEditor($user) && $user->id != $this->creator->id) {
				include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
				$stmt_addeditor = $con->prepare('DELETE FROM `cloudeditors` WHERE `cloudeditor_userid` = ? AND `cloudeditor_placeid` = ?;');
				$stmt_addeditor->bind_param('ii', $user->id, $this->id);
				$stmt_addeditor->execute();
			}	
		}

		function GetCloudEditors() {
			if($this->teamcreate_enabled) {
				include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
				$stmt_geteditors = $con->prepare('SELECT * FROM `cloudeditors` WHERE `cloudeditor_placeid` = ?;');
				$stmt_geteditors->bind_param('i', $this->id);
				$stmt_geteditors->execute();

				$result_geteditors = $stmt_geteditors->get_result();

				$result = [];

				while($row = $result_geteditors->fetch_assoc()) {
					$user = User::FromID(intval($row['cloudeditor_userid']));

					if($user != null && !$user->IsBanned()) {
						array_push($result, $user);
					}
				}

				return $result;
			}
			return [];
		}

		function Visit(User|int $user) {
			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";

			$placeid = $this->id;

			$stmt_checkvisit = $con->prepare('SELECT * FROM `visit` WHERE `visit_place` = ? AND `visit_player` = ? AND `visit_time` >= CURDATE() - INTERVAL 1 HOUR;');
			$stmt_checkvisit->bind_param('ii', $placeid, $userid);
			$stmt_checkvisit->execute();

			if($stmt_checkvisit->get_result()->num_rows == 0) {
				$stmt_addvisit = $con->prepare('INSERT INTO `visit`(`visit_place`, `visit_player`) VALUES (?, ?)');
				$stmt_addvisit->bind_param('ii', $placeid, $userid);
				$stmt_addvisit->execute();

				// Update

				$stmt_visitcount = $con->prepare('SELECT * FROM `visit` WHERE `visit_place` = ?;');
				$stmt_visitcount->bind_param('i', $placeid);
				$stmt_visitcount->execute();
	
				$visits = $stmt_visitcount->get_result()->num_rows;

				/*if($visits > 100 && !Asset::FromID($placeid)->creator->HasProfileBadgeOf(User::BADGE_HOMESTEAD)) {
					Asset::FromID($placeid)->creator->GiveProfileBadge(User::BADGE_HOMESTEAD);
				}

				if($visits > 1000 && !Asset::FromID($placeid)->creator->HasProfileBadgeOf(User::BADGE_BRICKSMITH)) {
					Asset::FromID($placeid)->creator->GiveProfileBadge(User::BADGE_BRICKSMITH);
				}*/
	
				$stmt = $con->prepare('UPDATE `asset_places` SET `place_visit_count` = ? WHERE `place_id` = ?;');
				$stmt->bind_param('ii', $visits, $placeid);
				$stmt->execute();
			}
		}
		function GetBadges(): array {
			return [];
		}
		function GetGamepasses(): array {
			return [];
		}
	}
	class AssetVersion {

		public int $id;
		public Asset $asset;
		public string $md5sig;
		public string $md5thumb;
		public AssetType $asset_type;
		public DateTime $publish_date;

		public static function GetLatestVersionOf(Asset|int $asset) {
			if($asset instanceof Asset) {
				return self::GetVersionOf($asset, $asset->current_version);
			} else {
				$asset = Asset::FromID($asset);
				return self::GetVersionOf($asset, $asset->current_version);
			}
		}

		public static function GetVersionOf(Asset|int $asset, int $version) {
			$id = $asset;
			if($asset instanceof Asset) {
				$id = $asset->id;
			}
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `assetversions` WHERE `version_assetid` = ? AND `version_subid` = ?");
			$stmt_getuser->bind_param('ii', $id, $version);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			if($result->num_rows == 1) {
				return new self($result->fetch_assoc());
			} else {
				return null;
			}
		}


		function __construct($rowdata) {
			$this->id = intval($rowdata['version_id']);
			$this->asset = Asset::FromID(intval($rowdata['version_assetid']));
			$this->asset_type = AssetType::index(intval($rowdata['version_assettype']));
			$this->md5sig = strval($rowdata['version_md5sig']);
			$this->md5thumb = strval($rowdata['version_md5thumb']);

			$this->publish_date = DateTime::createFromFormat("Y-m-d H:i:s", $rowdata['version_publishdate']);	
		}

	}
?>