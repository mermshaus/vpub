#!/usr/bin/env php
<?php
declare(strict_types=1);

namespace merms\anno\client;

use merms\anno\apisdk\ApiSdk;
use merms\anno\checksum_cache\CacheService;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$storageDir = getenv('HOME') . '/.local/share/vpub';

if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

$apiSdk       = new ApiSdk('http://localhost:8080/');
$cacheService = new CacheService($storageDir . '/cache.json');

$application = new Application();

$application->add(new AnnotationAddCommand($apiSdk, $cacheService));
$application->add(new AnnotationGetCommand($apiSdk, $cacheService));
$application->add(new AnnotationRemoveCommand($apiSdk, $cacheService));
$application->add(new AnnotationSetCommand($apiSdk, $cacheService));
$application->add(new ChecksumsByKeyAndValueCommand($apiSdk, $cacheService));
$application->add(new RecordGetCommand($apiSdk, $cacheService));

$application->run();
