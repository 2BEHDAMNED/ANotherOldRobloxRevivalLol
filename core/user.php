<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/core/status.php";

	/**
	 * Data of the user.
	 */
	class User {
		public int $id;
		public string $name;
		public string $blurb;
		public string $password;
		public string $security_key;
		public DateTime $join_date;
		
		/**
		 * Attempts to grab userdata from given id.<br>
		 * Returns null if user of id was not found.
		 * @param int $id
		 * @return User|null
		 */
		public static function FromID(?int $id) {
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
		public static function FromSecurityKey(?string $security) {
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
		public static function Exists(?int $id) {
			return self::FromID($id) != null;
		}

		function __construct($rowdata) {
			$this->id = intval($rowdata['user_id']);
			$this->name = strval($rowdata['user_name']);
			$this->blurb = str_replace("<", "&lt;", str_replace(">", "&gt;", $rowdata['user_blurb']));
			$this->join_date = DateTime::createFromFormat("Y-m-d H:i:s", $rowdata['user_joindate']);
			
			$this->password = strval($rowdata['user_password']);
			$this->security_key = strval($rowdata['user_security']);
		}

		function GetFriends(): array {
			return [];
		}
		
		function GetFollowers(): array {
			return [];
		}
		
		function GetFollowing(): array {
			return [];
		}

		function GetFriendsCount(): int { return count($this->GetFriends()); }
		
		function GetFollowersCount(): int { return count($this->GetFollowers()); }

		function GetFollowingCount(): int {	return count($this->GetFollowing()); }

		/**
		 * Returns the system badges (Homestead and the alike)
		 * @return void
		 */
		function GetProfileBadges() {}

		/**
		 * Returns badges created by the users (from games)
		 * @return void
		 */
		function GetUserBadges() {}

		function GetLatestStatus(): Status|null {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$stmt_getuser = $con->prepare("SELECT * FROM `statuses` WHERE `status_poster` = ? ORDER BY `status_posted` DESC");
			$stmt_getuser->bind_param('s', $security);
			$stmt_getuser->execute();
			$result = $stmt_getuser->get_result();

			if($result->num_rows == 0) {
				return null;
			} else {
				return new Status($result->fetch_assoc());
			}
		}

		/**
		 * Returns paged list of the user's created games
		 * @return void
		 */
		function GetOwnedGames() {}

		/**
		 * Returns the ban details if the user has been suspended/terminated<br>
		 * Null if no bans have been issued.
		 * @return void
		 */
		function GetBanDetails() {}
		
		/**
		 * Checks if the user is admin (duh)
		 * @return void
		 */
		function IsAdmin() {}

		/**
		 * Checks if user is banned via {@see GetBanDetails}
		 * @return bool
		 */
		function IsBanned(): void {}

		/**
		 * Gives user a suspension until notice.
		 * @return void
		 */
		function Suspend() {}
		/**
		 * Permanent version of Suspend()
		 * @return void
		 */
		function Terminate() {}
	}
?>