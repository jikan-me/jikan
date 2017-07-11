<?php

namespace Jikan\Lib\Parser;

use Jikan\Helper\Utils;
use Jikan\Model\Anime as AnimeModel;

abstract class TemplateParse
{

    public $filePath;
    public $data;
    public $model;

    public function parse() {

        $this->model = new AnimeModel;
    }

    public function setPath($filePath) {
        $this->filePath = $filePath;
    }
}