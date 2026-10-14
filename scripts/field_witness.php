<?php

declare(strict_types=1);

/**
 * Report model fields that no recorded page ever fills.
 *
 *   php scripts/field_witness.php
 *
 * Reads every model snapshot in the jikan-me/jikan-fixtures package and lists, per model class, the
 * properties that are null, "", [] or false in every snapshot. Such a field is either one MyAnimeList no
 * longer shows, a parser bug, or a variant no fixture covers yet; each entry is a question to answer,
 * usually by recording a page that has the field. Report only: it never fails.
 */
$dir = dirname(__DIR__) . '/vendor/jikan-me/jikan-fixtures/snapshots';

/** @var array<string, array<string, bool>> $seen class => property => ever non-empty */
$seen = [];

$walk = static function (mixed $node) use (&$walk, &$seen): void {
    if (!is_array($node)) {
        return;
    }
    if (isset($node['@class'])) {
        $class = $node['@class'];
        foreach ($node as $prop => $value) {
            if ($prop === '@class') {
                continue;
            }
            $filled = !($value === null || $value === '' || $value === [] || $value === false);
            $seen[$class][$prop] = ($seen[$class][$prop] ?? false) || $filled;
        }
    }
    foreach ($node as $value) {
        $walk($value);
    }
};

$files = 0;
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)) as $file) {
    if ($file->getExtension() !== 'json') {
        continue;
    }
    $walk(json_decode((string) file_get_contents($file->getPathname()), true));
    $files++;
}

$empty = [];
$total = 0;
foreach ($seen as $class => $props) {
    foreach ($props as $prop => $filled) {
        $total++;
        if (!$filled) {
            $empty[] = str_replace('Jikan\\Model\\', '', $class) . '::' . $prop;
        }
    }
}
sort($empty);

printf("%d snapshots, %d model classes, %d properties; %d never filled by any recorded page:\n", $files, count($seen), $total, count($empty));
foreach ($empty as $e) {
    echo "  $e\n";
}
