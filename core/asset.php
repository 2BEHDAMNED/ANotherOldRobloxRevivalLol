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

		public static function index(?int $ordinal): AssetType {
			return match($ordinal) {
				1 => AssetType::IMAGE,
				2 => AssetType::TSHIRT,
				3 => AssetType::AUDIO,
				4 => AssetType::LUA,
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
				AssetType::RIGHTLEG 	=> 31
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

		public DateTime    $last_updatetime;
		public DateTime    $created_at;

		public static function FromID(int $id): Asset|null {
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

		function __construct(array|int $rowdata) {
			if(is_array($rowdata)) {
				$this->id = intval($rowdata['asset_id']);
				$this->creator = User::FromID($rowdata['asset_creator']);
				$this->type = AssetType::index(intval($rowdata['asset_type'])); // temp
				$this->name = str_replace("<", "&lt;", str_replace(">", "&gt;", $rowdata['asset_name']));
				$this->description = str_replace("<", "&lt;", str_replace(">", "&gt;", $rowdata['asset_description']));
				$this->public = boolval($rowdata['asset_public']);
				$this->status = AssetStatus::index(intval($rowdata['asset_type']));
	
				$this->favourites_count = intval( $rowdata['asset_favourites_count']);
				$this->comments_enabled = boolval($rowdata['asset_comments_enabled']);
	
				$this->onsale = boolval($rowdata['asset_onsale']);
				$this->cost_lights = intval($rowdata['asset_cost_lights']);
				$this->cost_cones =  intval($rowdata['asset_cost_cones']);
				$this->sales_count = intval($rowdata['asset_sales_count']);
	
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

		function Comment(User|int $user, string $content) {}
		function GetAllComments() {}
		function GetComments(int $page, int $rows) {}

		function Favourite() {}
		function Unfavourite() {}

		function Accept() {}
		function Delete() {}
		function Reject() {}
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