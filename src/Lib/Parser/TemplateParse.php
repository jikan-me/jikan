<?php

namespace Jikan\Lib\Parser;

use Jikan\Helper\Utils as Util;
use Jikan\Jikan;

abstract class TemplateParse
{

    public $status;
    public $file;
    public $filePath;
    public $data;
    public $model;

    public $rules = [];
    public $matches = [];
    public $line;
    public $lineNo;

    public function setPath($filePath) {
        $this->filePath = $filePath;
    }

    public function setStatus($status) {
        $this->status = (int) $status;
    }

    public function loadFile() {
        if (is_null($this->filePath)) {
            throw new \Exception("File path is null");
        }
        $response = Jikan::$guzzle->get($this->filePath);
        if($response->getStatusCode() === 429) {
            throw new \Exception("MyAnimeList Rate Limit reached");
        }
        if($response->getStatusCode() !== 200) {
            throw new \Exception("File does not exist");
        }
        $this->file = (string) $response->getBody();
        $this->file = explode(PHP_EOL, $this->file);
        array_walk($this->file, Util::class.'::trim'); // bystanders begone!
    }

    public function find() {
        foreach ($this->rules as $index => $value) {
            if (!$value['found']) {
                if (preg_match($value['regex'], $this->line, $this->matches)) {
                    if ($value['merge']) {
                        $this->rules[$index] = array_merge($this->rules[$index], ($value['args'] !== false) ? $value['callback'](...$value['args']) : $value['callback']());
                    } else {
                        $this->rules[$index] = ($value['args'] !== false) ? $value['callback'](...$value['args']) : $value['callback']();
                    }

                    $this->rules[$index]['found'] = true;
                }
            }
        }
    }

    public function addRule(String $index, String $regex, Callable $callback, $args = null, Bool $merge = false) {
        $args = $args ?? false; // The reason you need PHP 7.1+
        $this->rules[$index] = [
            'regex' => $regex,
            'callback' => $callback,
            'args' => $args,
            'merge' => $merge,
            'found' => false
        ];
    }


}