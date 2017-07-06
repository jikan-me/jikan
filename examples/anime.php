<?php

require_once '../src/jikan.php';

$jikan = new \Jikan\Get;

$anime = array();

try {
	// get anime
	$jikan->anime(1)->characters_staff()->episodes();
	$anime = $jikan->data;

} catch (Exception $e) {
	// catch any errors
	echo $e->getMessage();
}

var_dump($anime);
?>