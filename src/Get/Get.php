<?php

namespace Jikan\Get;

abstract class Get
{
    public $id;
    public $status;
    public $query;
    public $path;
    public $model;
    public $parser;
    public $response = [];
    public $extend;
    public $extendArgs;

}