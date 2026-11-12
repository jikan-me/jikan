<?php

declare(strict_types=1);

/**
 * Point ../jikan-fixtures at the fixtures repository's master, without discarding local work.
 *
 * Runs from `composer link-fixtures`. The previous one-liner was
 *
 *     git clone … || git -C ../jikan-fixtures reset --hard origin/master
 *
 * which never fetched, so an existing clone could stay on a month-old ref (the local checkout and CI
 * then disagreed about which recordings exist), and any uncommitted recording or snapshot was
 * destroyed silently. This script fetches first, moves to master, fast-forwards, and refuses to touch
 * a clone that has uncommitted changes.
 *
 *   php scripts/link_fixtures.php
 */
$target = dirname(__DIR__) . '/../jikan-fixtures';
$repository = 'https://github.com/jikan-me/jikan-fixtures.git';

/**
 * Run a command in the fixtures clone and fail loudly; returns its output.
 *
 * @param list<string> $arguments
 */
function git(string $arguments): string
{
    $command = $arguments . ' 2>&1';
    exec($command, $output, $code);

    if ($code !== 0) {
        fwrite(STDERR, "link-fixtures: `{$arguments}` failed:\n" . implode("\n", $output) . "\n");
        exit (1);
    }

    return implode("\n", $output);
}

if (!is_dir($target . '/.git')) {
    $output = [];
    exec(
        'git clone ' . escapeshellarg($repository) . ' ' . escapeshellarg($target) . ' 2>&1',
        $output,
        $code
    );
    echo implode("\n", $output), "\n";

    exit ($code);
}

if (trim(git('git -C ' . escapeshellarg($target) . ' status --porcelain')) !== '') {
    fwrite(
        STDERR,
        "link-fixtures: ../jikan-fixtures has uncommitted changes; commit or stash them first.\n"
    );

    exit (1);
}

git('git -C ' . escapeshellarg($target) . ' fetch --prune origin');
git('git -C ' . escapeshellarg($target) . ' checkout master');
git('git -C ' . escapeshellarg($target) . ' merge --ff-only origin/master');

echo "link-fixtures: ../jikan-fixtures is on master at " . trim(git('git -C ' . escapeshellarg($target) . ' rev-parse --short HEAD')) . "\n";
