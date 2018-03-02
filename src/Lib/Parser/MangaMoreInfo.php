<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\MangaMoreInfo as MangaMoreInfoModel;

class MangaMoreInfoParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new MangaMoreInfoModel;

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
