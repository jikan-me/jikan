<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (!file_exists('jikan-fixtures/fixtures')) {
    echo "ERROR: jikan-fixtures/fixtures not found\n";
    echo "Clone the fixtures repo into project root directory\n";
    echo "git clone https://github.com/jikan-me/jikan-fixtures.git\n";
    die;
}


\VCR\VCR::configure()
    ->setCassettePath('jikan-fixtures/fixtures')
    ->enableLibraryHooks(['curl'])
    ->enableRequestMatchers(['url', 'method', 'query_string'])
;
\VCR\VCR::turnOn();
