<?php
header("Content-Type: application/json");

try {
    $text = $_REQUEST['text'];
	
    if (isset($_REQUEST['text'])) {
		$filtered = $text;
        $response = [
            "success" => true,
            "data" => [
                "AgeUnder13" => $filtered,
                "Age13OrOver" => $filtered
            ]
        ];
    } else {
        $response = [
            "success" => false,
            "data" => [
                "AgeUnder13" => "ERROR",
                "Age13OrOver" => "ERROR"
            ]
        ];
    }

    echo json_encode($response);
} catch (Throwable $e) {
    $errorResponse = [
        "success" => false,
        "data" => [
            "AgeUnder13" => "ERROR",
            "Age13OrOver" => "ERROR"
        ]
    ];

    echo json_encode($errorResponse);
    exit();
}
?>