<?php

require_once '../src/jikan.php';

$jikan = new \Jikan\Get;

$manga = array();

try {
	// get manga
	$manga = $jikan->manga(1)->characters_staff()->data;
} catch (Exception $e) {
	// catch any errors
	echo $e->getMessage();
}

var_dump($manga);
?>