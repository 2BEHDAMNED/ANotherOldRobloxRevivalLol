<?php
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
);

	$cmd = '"/home/delta/ANORRLGameManager/2016/RCCService.exe" -start -console 12828';
    popen($cmd . ' > /dev/null 2>&1 &', 'r');
	echo "wine ".$cmd;
?>
