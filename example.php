<?php

require_once 'mal-uapi.php';

$mal = new \MAL\GET;

var_dump($mal->anime(1)['related']); // anime id here

?>