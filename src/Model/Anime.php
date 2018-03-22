<?php

namespace Jikan\Model;


class Anime extends Model
{

	public $mal_id;

	public $link_canonical;

	public $title;

	public $title_english;

	public $title_japanese;

	public $title_synonyms;

	public $image_url;

	public $type;

    public $source;

    public $episodes;

	public $status;

	public $airing = false;

	public $aired_string;
	
	public $aired = [
		'from' => null,
		'to' => null
	];

	public $duration;

	public $rating;

	public $score;

	public $scored_by;

	public $rank;

	public $popularity;

	public $members;

	public $favorites;

	public $synopsis;

	public $background;

	public $premiered;

	public $broadcast;

	public $related = [];

	public $producer = [];

	public $licensor = [];

	public $studio = [];

	public $genre = [];

	public $opening_theme = [];

	public $ending_theme = [];

}