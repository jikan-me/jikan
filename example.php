<?php

require_once 'jikan.php';

$jikan = new \Jikan\Get;

$anime = array();
$manga = array();
$myAnimeList = array();

try {
	// get anime
	$jikan->anime(1)->characters_staff()->episodes();
	$anime = $jikan->data;

	// get manga
	$manga = $jikan->manga(1)->characters_staff()->data;

	// fetch my anime list
	$myAnimeList = $jikan->user_list('Nekomata1037', 'anime')->data;
	/*
		Save it as JSON!
		$jikan->json() will return $jikan->data as JSON

		file_put_contents("nekomata1037.json", $jikan->json());
	*/

} catch (Exception $e) {
	// catch any errors
	echo $e->getMessage();
}


?>