<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\MangaNewsParse;
use Jikan\Lib\Parser\MangaCharacterParse;
use Jikan\Lib\Parser\MangaParse;


class Manga extends Get
{

    public $canonical_path;

    private $validExtends = [CHARACTERS, NEWS];

    public function __construct($id = null, $extend = null) {


        if (is_null($id)) {
            throw new \Exception('ID/Path not given');
        }

        $this->id = $id;

        $this->parser = new MangaParse;
        $this->parser->setPath(
            (
                is_int($this->id) || ctype_digit($this->id)    
            ) ? BASE_URL . MANGA_ENDPOINT . $this->id : $this->id
        );
        $this->parser->loadFile();

        //$this->response = $this->parser->parse();
        $this->response = array_merge($this->response, $this->parser->parse());

        $this->canonical_path = $this->parser->model->get('Manga', 'link_canonical');


        if (!empty($extend)) {
            $this->extend = $extend;

            foreach ($this->extend as $key => $extend) {

                if (is_string($key)) {
                    $this->extend = $key;
                    $this->extendArgs = $extend;

                    if (!in_array($this->extend, $this->validExtends)) {
                        throw new \Exception('Unsupported parse request');
                    }

                    $this->{$this->extend}($this->extendArgs);
                } elseif (is_int($key)) {
                    $this->extend = $extend;

                    if (!in_array($this->extend, $this->validExtends)) {
                        throw new \Exception('Unsupported parse request');
                    }

                    $this->{$this->extend}();
                }
            }
        }

    }

    private function characters() {
        $this->parser = new MangaCharacterParse;

        $this->parser->setPath($this->canonical_path.'/characters');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function news() {
        $this->parser = new MangaNewsParse;

        $this->parser->setPath($this->canonical_path.'/news');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

}