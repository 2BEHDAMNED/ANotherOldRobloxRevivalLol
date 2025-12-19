<?php

header("Content-Type: application/json");

echo json_encode([
	"data" => [
		"white" => $_REQUEST["text"],
		"black" => $_REQUEST["text"]
	]
]);

die()
;