<?php
    chdir("/var/www/anorrl/rcc");

    $descriptorspec = array (
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
    );

    if ( is_resource( $prog = proc_open("wine start /b " . "/var/www/anorrl/rcc/RCCService.exe -console 64900", $descriptorspec, $pipes, "/var/www/anorrl/rcc", NULL) ) )
    {
        $ppid = proc_get_status($prog)['pid'];
    }
    else
    {
        echo("Failed to execute!");
        exit();
    }

	echo strval($ppid);
?>
