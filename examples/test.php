<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

var_dump($jikan->Anime(2167)['related']);

//var_dump($jikan->response);
//var_dump($jikan->response);



//$jikan->Manga(1);