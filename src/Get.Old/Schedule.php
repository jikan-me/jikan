<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\ScheduleParse;

/**
 * Class Schedule
 *
 * @package Jikan\Get
 */
class Schedule extends Get
{

    private $validExtends = [];

    /**
     * Schedule constructor.
     *
     * @param null $season
     * @param null $year
     */
    public function __construct($season = null, $year = null)
    {


        $this->parser = new ScheduleParse;

        $link = BASE_URL.'anime/season/schedule';

        $this->parser->setPath($link);
        $this->parser->loadFile();

        $this->response['code'] = $this->parser->status;
        $this->response = array_merge($this->response, $this->parser->parse());
    }
}
