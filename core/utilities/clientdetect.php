<?php
	enum Client {
		case C2010;
		case C2013;
		case C2016;
		case Unknown;

		public function ordinal(): string {
			return match($this) {
				Client::C2010 	=> "2010",
				Client::C2013 	=> "2013",
				Client::C2016	=> "2016",
			};
		}
	}

	class ClientDetector {

		public static function DetectClient(): Client {
			if(str_contains($_SERVER['HTTP_USER_AGENT'], "ANORRLStudio/0.235.0.2025")) {
				return Client::C2016;
			}
			else if(str_contains($_SERVER['HTTP_USER_AGENT'], "RobloxStudio/2013. 8. 13. 2") || str_contains($_SERVER['HTTP_USER_AGENT'], "ANORRL/13nInet")) {
				return Client::C2013;
			}
			else if(str_contains($_SERVER['HTTP_USER_AGENT'], "ANORRL/10nInet")) {
				return Client::C2010;
			}

			return Client::Unknown;
		}

	}
?>