<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;


$jikan->Anime(1);
//$jikan->Anime(1, [EPISODES, CHARACTERS_STAFF]);
//$jikan->Anime(21, [EPISODES=>2]);
//$jikan->Manga(1);
//$jikan->Manga(1, [CHARACTERS]);
//$jikan->Person(1);
//$jikan->Character(1);
//
//

var_dump($jikan->response);