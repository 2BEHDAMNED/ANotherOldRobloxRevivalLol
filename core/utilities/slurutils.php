<?php
	/**
	 * This is for detecting and censoring any horrendous shit that could be posted...
	 */
	class SlurUtils {

		public static function ProcessText(string $input) {
			$profanity = file($_SERVER['DOCUMENT_ROOT']."/core/badwords.txt", FILE_IGNORE_NEW_LINES);

			$processed = $input;

			foreach($profanity as $slur) {
				if(str_starts_with($input, "$slur ")) {
					$pretext = substr($input, strlen("$slur "), strlen($input));

					$processed = str_repeat("#", strlen($slur))." ".$pretext;
				}
			}

			$words = explode(" ", $processed);

			$processed = "";

			foreach($words as $word) {
				foreach($profanity as $slur) {
					if(str_starts_with($word, "$slur ")) {
						$pretext = substr($word, strlen("$slur"), strlen($word));

						$processed .= str_repeat("#", strlen($slur)).$pretext." ";
					}
				}
			}

			return trim($processed);
		}

	}
?>