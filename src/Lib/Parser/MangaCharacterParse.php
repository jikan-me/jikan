<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\MangaCharacter as MangaCharacterModel;

class MangaCharacterParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new MangaCharacterModel();

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
