<?php
header("Content-Type: application/json");

try {
    $text = $_REQUEST['text'];
	
    if (isset($_REQUEST['text'])) {
		$filtered = $text;
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