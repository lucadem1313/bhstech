<?php

session_start();

$sitename = "Circle Story";
$path = "/circlestory/";
$pathtouser = $path."u?id=";
$pathtosubject = $path."s?id=";
$pathtotag = $path."tag?id=";
$error404path = $path."error404.php";
$pathtostory = $path."story?id=";

define('ENCRYPTION_PASSWORD', 'RETRACTED');
define('ENCRYPTION_TYPE', 'blowfish');

?>