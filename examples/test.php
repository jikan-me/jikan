<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";

$jikan = new Jikan\Jikan;

$jikan->Person(1868);

var_dump($jikan->response['published_manga']);
