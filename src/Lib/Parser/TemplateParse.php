<?php

namespace Jikan\Lib\Parser;

use Jikan\Helper\Utils as Util;

abstract class TemplateParse
{

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
        echo $this->filePath;
    }

    public function loadFile() {
        if (!is_null($this->filePath)) {

            if (Util::isURL($this->filePath)) {
                if (!Util::existsURL($this->filePath)) {
                    http_response_code(404);
                    throw new \Exception("File does not exist");
                }
            } else {
                if (!file_exists($this->filePath)) {
                    throw new \Exception("File does not exist");
                }
            }
            $this->file = @file($this->filePath);
            array_walk($this->file, Util::class.'::trim');

        } else {
            throw new \Exception("File path is null");
        }
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

    public function addRule(string $index, string $regex, Callable $callback, $args = null, $merge = false) {
        $args = $args ?? false;
        $this->rules[$index] = [
            'regex' => $regex,
            'callback' => $callback,
            'args' => $args,
            'merge' => $merge,
            'found' => false
        ];
    }


}