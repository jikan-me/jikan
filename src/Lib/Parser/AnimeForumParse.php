<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\AnimeForum as AnimeForumModel;

class AnimeForumParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new AnimeForumModel;

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
