<?php

require_once 'mal-uapi.php';

$mal = new \MAL\GET;
$character = $mal->character(1)->data;
var_dump($character); // character id here

?>