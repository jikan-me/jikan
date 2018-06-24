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


        $this->addRule('moreinfo', '~<h2 class="mb8">More Info</h2>~', function() {
            $i = 0;
            $capture = "";

            while(true) {
                if (preg_match('~<div class="mauto clearfix pt24"~', $this->file[$this->lineNo + $i])) {
                    $capture .= $this->file[$this->lineNo + $i];
                    break;
                }

                $capture .= $this->file[$this->lineNo + $i];

                $i++;
            }


            $capture = trim(str_replace(['<h2 class="mb8">More Info</h2>', '<div class="mauto clearfix pt24" style="width:760px;">'], '', $capture));
            $capture = strip_tags(str_replace(['<br>', '<br />', '<br/>'], '\n', $capture));

            $this->model->set('MangaMoreInfo', 'more_info', $capture);

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
