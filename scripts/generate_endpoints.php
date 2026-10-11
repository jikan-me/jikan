<?php

declare(strict_types=1);

/**
 * Generate / verify jikan's machine-readable endpoint catalog.
 *
 *   php scripts/generate_endpoints.php --write   # (re)write .backend-suite/endpoints.json + llm.txt
 *   php scripts/generate_endpoints.php --check   # verify invariants + drift, exit 1 on failure
 *
 * DERIVED COLLATERAL (see AGENTS.md §4, .backend-suite/survey.json precedent). No fact here may be
 * authored by hand — change the code or docs, re-run --write, and the --check gate will prove the
 * file is still derivable. The `notes`/coverage gaps below are reported loudly; gate semantics are:
 *
 *   * HARD gate  — an endpoint whose Request/Parser/Model cannot be resolved to a real file;
 *                  the file is stale vs the tree; a MalClient method's @deprecated flag disagrees
 *                  with the manifest. Any of these exits 1.
 *   * REPORTED   — a public method with no parser test (coverage debt). Printed with a count, does
 *                  NOT fail --check: recording the gap is the point; closing it is fixture work.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use Jikan\MyAnimeList\MalClient;

const MAL_CLIENT  = 'src/MyAnimeList/MalClient.php';
const ENDPOINTS   = '.backend-suite/endpoints.json';
const LLM_TXT     = 'llm.txt';

/** @return list<array{name:string,start:int,body:string,signature:string,docblock:string}> */
function parse_methods(string $client) : array
{
    $text = file_get_contents($client);
    $out  = [];
    $re   = '/(?:(?:\/\*\*.*?\*\/)\s*)?public\s+function\s+(\w+)\s*\(([^)]*)\)\s*:\s*(\??[\w\\\\|]+)\s*\{/s';
    if (!preg_match_all($re, $text, $m, PREG_OFFSET_CAPTURE)) {
        return $out;
    }
    $n = count($m[0]);
    for ($i = 0; $i < $n; $i++) {
        $name      = $m[1][$i][0];
        $openPos   = $m[0][$i][1] + strlen($m[0][$i][0]) - 1; // position of '{'
        $body      = brace_body(substr($text, $openPos));
        $docblock  = '';
        $sig       = sprintf('%s(%s): %s', $name, $m[2][$i][0], $m[3][$i][0]);
        // docblock immediately preceding the method
        $before = substr($text, max(0, $m[1][$i][1] - 4096), $m[1][$i][1] - max(0, $m[1][$i][1] - 4096));
        if (preg_match('~/\*\*(.*?)\*/~s', $before, $d)) {
            $docblock = $d[1];
        }
        $out[] = ['name' => $name, 'body' => $body, 'signature' => $sig,
                  'docblock' => $docblock, 'return' => $m[3][$i][0]];
    }
    return $out;
}

/** Body of the brace-block beginning at `{` (string-literal aware enough for this codebase). */
function brace_body(string $from_open) : string
{
    $depth = 0; $i = 0; $n = strlen($from_open); $quote = '';
    for (; $i < $n; $i++) {
        $c = $from_open[$i];
        if ($quote !== '') {
            if ($c === '\\') { $i++; }
            if ($c === $quote) { $quote = ''; }
            continue;
        }
        if ($c === "'" || $c === '"') { $quote = $c; continue; }
        if ($c === '{') { $depth++; continue; }
        if ($c === '}') {
            $depth--;
            if ($depth === 0) { return substr($from_open, 0, $i + 1); }
        }
    }
    return $from_open;
}

/** `Request\Anime\AnimeRequest` (source-relative) -> FQCN `Jikan\Request\Anime\AnimeRequest`. */
function qualify(string $rel) : string
{
    $rel = ltrim($rel, '\\');
    return strlen($rel) && !str_contains($rel, ' ') && !str_starts_with($rel, 'Jikan\\')
        ? 'Jikan\\' . $rel : $rel;
}

/** FQCN -> repo-relative *.php path (PSR-4 `Jikan\` => src/). */
function fqcn_file(string $fqcn) : ?string
{
    if (!str_starts_with($fqcn, 'Jikan\\')) { return null; }
    $path = 'src/' . str_replace('\\', '/', substr($fqcn, 6)) . '.php';
    return is_file($path) ? $path : null;
}

function request_class_from_signature(string $sig) : ?string
{
    if (preg_match('~([\w\\\\]+)\s+\$request\b~', $sig, $m)) {
        return qualify($m[1]);
    }
    return null;
}

function parser_from_body(string $body) : ?string
{
    if (preg_match('~new\s+\\\\?(?:Jikan\\\\)?(Parser\\\\[\w\\\\]+)\s*\(~', $body, $m)) {
        return qualify($m[1]);
    }
    return null;
}

/** Test files that construct a parser FQCN (keyed FQCN => list of [class, file]). */
function test_index() : array
{
    $idx = [];
    $it  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('test/unit', FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if ($file->getExtension() !== 'php') { continue; }
        $text = file_get_contents($file->getPathname());
        if (!preg_match('~namespace\s+([\w\\\\]+);~', $text, $ns)
            || !preg_match('~class\s+(\w+)\s+extends\s+(?:\\\\JikanTest\\\\)?TestCase~', $text, $cls)) {
            continue;
        }
        $pick = []; // FQCN parsers this file constructs, from FQCN or imported short names
        if (preg_match_all('~new\s+\\\\?(?:Jikan\\\\)?(Parser\\\\[\w\\\\]+)\s*\(~', $text, $fq)) {
            foreach ($fq[1] as $p) { $pick[qualify($p)] = true; }
        }
        // imported short names: `use Jikan\Parser\Search\AnimeSearchParser;` then `new AnimeSearchParser(`
        $aliases = [];
        if (preg_match_all('~^use\s+(Jikan\\\\Parser\\\\[\w\\\\]+);~m', $text, $us)) {
            foreach ($us[1] as $u) { $aliases[substr($u, strrpos($u, '\\') + 1)] = $u; }
        }
        if ($aliases && preg_match_all('~\b(\w+)\s*\(~', $text, $short)) {
            foreach ($short[1] as $s) {
                if (isset($aliases[$s])) { $pick[$aliases[$s]] = true; }
            }
        }
        foreach (array_keys($pick) as $p) {
            $idx[$p][] = [$ns[1] . '\\' . $cls[1], $file->getPathname()];
        }
    }
    return $idx;
}

/** Templated request path from getPath() (`sprintf('…%s…', $this->id)` -> `…{id}…`), or null. */
function path_pattern(string $request_file) : ?string
{
    $text = file_get_contents($request_file);
    if (!preg_match('~function\s+getPath\s*\([^)]*\)\s*:\s*string\s*\{.*?return\s+(.*?);~s', $text, $m)) {
        return null;
    }
    $stmt = trim($m[1]);
    if (preg_match('~^sprintf\(\s*[\'"]([^\'"]*)[\'"]\s*(?:,\s*(.*))?\)?$~s', $stmt, $f)) {
        $fmt     = $f[1];
        $argsRaw = isset($f[2]) ? trim(rtrim(trim($f[2]), ')')) : '';
        $args    = $argsRaw === '' ? [] : array_map('trim', explode(',', $argsRaw));
        $argIdx  = 0;
        return preg_replace_callback('~%[0-9]*[sdu]~', function () use ($args, &$argIdx) {
            $raw = $argIdx < count($args) ? $args[$argIdx] : (string) $argIdx;
            $argIdx++;
            if (preg_match('~\$this->(\w+)\b~', $raw, $nm) || preg_match('~\$(\w+)\b~', $raw, $nm)) {
                return '{' . $nm[1] . '}';
            }
            return '{' . $raw . '}';
        }, $fmt);
    }
    if (preg_match('~^[\'"]([^\'"]+)[\'"]$~s', $stmt, $d)) {
        return $d[1];
    }
    return null;
}

function build_index(string $client_path, string $endpoints_path) : array
{
    $tests = test_index();
    $rows  = [];
    foreach (parse_methods($client_path) as $m) {
        if (!str_starts_with($m['name'], 'get')) { continue; }
        $request = request_class_from_signature($m['signature']);
        $raw     = str_contains($m['body'], '\\json_decode(');
        $parser  = $raw ? null : parser_from_body($m['body']);
        $rt      = ltrim($m['return'], '?\\');
        $kind    = $raw ? 'raw'
                 : (str_starts_with($rt, 'Model\\') ? 'model' : (($rt === 'array') ? 'array' : 'scalar'));
        $model   = model_for($rt, $kind, $parser);

        $row = [
            'method'   => $m['name'],
            'kind'     => $kind,
            'domain'   => $request ? strtolower(explode('\\', $request)[2] ?? '') : null,
            'request'  => request_ref($request),
            'parser'   => parser_ref($parser),
            'model'    => fqcn_ref($model),
            'returnType' => $m['return'],
            'parameters' => parameters($m['signature']),
            'pathPattern' => $request && ($rf = fqcn_file($request)) ? path_pattern($rf) : null,
            'paged'    => $request && ($rf = fqcn_file($request))
                          && preg_match('~\$page\b~', file_get_contents($rf)),
            'deprecated' => str_contains($m['docblock'], '@deprecated'),
            'test'     => !$parser ? null : test_ref($tests, $parser),
            'notes'    => $raw ? 'raw JSON client call — bypasses the parser layer' : $m['name'],
        ];
        $rows[] = $row;
    }
    usort($rows, fn($a, $b) => strcmp($a['method'], $b['method']));
    $domains = [];
    foreach ($rows as $r) { if ($r['domain']) { $domains[$r['domain']] = true; } }

    return [
        '_source' => [
            'generatedBy' => 'scripts/generate_endpoints.php',
            'generate'    => 'php scripts/generate_endpoints.php --write',
            'check'       => 'php scripts/generate_endpoints.php --check',
            '_note'       => 'DERIVED COLLATERAL — regenerable, source-stamped; never hand-edit. Contact: MalClient + Request/Parser/Model files + test tree.',
        ],
        'schemaVersion' => 1,
        'generatedAt'   => gmdate('Y-m-d\TH:i:s\Z'),
        'verification'  => ['commit' => current_commit(), 'methodsSeen' => count($rows), 'domainsSeen' => count($domains)],
        'client'        => ['class' => 'Jikan\\MyAnimeList\\MalClient', 'file' => MAL_CLIENT],
        'endpoints'     => $rows,
    ];
}

/** Best-effort model FQCN for a return type / kind. */
function model_for(string $rt, string $kind, ?string $parser) : ?string
{
    if ($kind === 'model' && preg_match('~^Model\\\\([\w\\\\]+)$~', $rt, $mm)) {
        $fq = 'Jikan\\Model\\' . $mm[1];
        return fqcn_file($fq) ? $fq : null; // null => HARD (surfaces dead references like AnimeSearchAlt)
    }
    if ($kind !== 'array' || !$parser || !($pf = fqcn_file($parser))) { return null; }
    // If the parser declares a getModel() type, prefer it (best-effort; null is fine for arrays).
    if (preg_match('~function getModel\s*\([^)]*\)\s*:\s*(\??[\w\\\\]+)~s', file_get_contents($pf), $gm)) {
        $t = ltrim($gm[1], '?\\');
        foreach (['Jikan\\Model\\' . $t, 'Jikan\\Model\\' . parser_domain($parser) . '\\' . $t] as $cand) {
            if (fqcn_file($cand)) { return $cand; }
        }
    }
    return null;
}

function parser_domain(string $parser) : string
{
    $p = explode('\\', $parser);
    return $p[2] ?? '';
}

function request_ref(?string $fq)   : ?array { return $fq ? fqcn_ref($fq) : null; }
function fqcn_ref(?string $fq)      : ?array
{
    if (!$fq) { return null; }
    $file = fqcn_file($fq);
    return ['class' => $fq, 'file' => $file ?? null];
}
function parser_ref(?string $fq)    : ?array
{
    if (!$fq) { return null; }
    $file = fqcn_file($fq);
    return ['class' => $fq, 'file' => $file ?? null];
}
function test_ref(array $tests, string $parser) : ?array
{
    $hit = $tests[$parser][0] ?? null;
    return $hit ? ['class' => $hit[0], 'file' => $hit[1]] : null;
}
function parameters(string $signature) : array
{
    if (!preg_match('~\(([^)]*)\)~', $signature, $m) || trim($m[1]) === '') { return []; }
    $out = [];
    foreach (explode(',', $m[1]) as $p) {
        $p = trim($p);
        if ($p === '') { continue; }
        if (preg_match('~^(?:\\\\?[\w\\\\|]+|array)\s+(&)?\$(\w+)~', $p, $pm)) {
            $out[] = ['name' => $pm[2], 'type' => str_replace(' ', '', $pm[1]), 'required' => true];
        }
    }
    return $out;
}
function current_commit() : string
{
    $h = @shell_exec('git rev-parse --short HEAD 2>/dev/null');
    return trim($h ?: 'unknown');
}

function render_llm_txt(array $index) : string
{
    $agents = file_get_contents('AGENTS.md');
    $para   = '';
    if (preg_match('~^## 1\..*?\n\n?(.+?)\n\s*\n~ms', $agents, $m)) {
        $para = trim($m[1]);
    }
    $lines = ["# Jikan (v5) — machine-readable index", "", "## Architecture", "", $para, "", "## Endpoint catalog"];
    $lines[] = "";
    $lines[] = "| method | path | request | parser | model | test | deprecated |";
    $lines[] = "|---|---|---|---|---|---|---|";
    foreach ($index['endpoints'] as $r) {
        $lines[] = sprintf(
            '| %s | %s | %s | %s | %s | %s | %s |',
            $r['method'],
            $r['pathPattern'] ?? '—',
            $r['request']['class'] ?? '—',
            $r['parser']['class'] ?? '—',
            $r['model']['class'] ?? '—',
            $r['test']['class'] ?? '—',
            $r['deprecated'] ? 'yes' : 'no'
        );
    }
    $lines[] = "";
    $lines[] = "Generated by `scripts/generate_endpoints.php`. Source-stamped; never hand-edit.";
    return implode("\n", $lines) . "\n";
}

function verify(array $new, string $endpoints_json, string $llm_txt) : int
{
    $fail = 0;
    // HARD: resolution
    foreach ($new['endpoints'] as $r) {
        if (!$r['request'] || !$r['request']['file']) {
            echo "HARD: {$r['method']} — request unresolved\n";
            $fail = 1;
        }
        // parser is required unless the endpoint is a raw json_decode call
        if ($r['kind'] !== 'raw' && (!$r['parser'] || !$r['parser']['file'])) {
            echo "HARD: {$r['method']} — parser unresolved\n";
            $fail = 1;
        }
        // a 'model'-kind endpoint MUST resolve its model type to a real file
        if ($r['kind'] === 'model' && (!$r['model'] || !$r['model']['file'])) {
            echo "HARD: {$r['method']} — model type '{$r['returnType']}' has no file\n";
            $fail = 1;
        }
        if ($r['deprecated'] !== false) { // no MalClient method is deprecated today
            echo "HARD: {$r['method']} — deprecated flag set while none expected\n";
            $fail = 1;
        }
    }
    // REPORTED: coverage gap
    $uncovered = array_values(array_filter($new['endpoints'], fn($r) => !$r['test']));
    if ($uncovered) {
        echo 'COVERAGE (' . count($uncovered) . ' endpoint(s) with no parser test — reported, not failing):' . "\n";
        foreach ($uncovered as $r) { echo "  - {$r['method']}" . ($r['parser']['class'] ?? ' ') . "\n"; }
    }
    if (!$uncovered) {
        echo "COVERAGE: every endpoint has a parser test\n";
    }
    // HARD: drift vs disk (normalise generatedAt + verification.commit — both legitimately
    //   change whenever the file is regenerated, so content-equality is the real check)
    $norm = static function (array $i) : array {
        $i['generatedAt'] = 'NOW';
        $i['verification']['commit'] = 'HEAD';
        return $i;
    };
    $jsonOnDisk = json_decode((string) file_get_contents($endpoints_json), true);
    $jsonOk = $jsonOnDisk !== null
        && trim(json_encode($norm($jsonOnDisk))) === trim(json_encode($norm($new)));
    $llmOk  = trim(file_get_contents($llm_txt)) === trim(render_llm_txt($new));
    if (!$jsonOk) { echo "HARD: {$endpoints_json} is stale — run --write\n"; $fail = 1; }
    if (!$llmOk)  { echo "HARD: {$llm_txt} is stale — run --write\n"; $fail = 1; }
    echo $fail ? "\nendpoint manifest: INCONSISTENT\n" : "\nendpoint manifest: OK (coverage debt reported above)\n";
    return $fail;
}

$mode = $argv[1] ?? '';
$index = build_index(MAL_CLIENT, ENDPOINTS);
$rc = 0;

if ($mode === '--check') {
    $rc = verify($index, ENDPOINTS, LLM_TXT);
}
if ($mode === '--write') {
    file_put_contents(ENDPOINTS, json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    file_put_contents(LLM_TXT, render_llm_txt($index));
    echo 'wrote ' . ENDPOINTS . ' and ' . LLM_TXT . ' (' . $index['verification']['methodsSeen'] . ' endpoints, '
        . $index['verification']['domainsSeen'] . ' domains)'. PHP_EOL;
    echo 'uncovered: ' . count(array_filter($index['endpoints'], fn($r) => !$r['test'])) . PHP_EOL;
}
if (!in_array($mode, ['--check', '--write'], true)) {
    echo "usage: php scripts/generate_endpoints.php [--write|--check]\n";
    $rc = 2;
}

exit ($rc);