<?php

	require_once 'user.php';

	enum AssetStatus {
		case REJECTED = -1;
		case PENDING = 0;
		case ACCEPTED = 1;

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
		case TSHIRT = 2;
		case HAT = 8;
		case PLACE = 9;
		case MODEL = 10;
		case SHIRT = 11;
		case PANTS = 12;
		case DECAL = 13;
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

		public static function FromID(int $id): Asset|Place {
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

		function __construct($rowdata) {
			$this->id = intval($rowdata['asset_id']);
			$this->creator = User::FromID($rowdata['asset_creator']);
			$this->type = AssetType::Asset; // temp
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
			parent::__construct($rowdata);


		}

		function Visit(User|int $user) {}
		function GetBadges() {}
	}
?>