<?php
// build testing

error_reporting(E_ALL);

require_once "vendor/autoload.php"; 

$jikan = new Jikan\Jikan;


$jikan->Anime(21);
sleep(5);
$jikan->Anime(21, [EPISODES]);
sleep(5);
$jikan->Anime(21, [CHARACTERS_STAFF]);
sleep(5);
$jikan->Anime(21, [NEWS]);
sleep(5);
$jikan->Manga(1);
sleep(5);
$jikan->Manga(1, [CHARACTERS]);
sleep(5);
$jikan->Manga(1, [NEWS]);
sleep(5);
$jikan->Person(1);
sleep(5);
$jikan->Character(1);
