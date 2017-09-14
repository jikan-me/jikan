<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\AnimeCharacterStaffParse;
use Jikan\Lib\Parser\AnimeParse;
use Jikan\Lib\Parser\AnimeEpisodeParse;


class Anime extends Get
{

    public $canonical_path;

    private $validExtends = [CHARACTERS_STAFF, EPISODES];

	public function __construct($id = null, $extend = null) {


        if (is_null($id)) {
            throw new \Exception('ID/Path not given');
        }

        $this->id = $id;

        $this->parser = new AnimeParse;
        $this->parser->setPath(BASE_URL . ANIME_ENDPOINT . $this->id);
        $this->parser->loadFile();

        //$this->response = $this->parser->parse();
        $this->response = array_merge($this->response, $this->parser->parse());

        $this->canonical_path = $this->parser->model->get('Anime', 'link_canonical');

        if (!empty($extend)) {

            $this->extend = $extend;


            foreach ($this->extend as $key => $extend) {

                if (is_array($extend)) {

                    if (!is_string(key($extend))) {
                        throw new \Exception('No arguments set for extend');
                    }

                    $this->extend = key($extend);
                    $this->extendArgs = current($extend);

                    if (!in_array($this->extend, $this->validExtends)) {
                        throw new \Exception('Unsupported parse request');
                    }

                    $this->{$this->extend}($this->extendArgs);
                } else {
                    $this->extend = $extend;

                    if (!in_array($this->extend, $this->validExtends)) {
                        throw new \Exception('Unsupported parse request');
                    }

                    $this->{$this->extend}($this->extendArgs);
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

        //array_merge($this->response, $this->parser->parse());
        //$this->response = $this->parser->parse();

	}

	private function charactersStaff() {
	    $this->parser = new AnimeCharacterStaffParse;

	    $this->parser->setPath($this->canonical_path.'/characters');
	    $this->parser->loadFile();

	    $this->response = array_merge($this->response, $this->parser->parse());
	}

}