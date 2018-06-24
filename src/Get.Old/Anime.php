<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\AnimeParse;
use Jikan\Lib\Parser\AnimeCharacterStaffParse;
use Jikan\Lib\Parser\AnimeEpisodeParse;
use Jikan\Lib\Parser\AnimeNewsParse;
use Jikan\Lib\Parser\AnimeVideoParse;
use Jikan\Lib\Parser\AnimeStatsParse;
use Jikan\Lib\Parser\AnimePicturesParse;
use Jikan\Lib\Parser\AnimeForumParse;
use Jikan\Lib\Parser\AnimeMoreInfoParse;


class Anime extends Get
{

    public $canonical_path;

    private $validExtends = [CHARACTERS_STAFF, EPISODES, NEWS, VIDEOS, STATS, PICTURES, FORUM, MORE_INFO];

	public function __construct($id = null, $extend = null) {


        if (is_null($id)) {
            throw new \Exception('No ID/Path Given');
        }

        $this->id = $id;

        $this->parser = new AnimeParse;
        $this->parser->setPath(
            (
                is_int($this->id) || ctype_digit($this->id)    
            ) ? BASE_URL . ANIME_ENDPOINT . $this->id : $this->id
        );
        $this->parser->loadFile();

        $this->response['code'] = $this->parser->status;
        $this->response = array_merge($this->response, $this->parser->parse());

        $this->canonical_path = $this->parser->model->get('Anime', 'link_canonical');



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

	private function episodes($page=1) {
	    $page = ($page < 1) ? $page = 1 : $page;
	    $this->parser = new AnimeEpisodeParse;
	    //echo $this->canonical_path.'?offset='.(($page-1)*100);

        $this->parser->setPath($this->canonical_path.'/episode?offset='.(($page-1)*100));
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());

	}

	private function charactersStaff() {
	    $this->parser = new AnimeCharacterStaffParse;

	    $this->parser->setPath($this->canonical_path.'/characters');
	    $this->parser->loadFile();

	    $this->response = array_merge($this->response, $this->parser->parse());
	}

    private function news() {
        $this->parser = new AnimeNewsParse;

        $this->parser->setPath($this->canonical_path.'/news');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function videos() {
        $this->parser = new AnimeVideoParse;

        $this->parser->setPath($this->canonical_path.'/video');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function stats() {
        $this->parser = new AnimeStatsParse;

        $this->parser->setPath($this->canonical_path.'/stats');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function pictures() {
        $this->parser = new AnimePicturesParse;

        $this->parser->setPath($this->canonical_path.'/pics');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function forum() {
        $this->parser = new AnimeForumParse;

        $this->parser->setPath($this->canonical_path.'/forum');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }

    private function moreInfo() {
        $this->parser = new AnimeMoreInfoParse;

        $this->parser->setPath($this->canonical_path.'/moreinfo');
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse());
    }


}