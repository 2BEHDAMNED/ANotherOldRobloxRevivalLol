<?php
	enum Client {
		case C2010;
		case C2013;
		case C2016;
		case Unknown;
	}

	class ClientDetector {

		public static function DetectClient() {
			if(str_contains($_SERVER['HTTP_USER_AGENT'], "ANORRLStudio/0.235.0.2025")) {
				return Client::C2016;
			}
			else if(str_contains($_SERVER['HTTP_USER_AGENT'], "RobloxStudio/2013. 8. 13. 2")) {
				return Client::C2013;
			}

			return Client::Unknown;
		}

	}
?>