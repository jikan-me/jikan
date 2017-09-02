<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

$jikan->Anime(1, [[EPISODES => 1], CHARACTERS_STAFF]);

echo json_encode($jikan->response);

var_dump($jikan->response);
