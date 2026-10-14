<?php

namespace JikanTest\Corpus;

use Jikan\Request\RequestInterface;

/**
 * The variant corpus: one entry per MyAnimeList page worth testing, run end-to-end through MalClient.
 *
 * Each entry in variants.json names a MalClient method and the Jikan\Request\* it takes. The page it fetches is
 * recorded once into the jikan-me/jikan-fixtures package (pages/<key>.html plus pages/<key>.meta.json with the
 * URL, HTTP status and recording time) by `php scripts/record_variants.php --live`. Adding a variant is one line
 * here plus one recording; no new test class.
 */
final class Variants
{
    public const FILE = __DIR__ . '/variants.json';

    public const PAGES = __DIR__ . '/../../../vendor/jikan-me/jikan-fixtures/pages';

    /**
     * @return list<array{id: string, method: string, request: string, args: list<mixed>, tags?: list<string>, expect?: string}>
     */
    public static function all(): array
    {
        return json_decode((string) file_get_contents(self::FILE), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array{request: string, args: list<mixed>} $variant
     */
    public static function request(array $variant): RequestInterface
    {
        $class = 'Jikan\\Request\\' . $variant['request'];

        return new $class(...$variant['args']);
    }

    /** The recording key: the same hash the per-test fixtures use, so a page is addressed by method + URL. */
    public static function key(string $method, string $url): string
    {
        return sha1(json_encode(['method' => $method, 'url' => $url]));
    }
}
