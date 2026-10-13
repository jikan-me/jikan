<?php

namespace JikanTest;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Golden-file check for parsed models.
 *
 * A model is dumped to stable JSON (every property, private ones included, class names kept) and compared
 * with <jikan-fixtures>/snapshots/<test class path>.json. Any refactor that changes any parsed field fails.
 *
 * The snapshots live in the jikan-me/jikan-fixtures package, next to the HTML they were parsed from, so
 * this repository stays small and a snapshot always changes in the same place as its fixture.
 *
 * Update the files on purpose, after reviewing the diff (with the fixtures linked to a local checkout,
 * see `composer link-fixtures`, so the new files land in a repository you can commit to):
 *
 *     JIKAN_UPDATE_SNAPSHOTS=1 composer test
 *
 * Models that carry "5 minutes ago" style dates resolve them against the clock, so those tests pass
 * $maskDates = true and the date values are replaced by a placeholder.
 */
final class ModelSnapshot
{
    private const DIR = __DIR__ . '/../../vendor/jikan-me/jikan-fixtures/snapshots';

    public static function assertMatches(TestCase $test, mixed $model, bool $maskDates = false): void
    {
        $actual = json_encode(
            self::dump($model, $maskDates, new \SplObjectStorage()),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR
        ) . "\n";

        $relative = str_replace('\\', '/', substr($test::class, strlen('JikanTest\\')));
        $file = self::DIR . '/' . $relative . '.json';

        if (getenv('JIKAN_UPDATE_SNAPSHOTS') === '1') {
            if (!is_dir(dirname($file))) {
                mkdir(dirname($file), 0o755, true);
            }
            file_put_contents($file, $actual);
            Assert::assertTrue(true);

            return;
        }

        Assert::assertFileExists(
            $file,
            'No snapshot for ' . $test::class . '. Create it with JIKAN_UPDATE_SNAPSHOTS=1 after checking the model.'
        );
        // A Windows checkout may have converted the snapshot to CRLF (core.autocrlf); the model is dumped with LF.
        Assert::assertSame(
            str_replace("\r\n", "\n", (string) file_get_contents($file)),
            $actual,
            'Parsed model differs from its snapshot (' . $relative . '.json). If the change is intended, '
            . 'review it and refresh with JIKAN_UPDATE_SNAPSHOTS=1.'
        );
    }

    private static function dump(mixed $value, bool $maskDates, \SplObjectStorage $seen): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $maskDates ? '<date>' : $value->format(\DATE_ATOM);
        }
        if (is_array($value)) {
            return array_map(static fn ($v) => self::dump($v, $maskDates, $seen), $value);
        }
        if (!is_object($value)) {
            return $value;
        }
        // ArrayAccess, not contains()/attach()/detach(): those are deprecated since PHP 8.5.
        if (isset($seen[$value])) {
            return '<recursion>';
        }
        $seen[$value] = true;

        $out = ['@class' => $value::class];
        for ($class = new \ReflectionObject($value); $class; $class = $class->getParentClass()) {
            foreach ($class->getProperties() as $property) {
                if ($property->isStatic() || $property->getDeclaringClass()->getName() !== $class->getName()) {
                    continue;
                }
                if (!$property->isInitialized($value)) {
                    continue;
                }
                $out[$property->getName()] = self::dump($property->getValue($value), $maskDates, $seen);
            }
        }
        unset($seen[$value]);

        return $out;
    }
}
