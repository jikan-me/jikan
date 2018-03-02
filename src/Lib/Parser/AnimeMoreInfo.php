<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\AnimeMoreInfo as AnimeMoreInfoModel;

class AnimeMoreInfoParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new AnimeMoreInfoModel;

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
