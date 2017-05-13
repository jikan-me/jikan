<?php

require_once 'mal-uapi.php';

$mal = new \MAL\GET;
$character = $mal->character(5)->data;
var_dump($character); // anime id here

?>