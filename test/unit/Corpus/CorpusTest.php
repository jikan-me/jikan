<?php

namespace JikanTest\Corpus;

use Jikan\MyAnimeList\MalClient;
use JikanTest\ModelSnapshot;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * Runs every variant in variants.json through its MalClient method, against the recorded page.
 *
 * For each variant: the model passes ModelInvariants, matches its snapshot (snapshots/Corpus/CorpusTest.<id>.json
 * in jikan-fixtures), and a second call builds an equal model. A variant with "expect" must throw that exception
 * instead (404 pages, invalid ids).
 */
class CorpusTest extends TestCase
{
    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function variants(): iterable
    {
        foreach (Variants::all() as $variant) {
            yield $variant['id'] => [$variant];
        }
    }

    /**
     * @param array{id: string, method: string, request: string, args: list<mixed>, expect?: string} $variant
     */
    #[Test]
    #[DataProvider('variants')]
    public function it_parses_the_recorded_page(array $variant): void
    {
        $client = new MalClient(new MockHttpClient(new RecordedPages()));
        $call = fn () => $client->{$variant['method']}(Variants::request($variant));

        if (isset($variant['expect'])) {
            $this->expectException($variant['expect']);
            $call();

            return;
        }

        $model = $call();
        ModelSnapshot::assertMatches($this, $model, false, $variant['id']);
        self::assertEquals($model, $call(), 'A second call on the same recording built a different model.');
    }
}
