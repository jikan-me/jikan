<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;


$jikan->Anime(21);
//$jikan->Anime(1, [EPISODES, CHARACTERS_STAFF]); // get episodes + characters+staff
//$jikan->Anime(21, [EPISODES=>2]); // get all the episodes from page 2 of the episode list

var_dump($jikan->response);
?>