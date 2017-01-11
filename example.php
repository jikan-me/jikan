<?php

require_once 'mal-uapi2.php';

$mal = new \MAL\GET;

$mal->anime(1);
var_dump($mal->data);
?>