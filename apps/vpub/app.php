#!/usr/bin/env php
<?php
declare(strict_types=1);

namespace merms\vpub;

use merms\anno\apisdk\ApiSdk;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$storageDir = getenv('HOME') . '/.local/share/vpub';

if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

$apiSdk       = new ApiSdk('http://localhost:8080/');
$cacheService = new CacheService($storageDir . '/cache.json');
$epubService  = new EpubService();

$application = new Application();

$application->add(new AnnotationGetCommand($apiSdk, $cacheService));
$application->add(new AnnotationRemoveCommand($apiSdk, $cacheService));
$application->add(new AnnotationSetCommand($apiSdk, $cacheService));

$application->add(new EpubListCommand($epubService, $apiSdk, $cacheService));

$application->run();
