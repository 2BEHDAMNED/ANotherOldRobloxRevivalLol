<?php
header("Content-Type: application/json");
require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/slurutils.php";

try {
    $text = $_REQUEST['text'];
	
    if (isset($_REQUEST['text'])) {
		$filtered = SlurUtils::ProcessText($text);
        $response = [
            "success" => true,
            "data" => [
                "white" => $filtered,
                "black" => $filtered
            ]
        ];
    } else {
        $response = [
            "success" => false,
            "data" => [
                "white" => "ERROR",
                "black" => "ERROR"
            ]
        ];
    }

    echo json_encode($response);
} catch (Throwable $e) {
    $errorResponse = [
        "success" => false,
        "data" => [
            "white" => "ERROR",
            "black" => "ERROR"
        ]
    ];

    echo json_encode($errorResponse);
    exit();
}
?>