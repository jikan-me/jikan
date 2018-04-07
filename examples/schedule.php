<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

// no arguments
$jikan->Schedule();

$jikan->response['monday']; // anime airing on monday
$jikan->response['tuesday']; // anime airing on tuesday
//$jikan->response[$day];
$jikan->response['sunday']; // all the way upto sunday

var_dump($jikan->response);
?>