<?php

namespace Jikan\Request\Anime;

use Jikan\Exception;
use Jikan\Requests as Requests;

class Anime extends Requests
{

	private $request;

	private const VALID_REQUEST = [ANIME, CHARACTERS_STAFF, ARTICLES, EPISODES, MORE_INFO, NEWS, PICTURES, VIDEOS, TOPICS, STATS];
	private const PATH = BASE_URL . ANIME_ENDPOINT;

	public function __construct($request = ANIME) {
		if (!in_array($request, self::VALID_REQUEST)) {
			throw new UnsupportedRequestException();
		}

		$this->request = $request;
	}

	public function getPath() {
		if (is_null($this->path) && is_null($this->id)) {
			throw new EmptyRequestException();
		}

		if (!is_null($this->id)) {
			return self::PATH . $this->id . (!$this->request == ANIME ? '_/' . $this->request : '');
		}

		return $this->path;
	}

	public function getRequest() {
		return $request;
	}
}