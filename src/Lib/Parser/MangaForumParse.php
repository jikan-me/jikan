<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\MangaForum as MangaForumModel;

class MangaForumParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new MangaForumModel;

        /*
         * Rules
         */



        /*
         * Parsing
         */

        foreach ($this->file as $lineNo => $line) {
            $this->line = $line;
            $this->lineNo = $lineNo;

            $this->find();
        }

        return (array) $this->model;
    }
}
