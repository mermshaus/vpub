#!/usr/bin/env php
<?php
declare(strict_types=1);

namespace merms\vpub;

use merms\anno\apisdk\ApiSdk;

require __DIR__ . '/vendor/autoload.php';

$apiSdk       = new ApiSdk('http://localhost:8080/');
$cacheService = new CacheService('/home/marc/.local/share/vpub/cache.json');

array_shift($argv);

$method = array_shift($argv);

if ($method === 'annotations.get') {
    $filepath = array_shift($argv);

    $checksum = hash_file('sha256', $filepath);

    $data = $apiSdk->getAnnotations($checksum);

    $longestKey = 0;

    foreach (array_keys($data) as $key) {
        if (strlen($key) > $longestKey) {
            $longestKey = strlen($key);
        }
    }

    foreach ($data as $key => $value) {
        printf("%s: %s\n", str_pad($key, $longestKey), $value);
    }
}

if ($method === 'checksums.get-by-key-and-value') {
    $key   = array_shift($argv);
    $value = array_shift($argv);

    $entries = $apiSdk->getChecksumsByKeyAndValue($key, $value);

    foreach ($entries as $checksum => $data) {
        if ($cacheService->findFilepath($checksum) !== null) {
            echo $cacheService->findFilepath($checksum), "\n";
        } else {
            echo $checksum, "\n";
        }
    }
}
