<?php

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/user.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/asset.php';

	enum BlobValueTypes {
		case STRING = "string";
		case INSTANCE = "instance";
		case BOOLEAN = "boolean";
		case NUMBER = "number";
	}

	class Blob {
		public Place $place;
		public User $user;
		public array $tinys; // TinyBlob
		
	}

	class TinyBlob {
		public string $key;
		public BlobValueTypes $type;
		public string|float|int|bool $value;
	}

	
?>