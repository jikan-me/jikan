<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\ScheduleParse;

class Schedule extends Get
{

    private $validExtends = [];

    public function __construct($season = null, $year = null) {


        $this->parser = new ScheduleParse;

        $link = BASE_URL . 'anime/season/schedule';

        $this->parser->setPath($link);
        $this->parser->loadFile();

        $this->response['code'] = $this->parser->status;
        $this->response = array_merge($this->response, $this->parser->parse());
    }

}