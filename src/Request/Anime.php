<?php

namespace Jikan\Request;

use Jikan\Exception as Exception;

class Anime extends \Jikan\Abstracts\Requests
{

	public $model;
	public $response;
	public $parser;

	private $request;
	private $helper;
	private const VALID_REQUESTS = [ANIME, CHARACTERS_STAFF, EPISODES, NEWS, VIDEOS, STATS, PICTURES, FORUM, MORE_INFO];
	private const VALID_HELPERS = [EPISODES];
	private const PATH = BASE_URL . ANIME_ENDPOINT;

	public function __construct($request = ANIME, $helper) {
		if (!in_array($request, self::VALID_REQUESTS)) {
			throw new Exception\UnsupportedRequestException();
		}

		var_dump($helper);
		// if (!is_null($helper) && $helper instanceof \Jikan\Abstracts\Helper && in_array(strtolower($helper::class), self::VALID_HELPERS)) {
		// 	$this->helper = $helper;

		// 	var_dump($this->helper);
		// }

		die;

		$model = '\\Jikan\\Model\\' . ($request == ANIME ? ANIME : ANIME . ucfirst($request));
		$parser = '\\Jikan\\Parser\\' . ($request == ANIME ? ANIME : ANIME . ucfirst($request));

		$this->model = new $model;
		$this->parser = new $parser($this->model);
		$this->request = $request;
	}

	public function getPath() : string {
		if (is_null(parent::getPath()) && is_null($this->getID())) {
			throw new Exception\EmptyRequestException();
		}

		if (!is_null($this->getID())) {
			return self::PATH . parent::getID() . ($this->request !== ANIME ? '/_/' . $this->request : '');
		}

		return parent::getPath();
	}

	public function getRequest() {
		return $request;
	}

	public function setRequestArgs($key, $value) {
		if (array_key_exists($this->request, self::REQUEST_VALUES)) {
			$this->requestArgs = $value;
		}
	}
}