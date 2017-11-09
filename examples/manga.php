<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;


$jikan->Manga(1);
//$jikan->Manga(1, [CHARACTERS]); // get the characters too

var_dump($jikan->response);
?>