<?php

namespace Jikan\Lib\Parser;


class MangaParse extends TemplateParse
{

    public function parse() {
        $this->model = new MangaModel;
    }
}