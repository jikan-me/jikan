<?php

require_once 'mal-uapi.php';

$mal = new \MAL\GET;
$dis = $mal->anime(30727);
var_dump($dis); // anime id here

?>