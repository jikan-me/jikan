<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\SeasonalParse;

class Seasonal extends Get
{

    private $validExtends = [];

    public function __construct($season = null, $year = null) {


        $this->parser = new SeasonalParse;

        $link = BASE_URL . 'anime/season';

        if (!is_null($season) && !is_null($year)) {
            $link .= '/' . $year . '/' . $season;
        }


        $this->parser->setPath($link);
        $this->parser->loadFile();

        $this->response['code'] = $this->parser->status;
        $this->response = array_merge($this->response, $this->parser->parse());
    }

}