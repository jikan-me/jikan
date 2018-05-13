<?php
// ignore this
error_reporting(E_ALL);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

//require_once dirname(__DIR__) . "/vendor/autoload.php"; 
require_once "../vendor/autoload.php";

$jikan = new Jikan\Jikan;

$time_start = microtime(true);

$config = new Jikan\Helper\SearchConfig(ANIME);

$config->setGenre(1, 2);
$config->setStartDate("2015", "01", "01");
$config->setEndDate("2016", "05", "05");
$config->setGenreInclude(false);

$jikan->Search('Bleach', ANIME, 1, $config);

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' s<br><br>';

//var_dump();
foreach ($jikan->response['result'] as $key => $value) {
	var_dump($value);
}

//$jikan->Anime(21);
//sleep(5);
//$jikan->Anime(21, [EPISODES]);
//sleep(5);
//$jikan->Anime(21, [EPISODES, CHARACTERS_STAFF]);
//sleep(5);
//$jikan->Manga(1);
//sleep(5);
//$jikan->Manga(1, [CHARACTERS]);
//sleep(5);
//$jikan->Person(1);
//sleep(5);
//$jikan->Character(1);
//