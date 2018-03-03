<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\MangaParse;
use Jikan\Lib\Parser\MangaCharacterParse;
use Jikan\Lib\Parser\MangaNewsParse;
use Jikan\Lib\Parser\MangaStatsParse;
use Jikan\Lib\Parser\MangaPicturesParse;
use Jikan\Lib\Parser\MangaForumParse;
use Jikan\Lib\Parser\MangaMoreInfoParse;


class Manga extends Get
{

    public $canonical_path;

    private $validExtends = [CHARACTERS, NEWS, STATS, PICTURES, FORUM, MORE_INFO];

    public function __construct($id = null, $extend = null) {


        if (is_null($id)) {
            throw new \Exception('No ID/Path Given');
        }

        $this->id = $id;

        $this->parser = new MangaParse;
        $this->parser->setPath(
            (
                is_int($this->id) || ctype_digit($this->id)    
            ) ? BASE_URL . MANGA_ENDPOINT . $this->id : $this->id
        );
        $this->parser->loadFile();

        $this->response['code'] = $this->parser->status;
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

    private function stats() {
        $this->parser = new MangaStatsParse;

        $this->parser->setPath($this->canonical_path.'/stats');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function pictures() {
        $this->parser = new MangaPicturesParse;

        $this->parser->setPath($this->canonical_path.'/pics');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function forum() {
        $this->parser = new MangaForumParse;

        $this->parser->setPath($this->canonical_path.'/forum');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function moreInfo() {
        $this->parser = new MangaMoreInfoParse;

        $this->parser->setPath($this->canonical_path.'/moreinfo');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

}