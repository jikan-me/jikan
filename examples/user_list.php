<?php

require_once '../src/jikan.php';

$jikan = new \Jikan\Get;

$user_list = array();

try {
	// fetch my anime list
	$jikan->user_list('Nekomata1037', 'anime');
	$user_list = $jikan->data;

	/*
		Save it as JSON!
		$jikan->json() will return $jikan->data as JSON

		file_put_contents("nekomata1037.json", $jikan->json());
	*/

} catch (Exception $e) {
	// catch any errors
	echo $e->getMessage();
}

var_dump($user_list);
?>