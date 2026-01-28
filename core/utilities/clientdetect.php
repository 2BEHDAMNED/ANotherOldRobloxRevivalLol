<?php
	enum Client {
		case C2010;
		case C2013;
		case C2016;
		case Unknown;
	}

	class ClientDetector {

		public static function DetectClient() {
			if(strpos($_SERVER['HTTP_USER_AGENT'], "ANORRLStudio/0.235.0.2025") !== false) {
				return Client::C2016;
			}
			else if(strpos($_SERVER['HTTP_USER_AGENT'], "RobloxStudio/2013. 8. 13. 2") !== false) {
				return Client::C2013;
			}

			return Client::Unknown;
		}

	}
?>