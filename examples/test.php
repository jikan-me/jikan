<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

$jikan->Anime(21, [EPISODES, CHARACTERS_STAFF]);


echo "Response: ";
var_dump($jikan->response);
