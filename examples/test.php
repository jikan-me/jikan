<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

$jikan->Anime(21, [EPISODES]);

$animeData = $jikan->response;


var_dump($animeData);


//var_dump($jikan->response);
//var_dump($jikan->response);



//$jikan->Manga(1);