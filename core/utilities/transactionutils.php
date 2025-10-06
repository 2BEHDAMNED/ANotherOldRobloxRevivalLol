<?php

	require_once $_SERVER["DOCUMENT_ROOT"]."/core/utilities/userutils.php";

	enum TransactionType {
		case CONES;
		case LIGHTS;
		case FREE;
	}

	class TransactionUtils {
		private static function getRandomString($length = 15): string {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			
			for ($i = 0; $i < $length; $i++) {
				$index = rand(0, strlen($characters) - 1);
				$randomString .= $characters[$index];
			}
	
			return $randomString;
		}

		
		public static function GenerateID() {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$id = self::getRandomString(); //id
			$stmt = $con->prepare('SELECT * FROM `transactions` WHERE `ta_id` LIKE ?');
			$stmt->bind_param('s', $id);
			$stmt->execute();
			$stmt->store_result();
			
			$instances = $stmt->num_rows;
			
			if($instances != 0) {
				return self::GenerateID();
			} else {
				return $id;
			}
		}

		public static function StipendLightsToUser(int $user_id, int $amount = 250) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$ta_id = self::GenerateID();
			$ta_userid = $user_id;
			$ta_cost = $amount;
			$stmt = $con->prepare('INSERT INTO `transactions`(`ta_id`, `ta_userid`, `ta_currency`, `ta_cost`) VALUES (?, ?, "lights", ?)');
			$stmt->bind_param("sii", $ta_id, $ta_userid, $ta_cost);
			$stmt->execute();
		}

		public static function StipendConesToUser(int $user_id, int $amount = 100) {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			$ta_id = self::GenerateID();
			$ta_userid = $user_id;
			$ta_cost = $amount;
			$stmt = $con->prepare('INSERT INTO `transactions`(`ta_id`, `ta_userid`, `ta_currency`, `ta_cost`) VALUES (?, ?, "cones", ?)');
			$stmt->bind_param("sii", $ta_id, $ta_userid, $ta_cost);
			$stmt->execute();
		}

		public static function StipendCheckToUser(int $user_id) {
			$user = User::FromID($user_id);
			if($user != null && !$user->IsBanned() && $user->PendingStipend()) {
				


				include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
				$stmt_getuser = $con->prepare("SELECT * FROM `subscriptions` WHERE `userid` = ?");
				$stmt_getuser->bind_param('i', $user->id);
				$stmt_getuser->execute();
				$result = $stmt_getuser->get_result();


				if($result->num_rows == 1) {
					$stmt_user_status_check = $con->prepare('UPDATE `subscriptions` SET `lastpaytime` = now() WHERE `userid` = ?');
					$stmt_user_status_check->bind_param('i', $user->id);
					$stmt_user_status_check->execute();
				} else {
					$stmt_user_status_check = $con->prepare('INSERT INTO `subscriptions`(`userid`) VALUES (?)');
					$stmt_user_status_check->bind_param('i', $user->id);
					$stmt_user_status_check->execute();


				}

				self::StipendLightsToUser($user_id);
				self::StipendConesToUser($user_id);
			}
		}

		public static function BuyItem(TransactionType $type, int $asset_id): string {
			include $_SERVER["DOCUMENT_ROOT"]."/core/connection.php";
			
			$get_user = UserUtils::RetrieveUser();
			$asset = Asset::FromID($asset_id);
			if($get_user != null) {
				if($asset != null) {

				} else {
					return "That asset doesn't exist!";
				}
				
			} else {
				return "User is not authorised to perform this action!";
			}
		}
	}
?>