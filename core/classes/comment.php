<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/user.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	class Comment  {
		public User|Asset $parent;
		public User $poster;
		public string $contents;
		public DateTime $postdate;

		function __construct($rowdata) {

		}

		public static function Post(Asset|User $parent, string $contents) {
			$user = UserUtils::RetrieveUser();

			$id = "a!".$parent->id;
			if($parent instanceof User) {
				$id = "u!".$parent->id;
			}

			
		}

	}
?>