<?php
require_once __DIR__ . '/../vendor/autoload.php';

$fixturesPath = __DIR__ . "/../vendor/jikan-me/jikan-fixtures/fixtures";

if (!file_exists($fixturesPath)) {
    echo "ERROR: Fixtures not found\n";
    die;
}

if (!is_readable($fixturesPath)) {
    echo "ERROR: Fixtures are not readable\n";
    die;
}


\VCR\VCR::configure()
    ->setCassettePath($fixturesPath)
    ->enableLibraryHooks(['curl'])
    ->enableRequestMatchers(['url', 'method', 'query_string'])
;
\VCR\VCR::turnOn();
