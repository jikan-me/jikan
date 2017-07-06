<?php

require_once '../src/jikan.php';

$jikan = new \Jikan\Get;

$person = array();

try {
	// get person
	$person = $jikan->person(1)->data;
} catch (Exception $e) {
	// catch any errors
	echo $e->getMessage();
}

var_dump($person);
?>