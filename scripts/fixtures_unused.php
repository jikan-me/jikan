<?php

declare(strict_types=1);

/**
 * List recorded fixtures that no test loads.
 *
 *   php scripts/fixtures_unused.php                    # runs the suite with a fixture log, prints unused files
 *   php scripts/fixtures_unused.php --check            # same, exit 1 if any are unused
 *   php scripts/fixtures_unused.php --from-log=FILE    # reuse the log of a run made with JIKAN_FIXTURE_LOG=FILE
 *
 * Fixtures live in the jikan-me/jikan-fixtures package; a recording nobody loads is dead weight that
 * makes the fixture count look better than the coverage is.
 */
$root = dirname(__DIR__);
$fixtures = $root . '/vendor/jikan-me/jikan-fixtures/fixtures';
$options = getopt('', ['check', 'from-log:']);

$fromLog = isset($options['from-log']);
$log = $fromLog ? (string) $options['from-log'] : (string) tempnam(sys_get_temp_dir(), 'jikan-fixture-log');

if ($fromLog && !is_file($log)) {
    fwrite(STDERR, "No fixture log at $log.\n");
    exit (2);
}

if (!$fromLog) {
    $cmd = sprintf('JIKAN_FIXTURE_LOG=%s %s %s/vendor/bin/phpunit --no-progress > /dev/null 2>&1', escapeshellarg($log), escapeshellarg(PHP_BINARY), escapeshellarg($root));
    passthru($cmd, $rc);
    if ($rc !== 0) {
        fwrite(STDERR, "The test suite failed; fix it before judging which fixtures are unused.\n");
        exit (2);
    }
}

$used = array_flip(array_filter(array_map('trim', file($log) ?: [])));
if (!$fromLog) {
    unlink($log);
}

$all = [];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fixtures, FilesystemIterator::SKIP_DOTS)) as $file) {
    $all[] = basename($file->getPath()) . '/' . $file->getFilename();
}
sort($all);
$unused = array_values(array_filter($all, static fn (string $f): bool => !isset($used[$f])));

printf("%d fixture files, %d loaded by the suite, %d unused\n", count($all), count($all) - count($unused), count($unused));
foreach ($unused as $f) {
    echo "  $f\n";
}

exit (isset($options['check']) && $unused !== [] ? 1 : 0);
