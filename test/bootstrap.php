<?php
require_once __DIR__ . '/../vendor/autoload.php';

\VCR\VCR::configure()
    ->setCassettePath(__DIR__ . "/../vendor/jikan-me/jikan-fixtures/fixtures")
    ->enableLibraryHooks(['curl'])
    ->enableRequestMatchers(['url', 'method', 'query_string'])
;
\VCR\VCR::turnOn();
