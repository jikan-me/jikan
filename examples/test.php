<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

$jikan->Anime(8536);

var_dump($jikan->response);
