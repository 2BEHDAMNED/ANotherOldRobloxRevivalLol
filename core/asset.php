<?php

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/user.php';

	enum AssetStatus {
		case REJECTED;
		case PENDING;
		case ACCEPTED;

		public static function index(?int $ordinal): AssetStatus {
			return match($ordinal) {
				-1 => AssetStatus::REJECTED, 
				 0 => AssetStatus::PENDING, 
				 1 => AssetStatus::ACCEPTED, 
			};
		}

		public function ordinal(): int {
			return match($this) {
				AssetStatus::REJECTED => -1, 
				AssetStatus::PENDING  =>  0, 
				AssetStatus::ACCEPTED =>  1, 
			};
		}
	}

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
		public AssetStatus $status;

		public int         $favourites_count;
		public bool        $comments_enabled;

		public bool        $onsale;
		/** Tickets */
		public int         $cost_lights;
		/** Robux */
		public int         $cost_cones;
		public int         $sales_count;

		public bool         $notcatalogueable;

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

		public static function GetAllUncheckedAssets(): array|null {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getallusers = $con->prepare("SELECT * FROM `assets` WHERE `asset_status` = ? AND `asset_nevershow` = 0");
			$ordinal = AssetStatus::PENDING->ordinal();
			$stmt_getallusers->bind_param("i", $ordinal);
			$stmt_getallusers->execute();
			$result = $stmt_getallusers->get_result();
			$result_array = array();

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					if(User::FromID($row['asset_creator']) != null) {
						if($row['asset_type'] == AssetType::PLACE->ordinal()) {
							array_push($result_array, new Place($row));
						} else {
							array_push($result_array, new Asset($row));
						}
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
				$this->status = AssetStatus::index(intval($rowdata['asset_status']));
	
				$this->favourites_count = intval( $rowdata['asset_favourites_count']);
				$this->comments_enabled = boolval($rowdata['asset_comments_enabled']);
	
				$this->onsale = boolval($rowdata['asset_onsale']);
				$this->cost_lights = intval($rowdata['asset_cost_lights']);
				$this->cost_cones =  intval($rowdata['asset_cost_cones']);
				$this->sales_count = intval($rowdata['asset_sales_count']);

				$this->notcatalogueable = boolval($rowdata['asset_nevershow']);
	
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
				$this->status = $asset_data->status;
	
				$this->favourites_count = $asset_data->favourites_count;
				$this->comments_enabled = $asset_data->comments_enabled;
	
				$this->onsale = $asset_data->onsale;
				$this->cost_lights = $asset_data->cost_lights;
				$this->cost_cones = $asset_data->cost_cones;
				$this->sales_count = $asset_data->sales_count;
	
				$this->last_updatetime = $asset_data->last_updatetime;
				$this->created_at      = $asset_data->created_at;	
			}
		}

		function GetURLTitle() {
			return str_replace(" ", "-", strtolower(trim(preg_replace('/[^A-Za-z0-9 ]/', "", $this->name))));
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

		function Comment(User|int $user, string $content) {}
		function GetAllComments() {}
		function GetComments(int $page, int $rows) {}

		function Favourite(User|int $user) {
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

			$userid = $user;
			if($user instanceof User) {
				$userid = $user->id;
			}

			if(!$this->HasUserFavourited($user)) {
				$stmt = $con->prepare("INSERT INTO `favourites`(`fav_assetid`, `fav_userid`) VALUES (?, ?);");
				$stmt->bind_param("ii", $this->id, $userid);
				$stmt->execute();
			}
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
	}

	class Place extends Asset {
		/** is the same as Asset::public */
		public bool $friends_only;
		public bool $copylocked;
		public int  $genre;
		public int  $visit_count;
		public int  $current_playing_count;

		function __construct($rowdata) {
			parent::__construct(intval($rowdata['place_id']));

			$this->friends_only = $this->public;
			$this->copylocked = intval($rowdata['place_copylocked']);
		}

		function Visit(User|int $user) {}
		function GetBadges() {}
	}
?>