<?php
require_once __DIR__ . '/../vendor/autoload.php';
\VCR\VCR::configure()
    ->setCassettePath('test/fixtures')
    ->enableRequestMatchers(['url', 'method', 'query_string'])
;
\VCR\VCR::turnOn();
