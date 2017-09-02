<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\AnimeCharacterStaff as AnimeCharacterStaffModel;

class AnimeCharacterStaffParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new AnimeCharacterStaffModel();

        /*
         * Rules
         */



        $this->loadFile($this->filePath);


        // rules



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
