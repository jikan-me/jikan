<?php

require_once '../vendor/autoload.php';

$jikan = new Jikan\Jikan;


try {
	$jikan->Anime(
		(new \Jikan\Request\Anime(
			CHARACTERS_STAFF 
		))->setID(21)
	);

	var_dump($jikan->response);
} catch (Exception $e) {
	var_dump($jikan->request->parser->status);
	var_dump("ERROR: " . $e->getMessage());
	var_dump($jikan);
}