<?php

require_once '../src/jikan.php';

$jikan = new \Jikan\Get;

$character = array();

try {
	// get character
	$character = $jikan->character(1)->data;

} catch (Exception $e) {
	// catch any errors
	echo $e->getMessage();
}

var_dump($character);
?>