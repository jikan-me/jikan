<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\Top as TopModel;

class TopParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {

        $this->model = new TopModel();

        /*
         * Rules
         */
        $this->addRule('top', '~<tr class="ranking-list">~', function() {
            $top = [];
            $i = 0;

            while (true) {
                if (preg_match('~<div class="mauto clearfix pt24"~', $line)) {break;}


                $i++;
            }
        });


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
