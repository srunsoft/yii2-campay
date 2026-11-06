<?php

declare(strict_types=1);

if ($argc !== 2 || !is_file($argv[1])) {
    fwrite(STDERR, "Usage: php compatibility-output.php /path/to/Core.php\n");
    exit(2);
}

require $argv[1];

$core = new srun\campay\Core();
$output = [
    'link' => $core->createLinkstring(
        ['first' => 'x', 'second' => ' y', 'third' => '&B'],
        'A'
    ),
    'urlencoded' => $core->createLinkstringUrlencode([
        'name' => 'John Doe',
        'content' => 'a&b',
        'zero' => '0',
    ]),
    'filtered' => $core->paraFilter([
        'name' => 'Alice',
        'sign' => 'ignored',
        'sign_type' => 'MD5',
        'empty' => '',
        'amount' => '12.50',
    ]),
    'signature' => $core->buildMysign(
        ['first' => 'x', 'second' => ' y', 'third' => '&B'],
        'CERT'
    ),
];

echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
