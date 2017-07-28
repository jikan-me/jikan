<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

$jikan->Anime(21, [[EPISODES => 1], CHARACTERS_STAFF]);


var_dump($jikan->response);
