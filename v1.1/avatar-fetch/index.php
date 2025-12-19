<?php
header("Content-Type: text/plain");
function DieOnBadInts($number){
	if(!is_numeric($number)){
		http_response_code(400);
		die();
	}
	if(!preg_match('/^[0-9]+$/', $number)){
		http_response_code(400);
		die();
	}
}
function PreventIntegerLimit($number){
	if((int)$number === -2147483648 or (int)$number === 2147483647){
		return 0;
	}
	return $number;
}
$binary = (string)$_GET["placeId"];
// valid binary string, split, explode and other magic
// prepare string for conversion
$chars = explode( "\n", chunk_split( str_replace( "\n", '', $binary ), 8 ) );
$char_count = count( $chars );
// converting the characters one by one
$text = "";
for( $i = 0; $i < $char_count; $text .= chr( bindec( $chars[$i] ) ), $i++ );
$numbers = explode("|", $text);
$head = strtoupper(sprintf('%06x', $numbers[0]));
$torso = strtoupper(sprintf('%06x', $numbers[1]));
$rightleg = strtoupper(sprintf('%06x', $numbers[2]));
$leftleg = strtoupper(sprintf('%06x', $numbers[3]));
$rightarm = strtoupper(sprintf('%06x', $numbers[4]));
$leftarm = strtoupper(sprintf('%06x', $numbers[5]));
// my bad og im 4 months late (avatar update)
$hat1id = @PreventIntegerLimit($numbers[7]) ?: 0;
DieOnBadInts($hat1id);
$hat2id = @PreventIntegerLimit($numbers[8]) ?: 0;
DieOnBadInts($hat2id);
$hat3id = @PreventIntegerLimit($numbers[9]) ?: 0;
DieOnBadInts($hat3id);
$shirtid = @PreventIntegerLimit($numbers[10]) ?: 0;
DieOnBadInts($shirtid);
$tshirtid = @PreventIntegerLimit($numbers[11]) ?: 0;
DieOnBadInts($tshirtid);
$pantsid = @PreventIntegerLimit($numbers[12]) ?: 0;
DieOnBadInts($pantsid);
$faceid = @substr(PreventIntegerLimit($numbers[13]), 0, -1) ?: 0;
DieOnBadInts($faceid);

$r15 = "R6";
if ($numbers[6] == 1) {
  $r15 = "R15";
}


?>
{
  "resolvedAvatarType": "R6",
  "accessoryVersionIds": [],
  "equippedGearVersionIds": [],
  "backpackGearVersionIds": [],
  "bodyColorsUrl": "http://<?= $_SERVER['SERVER_NAME'] ?>/Asset/BodyColors.ashx?userId=1",
  "animations": {
    "Run": 969731563
  },
  "scales": {
    "Width": 1,
    "Height": 1,
    "Head": 1,
    "Depth": 1,
    "Proportion": 0,
    "BodyType": 0
  }
}