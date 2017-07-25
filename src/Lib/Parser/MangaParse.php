<?php

namespace Jikan\Lib\Parser;

use Jikan\Helper\Utils;
use Jikan\Model\Manga as MangaModel;

class MangaParse extends TemplateParse
{

    public function parse() {
        $this->model = new MangaModel;
    }
}