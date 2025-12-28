<?php 
	$method = $_GET['method'] ?? null;

	if($method != null) {
		if(str_contains($method, "Group") && $method != "GetGroupRank") {
			echo "<Value Type=\"boolean\">false</Value>";
		} elseif($method == "GetGroupRank") {
			echo "<Value Type=\"integer\">2</Value>";
		} else {
			echo "true";
		}
	}
?>