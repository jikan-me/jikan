<?php

namespace Jikan\Model;

class Person extends Model
{

	public $mal_id;

	public $link_canonical;

	public $name;

	public $given_name;

	public $family_name;

	public $alternate_name;

	public $birthday;

	public $website_url;

	public $member_favorites;

	public $more;

	public $image_url;

	public $voice_acting_role = [];

	public $anime_staff_position = [];

	public $published_manga = [];

}