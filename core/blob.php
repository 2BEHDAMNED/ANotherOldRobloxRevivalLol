<?php

	enum BlobValueTypes {
		case STRING = "string";
		case INSTANCE = "instance";
		case BOOLEAN = "boolean";
		case NUMBER = "number";
	}

	class Blob {
		public Place $place;
		public User $user;
		public BlobValueTypes $type;
		public string|float|int|bool $value;
		
	}

?>