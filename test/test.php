<?php

require_once '../vendor/autoload.php';

$jikan = new Jikan\Jikan;


try {
	$foo = (new Jikan\Helper\Episodes)->setPage(2);
	var_dump($foo);
	die;
	$jikan->Anime(
		(new \Jikan\Request\Anime(EPISODES, $foo ))->setID(21)
	);

	var_dump($jikan->response);
} catch (Exception $e) {
	var_dump($jikan->request->parser->status);
	var_dump("ERROR: " . $e->getMessage());
	var_dump($jikan);
}