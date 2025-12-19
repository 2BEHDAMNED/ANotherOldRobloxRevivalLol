<?php

header("Content-Type: application/json");

echo json_encode([
	"success" => true,
	"data" => [
		"white" => $_REQUEST["text"],
		"black" => $_REQUEST["text"]
	]
]);

die()
;