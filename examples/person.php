<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

$jikan->Person(1);

var_dump($jikan->response);
?>