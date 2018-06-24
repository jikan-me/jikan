<?php

namespace Jikan\Request;

use Jikan\Abstracts\Requests;
use Jikan\Exception;

/**
 * Class Anime
 *
 * @package Jikan\Request
 */
class Anime extends Requests
{

    const VALID_REQUESTS = [ANIME, CHARACTERS_STAFF];
    const PATH = BASE_URL.ANIME_ENDPOINT;
    public $model;
    public $response;
    public $parser;
    private $request;

    /**
     * Anime constructor.
     *
     * @param string $request
     *
     * @throws Exception\UnsupportedRequestException
     */
    public function __construct($request = ANIME)
    {
        if (!in_array($request, self::VALID_REQUESTS)) {
            throw new Exception\UnsupportedRequestException();
        }

        $request = $request === ANIME ? '' : $request;
        $model = '\\Jikan\\Model\\'.ANIME.ucfirst($request);
        $parser = '\\Jikan\\Parser\\'.ANIME.ucfirst($request);

        $this->model = new $model;
        $this->parser = new $parser($this->model);
        $this->request = $request;
    }

    /**
     * @return string
     * @throws Exception\EmptyRequestException
     */
    public function getPath(): string
    {
        if (is_null(parent::getPath()) && is_null($this->getID())) {
            throw new Exception\EmptyRequestException();
        }

        if (!is_null($this->getID())) {
            return self::PATH.parent::getID().($this->request !== ANIME ? '/_/'.$this->request : '');
        }

        return parent::getPath();
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }
}
