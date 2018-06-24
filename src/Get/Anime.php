<?php

namespace Jikan\Get;

use Jikan\Request\Anime as AnimeRequest;

/**
 * Class Anime
 *
 * @package Jikan\Get
 */
class Anime
{
    public $parser;
    public $response;

    /**
     * Anime constructor.
     *
     * @param AnimeRequest $request
     */
    public function __construct(AnimeRequest $request)
    {

        $request->parser->setPath($request->getPath());
        $request->parser->loadRules();
        $request->parser->loadFile();
        $request->parser->parse();

        return $this->response = [
            'status'   => $request->parser->status,
            'response' => (array)$request->parser->model,
        ];
    }

}
