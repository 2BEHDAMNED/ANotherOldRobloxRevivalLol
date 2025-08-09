<?php

	require_once 'user.php';
	require_once 'assets/buyable.php';
	require_once 'assets/place.php';

	enum Status {
		case REJECTED = -1;
		case PENDING = 0;
		case ACCEPTED = 1;
	}

	enum AssetType {
		
	}

	/**
	 * Abstract class for assets
	*/
	class Asset {
		public int $id;
		public string $name;
		public string $description;
		public User $creator;
		public AssetType $type;
		public Status $status;

		public string $favourites;

		public DateTime $last_updatetime;
		public DateTime $created_at;

		function __construct($rowdata) {}

		function Favourite() {}
		function Unfavourite() {}

		function Accept() {}
		function Delete() {}
		function Reject() {}
	}
?>