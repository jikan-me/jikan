<?php
require_once __DIR__ . '/../vendor/autoload.php';

\VCR\VCR::configure()
    ->setCassettePath('test/fixtures')
;
\VCR\VCR::turnOn();
