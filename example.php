<?php

require_once 'jikan.php';

$jikan = new \Jikan\Get;

$firsts = array();

$jikan->manga(1); // get the anime with ID 1 on MAL
//$firsts['manga'] = $jikan->manga(1)->data; // get the manga with ID 1 on MAL
//$firsts['character'] = $jikan->character(1)->data; // get the character with ID 1 on MAL
//$firsts['person'] = $jikan->person(1)->data; // get the person with ID 1 on MAL

//$jikan->list('Nekomata1037', 'manga');
var_dump($jikan->data);

//var_dump($firsts); // data dump
?>