<?php

namespace Jikan\Lib\Parser;

use Jikan\Model\Anime as AnimeModel;

class AnimeParse extends TemplateParse
{

    public function parse() {
        $this->model = new AnimeModel;

        /*
         * Rules
         */

        $this->addRule('link_canonical', '~<link rel="canonical" href="(.*)" />~', function() {
           $this->model->set('link_canonical', $this->matches[1]);
        });

        $this->addRule('title', '~<h1 class="h1"><span itemprop="name">(.*)</span></h1>~', function() {
           $this->model->set('title', $this->matches[1]);
        });

        $this->addRule('title_english', '~<span class="dark_text">English:</span> (.*)~', function() {
            $this->model->set('title_english', $this->matches[1]);
        });

        $this->addRule('title_synonyms', '~<span class="dark_text">Synonyms:</span> (.*)~', function() {
            $this->model->set('title_synonyms', $this->matches[1]);
        });

        $this->addRule('title_japanese', '~<span class="dark_text">Japanese:</span> (.*)~', function() {
            $this->model->set('title_japanese', $this->matches[1]);
        });

        $this->addRule('image_url', '~<img src="(.*)" alt="(.*)" class="ac" itemprop="image">~', function() {
            $this->model->set('image_url', $this->matches[1]);
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