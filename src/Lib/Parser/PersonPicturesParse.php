<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\PersonPictures as PersonPicturesModel;

class PersonPicturesParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new PersonPicturesModel;

        /*
         * Rules
         */

        $this->addRule('images', '~<h2 class="mb8">~', function() {
            $i = 0;

            while(true) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~<div class="fl-l">~', $line)) {
                    break;
                }

                if (preg_match_all('~<div class="picSurround"><a href="(.*?)" title="(.*?)" class="js-picture-gallery" rel="(.*?)"><img src="(.*?)" alt="(.*?)"></a></div>~', $line, $this->matches)) {
                    $this->model->set(
                        'PersonPictures', 
                        'image', 
                        array_merge(
                            $this->model->get('PersonPictures', 'image'),
                            (is_array($this->matches[4]) ? $this->matches[4] : [$this->matches[4]])
                        )
                    );
                }

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
