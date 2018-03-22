<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;


// $jikan->Seasonal(season (constant), year (string));
// season : WINTER, SPRING, SUMMER, FALL
// CONSTANTS ARE DEFINED IN `src/config.php`
$jikan->Seasonal(WINTER, 2017);

var_dump($jikan->response);
?>