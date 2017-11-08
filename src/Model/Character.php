<?php

namespace Jikan\Model;


class Character extends Model
{

	public $name;

	public $name_kanji;

	public $nicknames;

	public $about;

	public $member_favorites;

	public $image_url;

	public $animeography = [];

	public $mangaography = [];

	public $voice_actor = [];

}