<?php
// build testing

error_reporting(E_ALL);

require_once "vendor/autoload.php"; 

$jikan = new Jikan\Jikan;

/*
 * Anime Tests
 */
try {
	$jikan->Anime(
		(new Jikan\Request\Anime())->setID(1)
	);

	var_dump($jikan->response);
} catch (Exception $e) {
	var_dump("STATUS: " . $jikan->request->parser->status);
	var_dump("ERROR: " . $e->getMessage());
}
sleep(4);
try {
	$jikan->Anime(
		(new Jikan\Request\Anime(CHARACTERS_STAFF))->setID(1)
	);

	var_dump($jikan->response);
} catch (Exception $e) {
	var_dump("STATUS: " . $jikan->request->parser->status);
	var_dump("ERROR: " . $e->getMessage());
}