<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/Core.php';

use srun\campay\Core;

function assertSameValue($expected, $actual, string $message): void
{
    if ($expected !== $actual) {
        fwrite(
            STDERR,
            $message . "\nExpected: " . var_export($expected, true) .
            "\nActual:   " . var_export($actual, true) . "\n"
        );
        exit(1);
    }
}

$core = new Core();

assertSameValue(
    'AxA yA&B',
    $core->createLinkstring(['first' => 'x', 'second' => ' y', 'third' => '&B'], 'A'),
    'createLinkstring must preserve parameter order and values'
);

assertSameValue(
    'name=John+Doe&content=a%26b&zero=0',
    $core->createLinkstringUrlencode([
        'name' => 'John Doe',
        'content' => 'a&b',
        'zero' => '0',
    ]),
    'createLinkstringUrlencode must encode values and remove only the final ampersand'
);

assertSameValue(
    '',
    $core->createLinkstringUrlencode([]),
    'createLinkstringUrlencode must support an empty parameter list'
);

assertSameValue(
    ['name' => 'Alice', 'amount' => '12.50'],
    $core->paraFilter([
        'name' => 'Alice',
        'sign' => 'ignored',
        'sign_type' => 'MD5',
        'empty' => '',
        'amount' => '12.50',
    ]),
    'paraFilter must remove signature fields and empty values'
);

assertSameValue(
    md5('CxC yC&BCERT'),
    $core->buildMysign(['first' => 'x', 'second' => ' y', 'third' => '&B'], 'CERT'),
    'buildMysign output must remain compatible'
);

assertSameValue(
    ['a' => 1, 'b' => 2],
    $core->argSort(['b' => 2, 'a' => 1]),
    'argSort must sort parameters by key'
);

assertSameValue(
    '中文',
    $core->charsetEncode('中文', 'UTF-8', 'UTF-8'),
    'charsetEncode must preserve input when encodings match'
);

assertSameValue(
    '中文',
    $core->charsetDecode('中文', 'UTF-8', 'UTF-8'),
    'charsetDecode must preserve input when encodings match'
);

echo 'yii2-campay compatibility tests passed on PHP ' . PHP_VERSION . "\n";
