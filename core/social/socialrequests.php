<?php 
	$method = $_GET['method'] ?? null;

	if($method != null) {
		if(str_contains($method, "Group") && $method != "GetGroupRank") {
			echo "<Value Type=\"boolean\">true</Value>";
		} elseif($method == "GetGroupRank") {
			echo "<Value Type=\"integer\">1</Value>";
		} else {
			echo "true";
		}
	}
?>