<?php

namespace Jikan\Model;


class Manga extends Model
{

	public $mal_id;
	
	public $link_canonical;

	public $title;

	public $title_english;

	public $title_synonyms;

	public $title_japanese;

	public $status;

	public $image_url;

	public $type;

	public $volumes;

	public $chapters;

	public $publishing = false;

	public $published_string;

	public $published = [];

	public $rank;

	public $score;

	public $scored_by;

	public $popularity;

	public $members;

	public $favorites;

	public $synopsis;

	public $background;
	
	public $related = [];

	public $genre = [];

	public $author = [];

	public $serialization = [];

}