<?php

namespace Jikan\Request\Anime;

use \Jikan\Exception as Exception;

class Anime extends \Jikan\Abstracts\Requests
{

	private $request;

	private const VALID_REQUESTS = [ANIME, CHARACTERS_STAFF];
	private const PATH = BASE_URL . ANIME_ENDPOINT;

	public function __construct($request = ANIME) {
		if (!in_array($request, self::VALID_REQUESTS)) {
			throw new Exception\UnsupportedRequestException();
		}

		$this->request = $request;
	}

	public function getPath() : string {
		if (is_null(parent::getPath()) && is_null($this->getID())) {
			throw new Exception\EmptyRequestException();
		}

		if (!is_null($this->getID())) {
			return self::PATH . parent::getID() . (!$this->request == ANIME ? '_/' . $this->request : '');
		}

		return parent::getPath();
	}

	public function getRequest() {
		return $request;
	}
}