<?php

namespace JikanTest;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Rules every parsed model must satisfy, checked on every model a snapshot test builds.
 *
 * A snapshot only proves the output did not change; these prove it is plausible: valid UTF-8 and
 * JSON-encodable (jikan-rest serialises the models), no leftover markup or whitespace, absolute URLs,
 * positive ids, non-negative numbers, sane dates, consistent pagination, and every public getter
 * callable without throwing.
 *
 * Known violations are recorded per test class in test/unit/__invariants__/<test class>[.<name>].json
 * and may only shrink: a new violation fails, and so does an allowlisted one that no longer occurs
 * (delete it from the file). Record the current set on purpose, after reviewing it:
 *
 *     JIKAN_UPDATE_INVARIANTS=1 composer test
 */
final class ModelInvariants
{
    private const DIR = __DIR__ . '/__invariants__';

    /** Model fields that legitimately carry HTML or free text with markup-like content. */
    private const RICH_TEXT = ['content', 'review', 'synopsis', 'background', 'about', 'body', 'text'];

    public static function assertHold(TestCase $test, mixed $model, string $name = ''): void
    {
        $found = [];
        self::walk($model, '', null, $found, new \SplObjectStorage());
        $found = array_values(array_unique($found));
        sort($found);

        $key = str_replace('\\', '/', substr($test::class, strlen('JikanTest\\'))) . ($name !== '' ? '.' . $name : '');
        $file = self::DIR . '/' . $key . '.json';

        if (getenv('JIKAN_UPDATE_INVARIANTS') === '1') {
            self::record($file, $found);
            Assert::assertTrue(true);

            return;
        }

        $allowed = is_file($file) ? json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR) : [];
        $new = array_values(array_diff($found, $allowed));
        $fixed = array_values(array_diff($allowed, $found));

        Assert::assertSame([], $new, "New invariant violations in $key:\n  " . implode("\n  ", $new));
        Assert::assertSame([], $fixed, "Allowlisted violations no longer occur in $key; remove them from __invariants__/$key.json:\n  " . implode("\n  ", $fixed));
    }

    /**
     * @param list<string> $found
     */
    private static function record(string $file, array $found): void
    {
        if ($found === []) {
            is_file($file) && unlink($file);

            return;
        }
        is_dir(dirname($file)) || mkdir(dirname($file), 0o755, true);
        file_put_contents($file, json_encode($found, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
    }

    /**
     * @param list<string> $found
     */
    private static function walk(mixed $value, string $path, ?string $field, array &$found, \SplObjectStorage $seen): void
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                self::walk($v, $path . (is_int($k) ? '[*]' : "[$k]"), is_int($k) ? $field : (string) $k, $found, $seen);
            }

            return;
        }
        if ($value instanceof \DateTimeInterface) {
            $year = (int) $value->format('Y');
            if ($year < 1900 || $year > (int) date('Y') + 5) {
                $found[] = "date-range $path";
            }

            return;
        }
        if (is_string($value)) {
            self::checkString($value, $path, $field ?? '', $found);

            return;
        }
        if (is_int($value) || is_float($value)) {
            self::checkNumber($value, $path, $field ?? '', $found);

            return;
        }
        if (!is_object($value) || isset($seen[$value])) {
            return;
        }
        $seen[$value] = true;

        $props = [];
        for ($class = new \ReflectionObject($value); $class; $class = $class->getParentClass()) {
            foreach ($class->getProperties() as $p) {
                if ($p->isStatic() || $p->getDeclaringClass()->getName() !== $class->getName() || !$p->isInitialized($value)) {
                    continue;
                }
                $props[$p->getName()] = $p->getValue($value);
                self::walk($props[$p->getName()], $path . '.' . $p->getName(), $p->getName(), $found, $seen);
            }
        }

        if (isset($props['hasNextPage'], $props['lastVisiblePage']) && $props['hasNextPage'] === true && $props['lastVisiblePage'] < 2) {
            $found[] = "pagination $path";
        }

        if (str_starts_with($value::class, 'Jikan\\Model\\')) {
            self::callGetters($value, $path, $found);
        }

        try {
            json_encode($props, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $found[] = "json $path";
        }
    }

    /**
     * @param list<string> $found
     */
    private static function checkString(string $s, string $path, string $field, array &$found): void
    {
        if (!mb_check_encoding($s, 'UTF-8')) {
            $found[] = "utf8 $path";

            return;
        }
        if ($s === '') {
            $found[] = "empty-string $path";

            return;
        }
        if (str_contains($s, "\u{00A0}")) {
            $found[] = "nbsp $path";
        }
        if (trim($s) !== $s) {
            $found[] = "edge-whitespace $path";
        }
        $rich = in_array(strtolower($field), self::RICH_TEXT, true);
        if (!$rich && preg_match('/&(?:[a-z]+|#\d+|#x[0-9a-f]+);/i', $s)) {
            $found[] = "html-entity $path";
        }
        if (!$rich && preg_match('~</?[a-z][a-z0-9]*[\s>/]~i', $s)) {
            $found[] = "html-tag $path";
        }
        if (preg_match('/(^url$|Url$)/', $field) && !preg_match('~^https?://~', $s)) {
            $found[] = "relative-url $path";
        }
    }

    /**
     * @param list<string> $found
     */
    private static function checkNumber(int|float $n, string $path, string $field, array &$found): void
    {
        $isId = (bool) preg_match('/^(malId|id)$|Id$/', $field);
        if ($isId && $n <= 0) {
            $found[] = "non-positive-id $path";
        }
        if (!$isId && $n < 0) {
            $found[] = "negative-number $path";
        }
        if (strtolower($field) === 'score' && $n > 10) {
            $found[] = "score-range $path";
        }
    }

    /**
     * @param list<string> $found
     */
    private static function callGetters(object $model, string $path, array &$found): void
    {
        foreach ((new \ReflectionObject($model))->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->isStatic() || $m->getNumberOfRequiredParameters() > 0 || $m->isConstructor()
                || (str_starts_with($m->getName(), '__') && $m->getName() !== '__toString')) {
                continue;
            }
            try {
                $m->invoke($model);
            } catch (\Throwable $e) {
                $found[] = 'getter-throws ' . $path . '->' . $m->getName() . '()';
            }
        }
    }
}
