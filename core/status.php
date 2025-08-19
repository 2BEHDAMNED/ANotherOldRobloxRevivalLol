<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/user.php";

	class Status {

		public string $id;
		public User $poster;
		public string $content;
		public DateTime $time_posted;

		private static function GetRandomString(): string {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			
			for ($i = 0; $i < 20; $i++) {
				$index = rand(0, strlen($characters) - 1);
				$randomString .= $characters[$index];
			}
	
			return $randomString;
		}

		public static function GenerateID() {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$id = self::GetRandomString(); //id
			$stmt = $con->prepare('SELECT * FROM `statuses` WHERE `status_id` = ?');
			$stmt->bind_param('s', $id);
			$stmt->execute();
			$stmt->store_result();
			
			$instances = $stmt->num_rows;
			
			if($instances != 0) {
				self::GenerateID();
			} else {
				return $id;
			}
		}

		public static function Send(int $userid, string $contents) {
			$user = User::FromID($userid);

			if($user != null && !$user->IsBanned()) {
				$latest_status = $user->GetLatestStatus();
				if($latest_status != null) {
					// check if user hasn't posted one in 30s

					$difference = (time()-($latest_status->time_posted->getTimestamp()-3600));

					$calculated_time = 30 - $difference; 

					if($difference < 30) {
						return ["error" => "You need to wait $calculated_time seconds before posting again."];
					}
				}

				$blockedchars = array('𒐫', '‮', '﷽', '𒈙', '⸻ ', '꧅');
				$status_id = self::GenerateID();
				$status_content = str_replace($blockedchars, '', $contents);;
			}
		}

		function __construct($rowdata) {
			$this->id = intval($rowdata['status_id']);
			$this->poster = User::FromID(intval($rowdata['status_poster']));
			$this->content = str_replace("<", "&lt;", str_replace(">", "&gt;", $rowdata['status_content']));
			$this->time_posted = DateTime::createFromFormat("Y-m-d H:i:s", $rowdata['status_posted']);
		}

	}
?>