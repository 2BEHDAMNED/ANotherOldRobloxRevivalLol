<?php 

require_once $_SERVER['DOCUMENT_ROOT']."/core/userutils.php";
echo User::FromID(1)->GetCharacterAppearanceVerbose(); ?>